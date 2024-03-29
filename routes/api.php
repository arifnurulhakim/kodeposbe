<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProvController;
use App\Http\Controllers\KabController;
use App\Http\Controllers\KecController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KodePosController;
use App\Http\Controllers\UserLogController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PotensiDesaController;

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

Route::controller(ProvController::class)->group(function () {
    Route::get('/allprovinsis', 'getallprovinsi');
});
Route::controller(KodePosController::class)->group(function () {
    Route::get('/kode-pos/kodepos', 'getallkodepos');
    Route::get('/kode-pos/kodepos/{kodepos}', 'getbykodepos');
    Route::get('/kode-pos/wilayah', 'getbywilayah');
    Route::get('/kode-pos/provinsi/{provinsi}', 'getbyprovinsi');
    Route::get('/kode-pos/kabupaten/{provinsi}/{kabupaten}', 'getbykabupaten');
    Route::get('/kode-pos/kecamatan/{provinsi}/{kabupaten}/{kecamatan}', 'getbykecamatan');
    Route::get('/kode-pos/desa/{provinsi}/{kabupaten}/{kecamatan}/{desa}', 'getbydesa');
});

Route::middleware('apikey')->group(function () {
    Route::controller(KodePosController::class)->group(function () {
        Route::get('/kode-pos/{perPage?}/{currentPage?}','index');
        Route::get('/kode-pos/{id}', 'show');
        Route::get('/kode-pos/qrcode/{id}', 'generateQrCode');
        Route::get('/dashboard', 'dashboard');
    });
    Route::controller(AuthController::class)->group(function () {
        Route::get('/getProfile', 'getProfile');
        Route::post('/logout', 'logout');
        Route::post('/generateApiKey', 'generateApiKey');
    });
    Route::controller(PotensiDesaController::class)->group(function () {
        Route::get('/potensidesa', 'index');
        Route::get('/potensidesa/{id}', 'show');
    });
    Route::controller(ProvController::class)->group(function () {
        Route::get('/provinsi/{id}', 'show');
        Route::get('/provinsi/{perPage?}/{currentPage?}','index');
        
    });
    Route::controller(KabController::class)->group(function () {
        Route::get('/kabupaten/{id}', 'show');
        Route::get('/kabupaten/{perPage?}/{currentPage?}','index');
    });
      
    Route::controller(KecController::class)->group(function () {
        Route::get('/kecamatan/{id}', 'show');
        Route::get('/kecamatan/{perPage?}/{currentPage?}','index');
    });
    Route::controller(DesaController::class)->group(function () {
        Route::get('/desa/{id}', 'show');
        Route::get('/desa/{perPage?}/{currentPage?}','index');
    });

    Route::middleware('role:0')->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/getUser/{id}', 'getUser');
            Route::get('/getAlluser', 'getAlluser');
            Route::delete('/deleteUser/{id}', 'deleteUser');
            Route::put('/updateUser/{id}', 'updateUser');
            Route::get('/exportCSV', 'exportCSV');
        });
        Route::controller(ProvController::class)->group(function () {
            Route::post('/provinsi', 'store');
            Route::put('/provinsi/{id}', 'update');
            Route::delete('/provinsi/{id}', 'destroy');
        });
        Route::controller(KodePosController::class)->group(function () {
            Route::post('/kode-pos', 'store');
            Route::put('/kode-pos/{id}', 'update');
            Route::delete('/kode-pos/{id}', 'destroy');
        });
        Route::controller(UserLogController::class)->group(function () {
            Route::get('/userlog', 'index');
            Route::get('/userlog/{id}', 'show');
        });
        Route::controller(PotensiDesaController::class)->group(function () {
            Route::post('/potensidesa', 'store');
            Route::put('/potensidesa/{id}', 'update');
            Route::delete('/potensidesa/{id}', 'destroy');
        });
        Route::controller(KabController::class)->group(function () {
            Route::post('/kabupaten', 'store');
            Route::put('/kabupaten/{id}', 'update');
            Route::delete('/kabupaten/{id}', 'destroy');
        });
        Route::controller(KecController::class)->group(function () {
            Route::post('/kecamatan', 'store');
            Route::put('/kecamatan/{id}', 'update');
            Route::delete('/kecamatan/{id}', 'destroy');
        });
        Route::controller(DesaController::class)->group(function () {
            Route::post('/desa', 'store');
            Route::put('/desa/{id}', 'update');
            Route::delete('/desa/{id}', 'destroy');
        });
        
    });
    
    Route::controller(PengirimanController::class)->group(function () {
        Route::get('/pengiriman', 'index');
        Route::post('/pengiriman', 'store');
        Route::get('/pengiriman/{id}', 'show');
        Route::put('/pengiriman/{id}', 'update');
        Route::delete('/pengiriman/{id}', 'destroy');
        Route::get('/pengiriman/qrcode/{id}', 'generateQrCode');
    });

});




// Route::controller(KodePosController::class)->group(function () {
//     Route::get('/kode-pos', 'index');
//     Route::post('/kode-pos', 'store');
//     Route::get('/kode-pos/{id}', 'show');
//     Route::put('/kode-pos/{id}', 'update');
//     Route::delete('/kode-pos/{id}', 'destroy');
//     Route::get('/kode-pos/qrcode/{id}', 'generateQrCode');
// });

// Route::controller(PengirimanController::class)->group(function () {
//     Route::get('/pengiriman', 'index');
//     Route::post('/pengiriman', 'store');
//     Route::get('/pengiriman/{id}', 'show');
//     Route::put('/pengiriman/{id}', 'update');
//     Route::delete('/pengiriman/{id}', 'destroy');
//     Route::get('/pengiriman/qrcode/{id}', 'generateQrCode');
// });

// Route::controller(UserLogController::class)->group(function () {
//     Route::get('/userlog', 'index');
//     Route::get('/userlog/{id}', 'show');
// });

// Route::controller(ProvController::class)->group(function () {
//     Route::get('/provinsi', 'index');
//     Route::post('/provinsi', 'store');
//     Route::get('/provinsi/{id}', 'show');
//     Route::put('/provinsi/{id}', 'update');
//     Route::delete('/provinsi/{id}', 'destroy');
// });

// Route::controller(KabController::class)->group(function () {
//     Route::get('/kabupaten', 'index');
//     Route::post('/kabupaten', 'store');
//     Route::get('/kabupaten/{id}', 'show');
//     Route::put('/kabupaten/{id}', 'update');
//     Route::delete('/kabupaten/{id}', 'destroy');
// });

// Route::controller(KecController::class)->group(function () {
//     Route::get('/kecamatan', 'index');
//     Route::post('/kecamatan', 'store');
//     Route::get('/kecamatan/{id}', 'show');
//     Route::put('/kecamatan/{id}', 'update');
//     Route::delete('/kecamatan/{id}', 'destroy');
// });

// Route::controller(DesaController::class)->group(function () {
//     Route::get('/desa', 'index');
//     Route::post('/desa', 'store');
//     Route::get('/desa/{id}', 'show');
//     Route::put('/desa/{id}', 'update');
//     Route::delete('/desa/{id}', 'destroy');
// });

