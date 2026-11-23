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
