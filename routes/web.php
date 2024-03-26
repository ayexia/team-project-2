<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactUsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;

use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $categories = Category::all();
    return view('welcome', compact('categories'));
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/profile', function () {
    return '<h1>Your profile</h1>';
})->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/change-user-type/{user}', [AdminController::class, 'changeUserType'])->name('admin.changeUserType');
    Route::post('/admin/processing-order/{order}', [OrderController::class, 'processingOrder'])->name('admin.processingOrder');
    Route::post('/admin/shipped-order/{order}', [OrderController::class, 'shippedOrder'])->name('admin.shippedOrder');
    Route::post('/admin/delivered-order/{order}', [OrderController::class, 'deliveredOrder'])->name('admin.deliveredOrder');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/product', [ProductController::class, 'index'])->name('product.index')->middleware(['auth', 'admin']);
Route::get('/products', [ProductController::class, 'indexForUser'])->name('products');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create')->middleware(['auth', 'admin']);
Route::post('/product', [ProductController::class, 'store'])->name('product.store')->middleware(['auth', 'admin']);
Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit')->middleware(['auth', 'admin']);
Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update')->middleware(['auth', 'admin']);
Route::delete('/product/{product}/destroy', [ProductController::class, 'destroy'])->name('product.destroy')->middleware(['auth', 'admin']);
Route::get('/product/{product}/show', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/{category}', [ProductController::class, 'viewCategory'])->name('view.category');

Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/product/{item}', [CartController::class, 'addToCart'])->name('add.to.cart');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('update.cart');
Route::get('/delete/{item}', [CartController::class, 'removeFromCart'])->name('remove.from.cart');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'confirmOrder'])->name('order');
Route::get('/orders', [OrderController::class, 'order'])->name('orders');
Route::post('/order/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');
Route::post('/order/{order}/return', [OrderController::class, 'returnOrder'])->name('order.return');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('auth');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware(['auth', 'admin']);
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware(['auth', 'admin']);
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware(['auth', 'admin']);
Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update')->middleware(['auth', 'admin']);
Route::delete('/categories/{category}/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware(['auth', 'admin']);
Route::get('/categories/{category}/show', [CategoryController::class, 'show'])->name('categories.show')->middleware('auth');

Route::get('/wishlist', [WishlistController::class, 'wishlist'])->name('wishlist');
Route::get('/wishlist/{item}', [WishlistController::class, 'addToWishlist'])->name('add.to.wishlist');
Route::get('wishlist//{item}/delete', [WishlistController::class, 'removeFromWishlist'])->name('remove.from.wishlist');

Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contactus.store');
Route::get('/contact-us', function () {
    $categories = Category::all();
    return view('contact-us', compact('categories'));
})->name('contact-us');

Route::get('/about-us', function () {
    $categories = Category::all();
    return view('about-us', compact('categories'));
})->name('about-us');

require __DIR__.'/auth.php';
