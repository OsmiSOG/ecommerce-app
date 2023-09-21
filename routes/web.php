<?php

use App\Http\Controllers\Commerce\LandingController;
use App\Http\Controllers\ProfileController;
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

    Route::middleware('verified', 'available-seller')->group(function () {
        Route::prefix('/dashboard')->group(function () {

        });
        Route::prefix('/products')->group(function () {
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
