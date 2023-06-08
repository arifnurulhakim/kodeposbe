<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\PaymentCallbackController; // => letakkan dibagian atas
use App\Http\Controllers\OrderController; // => letakkan dibagian atas
use App\Http\Controllers\ProductController; // => letakkan dibagian atas
use App\Http\Controllers\KeranjangController; // => letakkan dibagian atas
use App\Http\Controllers\ProvController; // => letakkan dibagian atas
use App\Http\Controllers\KabController; // => letakkan dibagian atas
use App\Http\Controllers\KecController; // => letakkan dibagian atas
use App\Http\Controllers\DesaController; // => letakkan dibagian atas
use App\Http\Controllers\KodePosController; // => letakkan dibagian atas
use App\Http\Controllers\UserLogController; // => letakkan dibagian atas
use App\Http\Controllers\PengirimanController; // => letakkan dibagian atas

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache cleared</h1>';
})->name('clear-cache');

Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
})->name('route-clear');

Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Configuration cached</h1>';
})->name('config-cache');

Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Configuration cached</h1>';
})->name('optimize');

Route::get('/storage-link', function() {
    $exitCode = Artisan::call('storage:link');
    return '<h1>storage linked</h1>';
})->name('storage-link');

Route::controller(AuthController::class)->group(function () {
    // Route login tidak perlu middleware auth:api
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::get('/getProfile', 'getProfile');
    Route::get('/getUser/{id}', 'getUser');
    Route::get('/getAlluser', 'getAlluser');
    Route::delete('/deleteUser/{id}', 'deleteUser');
    Route::post('/updateUser/{id}', 'updateUser');
    Route::post('/logout', 'logout');
    Route::get('/exportCSV', 'exportCSV');

});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/password/email', '__invoke')->name('postemail');
    Route::post('/password/email', '__invoke')->name('email');
});

Route::controller(CodeCheckController::class)->group(function () {
    Route::get('/password/code/check', '__invoke')->name('get_check');
    Route::post('/password/code/check', '__invoke')->name('post_check');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/password/reset', '__invoke')->name('postreset');
    Route::post('/password/reset', '__invoke')->name('reset');
});

Route::post('/reset-first-password', [ResetPasswordController::class, 'resetFirstPassword'])->name('reset-first-password');

Route::post('/sendMessage/{eventName}', [PusherController::class, 'sendMessage'])->name('sendMessage');
Route::get('/chatList', [PusherController::class, 'chatList']);
Route::get('/chatList/{id_penerima}', [PusherController::class, 'chatDetail']);

Route::get('/test', [PusherController::class, 'test'])->name('test');
// Route::get('/orders-show', [OrderController::class, 'show'])->name('orders-show');

Route::resource('orders', OrderController::class);
Route::resource('product', ProductController::class);
Route::resource('keranjang', KeranjangController::class);
// Route::resource('orders', OrderController::class)->only(['index', 'show']);
 
Route::post('payments/midtrans-notification', [PaymentCallbackController::class, 'receive']);

// provinsi
Route::controller(ProvController::class)->group(function () {

    Route::get('/provinsi', 'index');
    Route::post('/provinsi', 'store');
    Route::get('/provinsi/{id}', 'show');
    Route::put('/provinsi/{id}', 'update');
    Route::delete('/provinsi/{id}', 'destroy');

});

// kabupaten
Route::controller(KabController::class)->group(function () {

    Route::get('/kabupaten', 'index');
    Route::post('/kabupaten', 'store');
    Route::get('/kabupaten/{id}', 'show');
    Route::put('/kabupaten/{id}', 'update');
    Route::delete('/kabupaten/{id}', 'destroy');

});

// kecamatan
Route::controller(KecController::class)->group(function () {

    Route::get('/kecamatan', 'index');
    Route::post('/kecamatan', 'store');
    Route::get('/kecamatan/{id}', 'show');
    Route::put('/kecamatan/{id}', 'update');
    Route::delete('/kecamatan/{id}', 'destroy');

});

// desa
Route::controller(DesaController::class)->group(function () {

    Route::get('/desa', 'index');
    Route::post('/desa', 'store');
    Route::get('/desa/{id}', 'show');
    Route::put('/desa/{id}', 'update');
    Route::delete('/desa/{id}', 'destroy');

});

// kode pos
Route::controller(KodePosController::class)->group(function () {

    Route::get('/kode-pos', 'index');
    Route::post('/kode-pos', 'store');
    Route::get('/kode-pos/{id}', 'show');
    Route::put('/kode-pos/{id}', 'update');
    Route::delete('/kode-pos/{id}', 'destroy');

});

// pengiriman
Route::controller(PengirimanController::class)->group(function () {

    Route::get('/pengiriman', 'index');
    Route::post('/pengiriman', 'store');
    Route::get('/pengiriman/{id}', 'show');
    Route::put('/pengiriman/{id}', 'update');
    Route::delete('/pengiriman/{id}', 'destroy');

});


