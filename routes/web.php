<?php

use App\Http\Controllers\Commerce\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\ProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', LandingController::class)->name('home');
Route::get('/products', LandingController::class);
Route::get('/products/{product}', LandingController::class);

Route::get('/services', LandingController::class);
Route::get('/services/{service}', LandingController::class);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/shopping-history')->group(function () {

    });

    Route::prefix('/subscriptions')->group(function () {

    });

    Route::prefix('/transactions')->group(function () {

    });

    Route::prefix('/cart')->group(function () {

    });

    Route::prefix('sell')->as('sell.')->middleware('verified', 'enable-seller')->group(function () {
        Route::prefix('/dashboard')->group(function () {

        });
        Route::prefix('/product')->as('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/edit/{}', [ProductController::class, 'edit'])->name('edit');
            Route::patch('/', [ProductController::class, 'update'])->name('update');
            Route::delete('/', [ProductController::class, 'delete'])->name('delete');

            Route::prefix('/deals')->group(function () {

            });

            Route::prefix('/stock')->group(function () {

            });
        });
        Route::prefix('/services')->group(function () {
            Route::prefix('/plans')->group(function () {

            });
            Route::prefix('/subscribtions')->group(function () {

            });
        });
    });
});

require __DIR__.'/auth.php';
