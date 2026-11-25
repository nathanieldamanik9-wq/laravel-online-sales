<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ShippingService;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin Toko',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        User::updateOrCreate(['email' => 'customer@example.com'], [
            'name' => 'Budi Customer',
            'password' => 'password123',
            'role' => 'customer',
        ]);

        $elektronik = Category::updateOrCreate(['slug' => 'elektronik'], ['name' => 'Elektronik']);
        $aksesoris = Category::updateOrCreate(['slug' => 'aksesoris'], ['name' => 'Aksesoris']);

        $products = [
            ['SKU-001', 'Keyboard Mechanical', 'keyboard-mechanical', $elektronik->id, 350000, 20],
            ['SKU-002', 'Mouse Wireless', 'mouse-wireless', $elektronik->id, 150000, 30],
            ['SKU-003', 'Headset Gaming', 'headset-gaming', $elektronik->id, 275000, 15],
            ['SKU-004', 'Mousepad Besar', 'mousepad-besar', $aksesoris->id, 75000, 50],
            ['SKU-005', 'Kabel USB-C', 'kabel-usb-c', $aksesoris->id, 45000, 100],
        ];

        foreach ($products as [$sku, $name, $slug, $categoryId, $price, $stock]) {
            Product::updateOrCreate(['sku' => $sku], [
                'category_id' => $categoryId,
                'name' => $name,
                'slug' => $slug,
                'description' => 'Produk contoh: '.$name,
                'price' => $price,
                'stock' => $stock,
                'is_active' => true,
            ]);
        }

        $shipping = [
            ['JNE', 'REG', '2-3 hari', 20000],
            ['JNE', 'YES', '1 hari', 35000],
            ['J&T', 'EZ', '2-4 hari', 18000],
        ];

        foreach ($shipping as [$courier, $service, $eta, $cost]) {
            ShippingService::updateOrCreate(
                ['courier' => $courier, 'service' => $service],
                ['eta' => $eta, 'cost' => $cost, 'is_active' => true]
            );
        }
    }
}