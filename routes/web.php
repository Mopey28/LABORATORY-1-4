<?php

use App\Http\Controllers\PaypalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('/auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/create-product', [AdminController::class, 'createProduct'])->name('admin.create-product');
        Route::post('/store-product', [AdminController::class, 'storeProduct'])->name('admin.store-product');
        Route::get('/manage-products', [AdminController::class, 'manageProducts'])->name('admin.manage-products');
        Route::get('/search-products', [AdminController::class, 'searchProducts'])->name('admin.search-products');
        Route::get('/edit-product/{id}', [AdminController::class, 'editProduct'])->name('admin.edit-product');
        Route::put('/update-product/{id}', [AdminController::class, 'updateProduct'])->name('admin.update-product');
        Route::delete('/delete-product/{id}', [AdminController::class, 'destroyProduct'])->name('admin.delete-product');

        // Routes for Main Category
        Route::get('/manage-main-categories', [AdminController::class, 'manageMainCategories'])->name('admin.manage-main-categories');
        Route::get('/create-main-category', [AdminController::class, 'createMainCategory'])->name('admin.create-main-category');
        Route::post('/store-main-category', [AdminController::class, 'storeMainCategory'])->name('admin.store-main-category');
        Route::get('/edit-main-category/{id}', [AdminController::class, 'editMainCategory'])->name('admin.edit-main-category');
        Route::put('/update-main-category/{id}', [AdminController::class, 'updateMainCategory'])->name('admin.update-main-category');
        Route::delete('/delete-main-category/{id}', [AdminController::class, 'deleteMainCategory'])->name('admin.delete-main-category');

        // Routes for Sub-Category
        Route::get('/manage-sub-categories', [AdminController::class, 'manageSubCategories'])->name('admin.manage-sub-categories');
        Route::get('/create-sub-category', [AdminController::class, 'createSubCategory'])->name('admin.create-sub-category');
        Route::post('/store-sub-category', [AdminController::class, 'storeSubCategory'])->name('admin.store-sub-category');
        Route::get('/edit-sub-category/{id}', [AdminController::class, 'editSubCategory'])->name('admin.edit-sub-category');
        Route::put('/update-sub-category/{id}', [AdminController::class, 'updateSubCategory'])->name('admin.update-sub-category');
        Route::delete('/delete-sub-category/{id}', [AdminController::class, 'deleteSubCategory'])->name('admin.delete-sub-category');

        // Routes for Sub-sub-Category
        Route::get('/manage-sub-sub-categories', [AdminController::class, 'manageSubSubCategories'])->name('admin.manage-sub-sub-categories');
        Route::get('/create-sub-sub-category', [AdminController::class, 'createSubSubCategory'])->name('admin.create-sub-sub-category');
        Route::post('/store-sub-sub-category', [AdminController::class, 'storeSubSubCategory'])->name('admin.store-sub-sub-category');
        Route::get('/edit-sub-sub-category/{id}', [AdminController::class, 'editSubSubCategory'])->name('admin.edit-sub-sub-category');
        Route::put('/update-sub-sub-category/{id}', [AdminController::class, 'updateSubSubCategory'])->name('admin.update-sub-sub-category');
        Route::delete('/delete-sub-sub-category/{id}', [AdminController::class, 'deleteSubSubCategory'])->name('admin.delete-sub-sub-category');

        Route::get('/manage-suppliers', [AdminController::class, 'manageSuppliers'])->name('admin.manage-suppliers');
        Route::get('/create-supplier', [AdminController::class, 'createSupplier'])->name('admin.create-supplier');
        Route::post('/store-supplier', [AdminController::class, 'storeSupplier'])->name('admin.store-supplier');
        Route::get('/edit-supplier/{id}', [AdminController::class, 'editSupplier'])->name('admin.edit-supplier');
        Route::put('/update-supplier/{id}', [AdminController::class, 'updateSupplier'])->name('admin.update-supplier');
        Route::delete('/delete-supplier/{id}', [AdminController::class, 'deleteSupplier'])->name('admin.delete-supplier');
        Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/create-user', [AdminController::class, 'createUser'])->name('admin.create-user');
        Route::post('/store-user', [AdminController::class, 'storeUser'])->name('admin.store-user');
        Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit-user');
        Route::put('/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.update-user');
        Route::delete('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete-user');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');

        // New routes for Stock Management
        Route::get('/add-stock', [AdminController::class, 'addStock'])->name('admin.add-stock');
        Route::post('/store-stock', [AdminController::class, 'storeStock'])->name('admin.store-stock');
        Route::get('/manage-stock', [AdminController::class, 'manageStock'])->name('admin.manage-stock');
        Route::get('/edit-stock/{id}', [AdminController::class, 'editStock'])->name('admin.edit-stock');
        Route::put('/update-stock/{id}', [AdminController::class, 'updateStock'])->name('admin.update-stock');
        Route::delete('/delete-stock/{id}', [AdminController::class, 'deleteStock'])->name('admin.delete-stock');

        // Stock History Route
        Route::get('/stock-history', [AdminController::class, 'stockHistory'])->name('admin.stock-history');

        // Routes for fetching sub-categories and sub-sub-categories
        Route::get('/show-sub-categories/{mainCategoryId}', [AdminController::class, 'showSubCategories']);
        Route::get('/show-sub-sub-categories/{subCategoryId}', [AdminController::class, 'showSubSubCategories']);

        // New routes for Admin Profile Management
        Route::get('/profile', [AdminController::class, 'showProfile'])->name('admin.profile');
        Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
        Route::put('/profile/update-password', [AdminController::class, 'updateUserPassword'])->name('admin.profile.update-password');
        Route::post('/profile/update-profile-picture', [AdminController::class, 'updateProfilePicture'])->name('admin.profile.update-picture');

        // New routes for managing discounts
        Route::get('/manage-discounts', [AdminController::class, 'manageDiscounts'])->name('admin.manage-discounts');
        Route::get('/create-discount', [AdminController::class, 'createDiscount'])->name('admin.create-discount');
        Route::post('/store-discount', [AdminController::class, 'storeDiscount'])->name('admin.store-discount');
        Route::get('/edit-discount/{id}', [AdminController::class, 'editDiscount'])->name('admin.edit-discount');
        Route::put('/update-discount/{id}', [AdminController::class, 'updateDiscount'])->name('admin.update-discount');
        Route::delete('/delete-discount/{id}', [AdminController::class, 'deleteDiscount'])->name('admin.delete-discount');

        Route::get('/admin/manage-orders', [AdminController::class, 'manageOrders'])->name('admin.manage-orders');
        Route::get('/admin/pending-orders', [AdminController::class, 'pendingOrders'])->name('admin.pending-orders');
        Route::get('/admin/edit-order/{id}', [AdminController::class, 'editOrder'])->name('admin.edit-order');
        Route::put('/admin/update-order/{id}', [AdminController::class, 'updateOrder'])->name('admin.update-order');
        Route::put('/admin/update-order-status/{id}', [AdminController::class, 'updateOrderStatus'])->name('admin.update-order-status');
        Route::delete('/admin/delete-order/{id}', [AdminController::class, 'deleteOrder'])->name('admin.delete-order');
        Route::get('/admin/order-history', [AdminController::class, 'orderHistory'])->name('admin.order-history');

        Route::get('/manage-colors', [AdminController::class, 'manageColors'])->name('manage-colors');
        Route::get('/manage-sizes', [AdminController::class, 'manageSizes'])->name('manage-sizes');
        Route::get('/admin/manage-variations', [AdminController::class, 'manageVariations'])->name('admin.manage-variations');
        Route::get('/admin/edit-variation/{id}', [AdminController::class, 'editVariation'])->name('admin.edit-variation');
        Route::put('/admin/update-variation/{id}', [AdminController::class, 'updateVariation'])->name('admin.update-variation');
        Route::delete('/admin/delete-variation/{id}', [AdminController::class, 'deleteVariation'])->name('admin.delete-variation');

    });
});

Route::middleware(['auth', RoleMiddleware::class . ':user'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/search', [UserController::class, 'search'])->name('search');
    Route::get('/filter-products', [UserController::class, 'filterProducts'])->name('user.filter-products');
    Route::get('/user-profile', [UserController::class, 'showUserProfile'])->name('user.profile.dashboard');
    Route::post('/add-to-wishlist/{product}', [UserController::class, 'addToWishlist'])->name('user.add-to-wishlist');
    Route::post('/add-to-cart/{product}', [UserController::class, 'addToCart'])->name('user.add-to-cart');
    Route::get('/wishlist', [UserController::class, 'showWishlist'])->name('user.wishlist.dashboard');
    Route::get('/cart', [UserController::class, 'showCart'])->name('user.cart.dashboard');
    Route::delete('/wishlist/{productId}', [UserController::class, 'removeFromWishlist'])->name('user.removeFromWishlist');
    Route::delete('/cart/{productId}', [UserController::class, 'removeFromCart'])->name('user.removeFromCart');
    Route::get('/checkout', [UserController::class, 'showCheckoutForm'])->name('user.checkout');
    Route::post('/checkout', [UserController::class, 'processCheckout'])->name('user.processCheckout');
    Route::post('/user/get-selected-cart-items', [UserController::class, 'getSelectedCartItems'])->name('user.getSelectedCartItems');
    Route::get('/profile-dashboard', [UserController::class, 'showProfile'])->name('user.profile-dashboard');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('user.update-profile');
    Route::get('/profile/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change-password');
    Route::post('/profile/update-password', [UserController::class, 'changePassword'])->name('user.update-password');
    Route::post('/profile/update-avatar', [UserController::class, 'updateAvatar'])->name('user.update-avatar');
    Route::get('/user/filter-products', [UserController::class, 'filterProducts'])->name('user.filterProducts');
    Route::post('/user/update-cart-quantity', [UserController::class, 'updateCartQuantity'])->name('user.updateCartQuantity');
    Route::get('/order-complete/{orderId}', [UserController::class, 'showOrderComplete'])->name('user.orderComplete');
});

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.post');

Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
Route::get('success', [PaypalController::class, 'success'])->name('success');
Route::get('cancel', [PaypalController::class, 'cancel'])->name('cancel');
