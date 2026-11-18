<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingService;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja masih kosong.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = collect();
        $subtotal = 0;

        foreach ($cart as $productId => $qty) {
            if (isset($products[$productId])) {
                $product = $products[$productId];
                $lineTotal = $product->price * $qty;
                $subtotal += $lineTotal;
                $items->push([
                    'product' => $product,
                    'quantity' => $qty,
                    'lineTotal' => $lineTotal,
                ]);
            }
        }

        // Buat token unik di session untuk mencegah double submit
        $checkoutToken = (string) Str::uuid();
        session(['checkout_token' => $checkoutToken]);

        $shippingServices = ShippingService::where('is_active', true)->get();

        return view('checkout.index', compact(
            'items',
            'subtotal',
            'shippingServices',
            'checkoutToken'
        ));
    }

    public function store(CheckoutRequest $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja masih kosong.');
        }

        // Validasi checkout_token untuk mencegah pengiriman ganda (double submit)
        $sessionToken = session()->pull('checkout_token');
        if (!$sessionToken || $sessionToken !== $request->input('checkout_token')) {
            return redirect()->route('checkout.index')->with('error', 'Sesi checkout tidak valid atau sudah diproses.');
        }

        $shippingService = ShippingService::where('is_active', true)->findOrFail($request->shipping_service_id);

        $order = DB::transaction(function () use ($request, $cart, $shippingService) {
            $products = Product::whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cart as $productId => $qty) {
                $product = $products->get($productId);

                if (!$product || !$product->is_active) {
                    throw ValidationException::withMessages([
                        'cart' => 'Salah satu produk di keranjang sudah tidak tersedia.',
                    ]);
                }

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok produk {$product->name} tidak mencukupi (tersisa {$product->stock}).",
                    ]);
                }

                $lineTotal = $product->price * $qty;
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'subtotal' => $lineTotal,
                ];
            }

            $shippingCost = $shippingService->cost;
            $grandTotal = $subtotal + $shippingCost;
            $orderCode = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_service_id' => $shippingService->id,
                'code' => $orderCode,
                'status' => 'pending',
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'shipping_courier' => $shippingService->courier,
                'shipping_service' => $shippingService->service,
                'shipping_eta' => $shippingService->eta,
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
                'ordered_at' => now(),
            ]);

            foreach ($orderItemsData as $item) {
                $order->items()->create($item);

                $product = $products->get($item['product_id']);
                $product->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'type' => 'OUT',
                    'quantity' => $item['quantity'],
                    'reference' => $order->code,
                    'note' => 'Checkout pesanan ' . $order->code,
                    'movement_at' => now(),
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibuat!');
    }
}