<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradesController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/options', [OptionsController::class, 'index'])->name('options');
    Route::post('/options', [OptionsController::class, 'store']);

    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
    Route::get('/trades', [TradesController::class, 'index'])->name('trades');
    Route::get('/trade', [TradeController::class, 'trade']);
    Route::get('/trades/orders', [TradesController::class, 'orders'])->name('trades.orders');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/password', [PasswordController::class, 'index'])->name('password');
    Route::post('/password', [PasswordController::class, 'store']);
});

require __DIR__.'/auth.php';
