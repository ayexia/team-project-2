<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
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
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

// Route::get('post', [HomeController::class, 'post'])->middleware(['auth', 'admin']); - used for test

//links to profile page for verified users only, redirects to login page otherwise
Route::get('/profile', function () {
    return '<h1>Your profile</h1>';
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/product', [ProductController::class, 'index'])->name('product.index')->middleware('auth');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create')->middleware(['auth', 'admin']);
Route::post('/product', [ProductController::class, 'store'])->name('product.store')->middleware(['auth', 'admin']);
Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit')->middleware(['auth', 'admin']);
Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('product.update')->middleware(['auth', 'admin']);
Route::delete('/product/{product}/destroy', [ProductController::class, 'destroy'])->name('product.destroy')->middleware(['auth', 'admin']);
Route::get('/product/{product}/show', [ProductController::class, 'show'])->name('product.show')->middleware('auth');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('auth');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create')->middleware(['auth', 'admin']);
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware(['auth', 'admin']);
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware(['auth', 'admin']);
Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update')->middleware(['auth', 'admin']);
Route::delete('/categories/{category}/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware(['auth', 'admin']);
Route::get('/categories/{category}/show', [CategoryController::class, 'show'])->name('categories.show')->middleware('auth');
Route::get('/getSlug', function(Request $request) {
    $slug = '';
    if (!empty($request->title)) {
        $slug = Str::slug($request->title);

        // Save the slug to the categories table
        $category = new Category;
        $category->name = $request->title;
        $category->slug = $slug;
        $category->save();
    }

    return response()->json([
        'status' => true,
        'slug' => $slug
    ]);
})->name('getSlug')->middleware(['auth', 'admin']);

require __DIR__.'/auth.php';
