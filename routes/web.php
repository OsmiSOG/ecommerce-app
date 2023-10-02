<?php

use App\Http\Controllers\Commerce\LandingController;
use App\Http\Controllers\Commerce\ProductsDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductStockController;
use App\Http\Controllers\Seller\ServiceController;
use App\Http\Controllers\Seller\ServicePlanController;
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
Route::get('/products', ProductsDashboardController::class)->name('products');
Route::get('/products/{product}', LandingController::class);

Route::get('/services', LandingController::class)->name('services');
Route::get('/services/{service}', LandingController::class);

Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->middleware(['auth'])->name('welcome');

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
        Route::prefix('/dashboard')->as('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
        });
        Route::prefix('/product')->as('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/create', [ProductController::class, 'create'])->name('create');
            Route::post('/', [ProductController::class, 'store'])->name('store');
            Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('edit');
            Route::patch('/{update}', [ProductController::class, 'update'])->name('update');

            Route::prefix('/deals')->group(function () {

            });

            Route::prefix('/stock')->as('stock.')->group(function () {
                Route::get('/{product}/{available}', [ProductStockController::class, 'get'])->name('get');
                Route::post('/{product}', [ProductStockController::class, 'store'])->name('store');
                Route::delete('/{productStock}', [ProductStockController::class, 'destroy'])->name('delete');
            });
        });
        Route::prefix('/services')->as('service.')->group(function () {
            Route::get('/', [ServiceController::class, 'index'])->name('index');
            Route::get('/create', [ServiceController::class, 'create'])->name('create');
            Route::post('/', [ServiceController::class, 'store'])->name('store');
            Route::get('/edit/{service}', [ServiceController::class, 'edit'])->name('edit');
            Route::patch('/{service}', [ServiceController::class, 'update'])->name('update');

            Route::prefix('/plans')->as('plan.')->group(function () {
                Route::get('/{service}', [ServicePlanController::class, 'get'])->name('get');
                Route::post('/{service}', [ServicePlanController::class, 'store'])->name('store');
                Route::put('/{servicePlan}', [ServicePlanController::class, 'update'])->name('update');
                Route::put('/toggle-active/{servicePlan}', [ServicePlanController::class, 'toggleActive'])->name('toggle-active');
                Route::delete('/{servicePlan}', [ServicePlanController::class, 'destroy'])->name('delete');
            });

            Route::prefix('/subscribtions')->group(function () {

            });
        });
    });
});

require __DIR__.'/auth.php';
