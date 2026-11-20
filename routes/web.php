<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ShippingServiceController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ProductCatalogController;
use Illuminate\Support\Facades\Route;

// ---------- Autentikasi ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Katalog publik (M4.2) ----------
Route::get('/', [ProductCatalogController::class, 'index'])->name('shop.index');
Route::get('/produk/{product:slug}', [ProductCatalogController::class, 'show'])->name('shop.show');

// ---------- Keranjang belanja & Checkout pelanggan (M5.3 & M6.7) ----------
Route::middleware(['auth', 'role:customer'])->group(function () {
    // M5 - Keranjang belanja
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

    // M6 - Checkout & Riwayat pesanan pelanggan
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/pesanan', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
});

// ---------- Admin (M2, M3 & M7.4) ----------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // M7 - Dashboard Admin (menggantikan placeholder M1)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // M2 - Master kategori dan produk
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::resource('products', AdminProductController::class)->except('show');

    // M3 - Barang masuk dan histori stok
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');

    // M3 - Layanan ekspedisi
    Route::get('/shipping', [ShippingServiceController::class, 'index'])->name('shipping.index');
    Route::post('/shipping', [ShippingServiceController::class, 'store'])->name('shipping.store');
    Route::put('/shipping/{shippingService}', [ShippingServiceController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/{shippingService}', [ShippingServiceController::class, 'destroy'])->name('shipping.destroy');

    // M7 - Transaksi admin
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/confirm', [AdminOrderController::class, 'confirm'])->name('orders.confirm');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
});
