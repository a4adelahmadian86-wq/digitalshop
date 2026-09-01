<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminDiscountController;
use App\Http\Controllers\AdminStorageProviderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminWalletController;
use App\Http\Controllers\AccountController;


// ======================================================
// Home
// ======================================================

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');


// ======================================================
// Authentication
// ======================================================

Route::get(
    '/login',
    [AuthController::class, 'login']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'loginStore']
)->name('login.store');

Route::post(
    '/login/check-phone',
    [AuthController::class, 'checkPhone']
)->name('login.check.phone');

Route::get(
    '/login/password',
    [AuthController::class, 'passwordForm']
)->name('login.password');

Route::post(
    '/login/password',
    [AuthController::class, 'passwordLogin']
)->name('login.password.store');

Route::get(
    '/register',
    [AuthController::class, 'register']
)->name('register');

Route::post(
    '/register',
    [AuthController::class, 'registerStore']
)->name('register.store');


// ======================================================
// OTP Verification
// ======================================================

Route::get(
    '/otp',
    [OtpController::class, 'show']
)->name('otp.show');

Route::post(
    '/otp/send',
    [OtpController::class, 'send']
)->name('otp.send');

Route::post(
    '/otp/verify',
    [OtpController::class, 'verify']
)->name('otp.verify');

Route::post(
    '/otp/resend',
    [OtpController::class, 'resend']
)->name('otp.resend');


// ======================================================
// Password Reset
// ======================================================

Route::get(
    '/forgot-password',
    [PasswordResetController::class, 'showRequestForm']
)->name('password.request');

Route::post(
    '/forgot-password',
    [PasswordResetController::class, 'sendOtp']
)->name('password.send');


// ======================================================
// Logout
// ======================================================

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->middleware('auth')
  ->name('logout');


// ======================================================
// Products
// ======================================================

Route::get(
    '/products',
    [ProductController::class, 'search']
)->name('products.index');

Route::get(
    '/products/{product:slug}',
    [ProductController::class, 'show']
)->name('product.show');

Route::get(
    '/search',
    [ProductController::class, 'search']
)->name('search');


// ======================================================
// Cart
// ======================================================

Route::get(
    '/cart',
    [CartController::class, 'index']
)->name('cart');


// ------------------------------------------------------
// Cart Discount
// ------------------------------------------------------

Route::post(
    '/cart/discount',
    [CheckoutController::class, 'applyDiscount']
)->name('cart.discount');

Route::delete(
    '/cart/discount',
    [CheckoutController::class, 'removeDiscount']
)->name('cart.discount.remove');


// ------------------------------------------------------
// Cart Product Actions
// ------------------------------------------------------

Route::post(
    '/cart/{product}/later',
    [CartController::class, 'later']
)->name('cart.later');

Route::post(
    '/cart/{product}/move-to-cart',
    [CartController::class, 'moveToCart']
)->name('cart.move');

Route::post(
    '/cart/{product}',
    [CartController::class, 'add']
)->name('cart.add');

Route::delete(
    '/cart/{product}',
    [CartController::class, 'remove']
)->name('cart.remove');


// ======================================================
// Checkout
// ======================================================

Route::middleware('auth')->group(function () {

        /* Buyer Panel Routes */

// Buyer Panel routes - add inside the existing Route::middleware('auth')->group(...)
Route::get('/account', fn () => redirect()->route('account.dashboard'))->name('account');
Route::get('/account/dashboard', [AccountController::class, 'dashboard'])->name('account.dashboard');
Route::get('/account/orders', [AccountController::class, 'orders'])->name('account.orders');
Route::get('/account/orders/{order}', [AccountController::class, 'order'])->name('account.orders.show');
Route::get('/account/files', [AccountController::class, 'files'])->name('account.files');
Route::get('/account/wallet', [AccountController::class, 'wallet'])->name('account.wallet');
Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
Route::get('/account/security', [AccountController::class, 'security'])->name('account.security');
Route::put('/account/security', [AccountController::class, 'updateSecurity'])->name('account.security.update');
Route::get('/account/notifications', [AccountController::class, 'notifications'])->name('account.notifications');
Route::get('/account/notifications/{id}/read', [AccountController::class, 'readNotification'])->name('account.notifications.read');


    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.read-all');

    Route::get(
        '/notifications/{notification}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::get(
        '/checkout',
        [CheckoutController::class, 'index']
    )->name('checkout');

    Route::post(
        '/checkout',
        [CheckoutController::class, 'store']
    )->name('checkout.store');

    Route::get(
        '/checkout/payment',
        [CheckoutController::class, 'payment']
    )->middleware('auth')
     ->name('checkout.payment');

    Route::post(
        '/checkout/payment',
        [CheckoutController::class, 'paymentStore']
    )->middleware('auth')
     ->name('checkout.payment.store');

    // --------------------------------------------------
    // Checkout Discount
    // --------------------------------------------------

    Route::post(
        '/checkout/discount',
        [CheckoutController::class, 'applyDiscount']
    )->name('checkout.discount');

    Route::delete(
        '/checkout/discount',
        [CheckoutController::class, 'removeDiscount']
    )->name('checkout.discount.remove');


    // --------------------------------------------------
    // Checkout Success
    // --------------------------------------------------

    Route::get(
        '/checkout/success/{order}',
        [CheckoutController::class, 'success']
    )->name('checkout.success');


    // ==================================================
    // Wallet
    // ==================================================

    Route::get(
        '/wallet',
        [WalletController::class, 'index']
    )->name('wallet.index');

    Route::post(
        '/wallet/topup',
        [WalletController::class, 'topup']
    )->name('wallet.topup');

    Route::get(
        '/wallet/topup/{topup}/callback',
        [WalletController::class, 'callback']
    )->name('wallet.topup.callback');


    // ==================================================
    // Payment
    // ==================================================

    Route::get(
        '/payment/pay/{order}',
        [PaymentController::class, 'pay']
    )->name('payment.pay');

    Route::get(
        '/payment/callback',
        [PaymentController::class, 'callback']
    )->name('payment.callback');

// ==================================================
// Invoice
// ==================================================

Route::get(
    '/account/orders/{order}/invoice',
    [InvoiceController::class, 'show']
)->name('account.invoice');

    // ==================================================
    // Downloads
    // ==================================================

    Route::get(
        '/download/{item}',
        [DownloadController::class, 'download']
    )->name('download');

});


// ======================================================
// Storage Test
// ======================================================

Route::get(
    '/storage/test',
    [\App\Http\Controllers\StorageController::class, 'test']
)->name('storage.test');

Route::post(
    '/storage/test/upload',
    [\App\Http\Controllers\StorageController::class, 'upload']
)->name('storage.test.upload');


// ======================================================
// Admin
// ======================================================

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // ==================================================
        // Admin Logout
        // ==================================================

        Route::post(
            '/logout',
            [AuthController::class, 'logout']
        )->name('admin.logout');


        // ==================================================
        // Dashboard
        // ==================================================

        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('admin.dashboard');

        // مسیر قدیمی /admin/ برای جلوگیری از شکستن لینک‌های قبلی
        Route::get(
            '/',
            function () {
                return redirect()->route('admin.dashboard');
            }
        );


// ==================================================
// Users
// ==================================================

Route::resource(
    'users',
    AdminUserController::class
)
    ->except([
        'destroy',
    ])
    ->names('admin.users');

Route::patch(
    '/users/{user}/toggle',
    [AdminUserController::class, 'toggle']
)->name('admin.users.toggle');
        // ==================================================
        // Categories
        // ==================================================

        Route::get(
            '/categories',
            [AdminCategoryController::class, 'index']
        )->name('admin.categories.index');

        Route::get(
            '/categories/create',
            [AdminCategoryController::class, 'create']
        )->name('admin.categories.create');

        Route::post(
            '/categories',
            [AdminCategoryController::class, 'store']
        )->name('admin.categories.store');

        Route::get(
            '/categories/{category}/edit',
            [AdminCategoryController::class, 'edit']
        )->name('admin.categories.edit');

        Route::put(
            '/categories/{category}',
            [AdminCategoryController::class, 'update']
        )->name('admin.categories.update');

        Route::patch(
            '/categories/{category}/toggle',
            [AdminCategoryController::class, 'toggle']
        )->name('admin.categories.toggle');

        Route::delete(
            '/categories/{category}',
            [AdminCategoryController::class, 'destroy']
        )->name('admin.categories.destroy');


        // ==================================================
        // Products
        // ==================================================

        Route::resource(
            'products',
            AdminProductController::class
        )
        ->except([
            'show',
        ])
        ->names('admin.products');

        Route::patch(
            '/products/{product}/toggle',
            [AdminProductController::class, 'toggle']
        )->name('admin.products.toggle');


        // ==================================================
        // Storage Providers
        // ==================================================

        Route::get(
            '/storage',
            [AdminStorageProviderController::class, 'index']
        )->name('admin.storage.index');

        Route::get(
            '/storage/create',
            [AdminStorageProviderController::class, 'create']
        )->name('admin.storage.create');

        Route::post(
            '/storage',
            [AdminStorageProviderController::class, 'store']
        )->name('admin.storage.store');

        Route::get(
            '/storage/{storageProvider}/edit',
            [AdminStorageProviderController::class, 'edit']
        )->name('admin.storage.edit');

        Route::put(
            '/storage/{storageProvider}',
            [AdminStorageProviderController::class, 'update']
        )->name('admin.storage.update');

        Route::patch(
            '/storage/{storageProvider}/toggle',
            [AdminStorageProviderController::class, 'toggle']
        )->name('admin.storage.toggle');

        Route::patch(
            '/storage/{storageProvider}/default',
            [AdminStorageProviderController::class, 'makeDefault']
        )->name('admin.storage.default');

        Route::delete(
            '/storage/{storageProvider}',
            [AdminStorageProviderController::class, 'destroy']
        )->name('admin.storage.destroy');

        Route::post(
            '/storage/{storageProvider}/test',
            [AdminStorageProviderController::class, 'test']
        )->name('admin.storage.test');


        // ==================================================
        // Discounts
        // ==================================================

        Route::get(
            '/discounts',
            [AdminDiscountController::class, 'index']
        )->name('admin.discounts.index');

        Route::get(
            '/discounts/create',
            [AdminDiscountController::class, 'create']
        )->name('admin.discounts.create');

        Route::post(
            '/discounts',
            [AdminDiscountController::class, 'store']
        )->name('admin.discounts.store');

        Route::get(
            '/discounts/{discount}/edit',
            [AdminDiscountController::class, 'edit']
        )->name('admin.discounts.edit');

        Route::put(
            '/discounts/{discount}',
            [AdminDiscountController::class, 'update']
        )->name('admin.discounts.update');

        Route::patch(
            '/discounts/{discount}/toggle',
            [AdminDiscountController::class, 'toggle']
        )->name('admin.discounts.toggle');

        Route::delete(
            '/discounts/{discount}',
            [AdminDiscountController::class, 'destroy']
        )->name('admin.discounts.destroy');

        // ==================================================
        // Wallets
        // ==================================================

        Route::get(
            '/wallets',
            [AdminWalletController::class, 'index']
        )->name('admin.wallets.index');

        Route::get(
            '/wallets/{user}',
            [AdminWalletController::class, 'show']
        )->name('admin.wallets.show');

        Route::post(
            '/wallets/{user}/credit',
            [AdminWalletController::class, 'credit']
        )->name('admin.wallets.credit');

        Route::post(
            '/wallets/{user}/debit',
            [AdminWalletController::class, 'debit']
        )->name('admin.wallets.debit');

    });

