# Aplikasi Penjualan Online Multi-Role (Laravel)

Aplikasi web penjualan online berbasis **Laravel 13.x**, **PHP >= 8.3**, **MySQL**, dan **Blade**. Mendukung katalog produk, pencatatan stok masuk/keluar dengan histori mutasi, keranjang belanja berbasis session, checkout transaksional, dan pengelolaan pesanan oleh admin.

---

## Fitur Utama

### 1. Pelanggan (Customer) & Publik

- **Katalog Publik**: Daftar produk aktif dengan pencarian nama/SKU, filter kategori, filter rentang harga, filter stok tersedia, pengurutan (terbaru, harga, nama), pagination, dan halaman detail produk berbasis `slug`.
- **Autentikasi & Registrasi**: Login dan registrasi akun baru dengan role otomatis `customer`.
- **Keranjang Belanja**: Tambah produk ke keranjang berbasis session, ubah jumlah dengan validasi stok tersedia, dan hapus item.
- **Checkout Transaksional**: Memilih layanan ekspedisi, mengisi alamat pengiriman, menyimpan snapshot nama/harga produk dan ongkir pada `orders` serta `order_items`, mengurangi stok secara atomik (`DB::transaction` + `lockForUpdate`), dan mencatat mutasi stok `OUT`.
- **Riwayat Pesanan**: Melihat daftar pesanan milik sendiri beserta detail item dan status.

### 2. Administrator (Admin)

- **Dashboard Ringkasan**: Total produk, produk stok menipis (stok <= 5), jumlah pesanan `pending`, penjualan hari ini (di luar pesanan dibatalkan), dan 5 transaksi terbaru.
- **Master Kategori & Produk**: CRUD kategori dan produk, termasuk upload gambar ke `public/storage`. Produk yang sudah punya histori stok dinonaktifkan, bukan dihapus.
- **Manajemen Stok & Histori Mutasi**: Pencatatan barang masuk (`IN`) secara transaksional serta audit seluruh pergerakan stok (`IN` / `OUT`).
- **Layanan Ekspedisi**: Kelola layanan kurir dan ongkos kirim (dinonaktifkan, tidak dihapus).
- **Manajemen Transaksi**: Validasi pesanan (`pending` → `confirmed`), pembaruan status berurutan (`processed` → `shipped` → `completed`), dan pembatalan pesanan (status `pending`/`confirmed`) dengan pengembalian stok otomatis (movement `IN` dengan referensi `CANCEL-<kode_order>`).

---

## Struktur Proyek

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── CategoryController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── OrderController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ShippingServiceController.php
│   │   │   └── StockController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── CheckoutController.php
│   │   ├── CustomerOrderController.php
│   │   └── ProductCatalogController.php
│   ├── Middleware/
│   │   └── RoleMiddleware.php
│   └── Requests/
│       ├── CheckoutRequest.php
│       ├── StoreProductRequest.php
│       ├── StoreStockInRequest.php
│       └── UpdateProductRequest.php
├── Models/
│   ├── Category.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── ShippingService.php
│   ├── StockMovement.php
│   └── User.php
└── Providers/
    └── AppServiceProvider.php
bootstrap/
└── app.php
database/
├── migrations/
└── seeders/
public/
├── css/store.css
└── js/store.js
resources/
└── views/
    ├── layouts/
    ├── auth/
    ├── pagination/
    ├── shop/
    ├── cart/
    ├── checkout/
    ├── orders/
    └── admin/
routes/
└── web.php
```

---

## Prasyarat Sistem

- PHP >= 8.3
- Composer
- MySQL / MariaDB (XAMPP / Laragon / sejenisnya)
- Git

---

## Langkah Instalasi

1. **Clone Repository**

   ```bash
   git clone https://github.com/nathanieldamanik9-wq/laravel-online-sales.git
   cd laravel-online-sales
   ```

2. **Install Dependensi PHP**

   ```bash
   composer install
   ```

3. **Salin File Environment & Generate App Key**

   Linux / macOS:

   ```bash
   cp .env.example .env
   ```

   Windows (CMD / PowerShell):

   ```bash
   copy .env.example .env
   ```

   Lalu:

   ```bash
   php artisan key:generate
   ```

4. **Buat Database**

   Lewat terminal:

   ```bash
   mysql -u root -e "CREATE DATABASE laravel_online_sales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

   Atau buka phpMyAdmin / HeidiSQL, lalu buat database `laravel_online_sales` dengan collation `utf8mb4_unicode_ci`.

5. **Konfigurasi Database di `.env`**

   `.env.example` bawaan Laravel memakai SQLite. Ubah `DB_CONNECTION` menjadi `mysql` dan pastikan kelima baris `DB_*` tidak diawali tanda `#`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_online_sales
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Pastikan Aset Front-end Tersedia**

   File `public/css/store.css` dan `public/js/store.js` harus ada. Tanpa `store.css`, aplikasi tetap berjalan tetapi tampilan tidak berformat.

7. **Jalankan Migrasi & Seeder**

   ```bash
   php artisan migrate --seed
   ```

   Seeder membuat 1 akun admin, 3 kategori, 3 layanan ekspedisi (JNE REG, J&T EZ, SiCepat REG), dan 15 produk contoh beserta stok awalnya.

8. **Buat Symbolic Link Storage (untuk gambar produk)**

   ```bash
   php artisan storage:link
   ```

9. **Jalankan Server Lokal**

   ```bash
   php artisan serve
   ```

   Buka aplikasi di browser melalui alamat `http://127.0.0.1:8000`.

---

## Akun Latihan

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `Admin12345!` |
| **Customer** | Daftar sendiri melalui halaman Register | (sesuai isian Anda) |

> Akun admin hanya untuk lingkungan praktikum lokal. Jangan dipakai di production.

---

## Pengujian

Ikuti checklist fungsional (M8.4) dan uji akses dasar (M8.5) pada modul praktikum.

---

## Ringkasan Milestone Git

- `m0-setup` — Setup proyek Laravel, konfigurasi database, dan baseline Git
- `m1-auth-role` — Autentikasi session, kolom role, middleware role, dan seeder admin
- `m2-master-product` — Master kategori, produk, upload gambar, dan pagination
- `m3-inventory-shipping` — Barang masuk transaksional, histori stok, dan layanan ekspedisi
- `m4-storefront` — Katalog publik dengan pencarian, filter, sorting, dan detail produk
- `m5-cart` — Keranjang belanja berbasis session dengan validasi stok
- `m6-checkout` — Checkout atomik, snapshot pesanan, pengurangan stok, dan riwayat pesanan
- `m7-order-admin` — Dashboard admin, validasi pesanan, pembaruan status, dan pembatalan dengan rollback stok
- `m8-final` — Verifikasi akhir route, struktur proyek, checklist QA, dan dokumentasi `README.md`