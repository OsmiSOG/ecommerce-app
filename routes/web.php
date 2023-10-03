<?php

use App\Http\Controllers\Buyer\ProductBoughtController;
use App\Http\Controllers\Buyer\ProductCartController;
use App\Http\Controllers\Buyer\ProductCheckoutController;
use App\Http\Controllers\Buyer\SubscriptionController;
use App\Http\Controllers\Commerce\LandingController;
use App\Http\Controllers\Commerce\ProductOverviewController;
use App\Http\Controllers\Commerce\ProductsDashboardController;
use App\Http\Controllers\Commerce\ServiceOverviewController;
use App\Http\Controllers\Commerce\ServicesDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductStockController;
use App\Http\Controllers\Seller\ServiceController;
use App\Http\Controllers\Seller\ServicePlanController;
use App\Models\Product\Product;
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
Route::get('/products/{product}', ProductOverviewController::class)->name('products.overview');

Route::get('/services', ServicesDashboardController::class)->name('services');
Route::get('/services/{service}', ServiceOverviewController::class)->name('services.overview');

Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->middleware(['auth'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('/shopping-history')->as('shopping-history.')->group(function () {
        Route::get('/', [ProductBoughtController::class, 'index'])->name('index');
    });

    Route::prefix('/subscriptions')->as('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::post('/cancel/{subscription}', [SubscriptionController::class, 'cancel'])->name('cancel');
    });

    Route::prefix('/transactions')->group(function () {

    });

    Route::prefix('/cart')->as('cart.')->group(function () {
        Route::get('/', [ProductCartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [ProductCartController::class, 'add'])->name('add');
        Route::put('/substract/{product}', [ProductCartController::class, 'substract'])->name('substract');
        Route::delete('/remove/{product}', [ProductCartController::class, 'remove'])->name('remove');
        Route::post('/checkout', ProductCheckoutController::class)->name('checkout');
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
