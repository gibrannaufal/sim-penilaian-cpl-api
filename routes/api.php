<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CpmkController;
use App\Http\Controllers\Api\KurikulumController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\FilterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    
    // Kurikulum 
    Route::get('/kurikulum', [KurikulumController::class, 'index'])->middleware(['auth.api']);
    Route::get('/kurikulum/{id}', [KurikulumController::class, 'show'])->middleware(['auth.api']);
    Route::post('/kurikulum', [KurikulumController::class, 'store'])->middleware(['auth.api']);
    Route::put('/kurikulum', [KurikulumController::class, 'update'])->middleware(['auth.api']);
    Route::delete('/kurikulum/{id}', [KurikulumController::class, 'destroy'])->middleware(['auth.api']);
    
    //cpmk
    Route::get('/cpmk', [CpmkController::class, 'index'])->middleware(['auth.api']);
    Route::get('/cpmk/{id}', [CpmkController::class, 'show'])->middleware(['auth.api']);
    Route::post('/cpmk', [CpmkController::class, 'store'])->middleware(['auth.api']);
    Route::put('/cpmk', [CpmkController::class, 'update'])->middleware(['auth.api']);
    Route::delete('/cpmk/{id}', [CpmkController::class, 'destroy'])->middleware(['auth.api']);
    

    // Mata Kuliah
    Route::get('/mataKuliah', [MataKuliahController::class, 'index'])->middleware(['auth.api']);
    Route::get('/mataKuliah/{id}', [MataKuliahController::class, 'show'])->middleware(['auth.api']);
    Route::post('/mataKuliah', [MataKuliahController::class, 'store'])->middleware(['auth.api']);
    Route::put('/mataKuliah', [MataKuliahController::class, 'update'])->middleware(['auth.api']);
    Route::delete('/mataKuliah/{id}', [MataKuliahController::class, 'destroy'])->middleware(['auth.api']);
    


    //filter
    Route::get('/kurikulumFilter', [FilterController::class, 'getKurilumFilter'])->middleware(['auth.api']);
    Route::get('/cplFilter/{id}', [FilterController::class, 'getCplFilter'])->middleware(['auth.api']);
    Route::get('/cpmkFilter/{id}', [FilterController::class, 'getCpmkFilter'])->middleware(['auth.api']);
    Route::get('/cpmkFilterAll', [FilterController::class, 'getCpmkAll'])->middleware(['auth.api']);

    /**
     * Route khusus authentifikasi
    */
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile'])->middleware(['auth.api']);
        Route::get('/csrf', [AuthController::class, 'csrf'])->middleware(['web']);
    });
});

Route::get('/', function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});

/**
 * Jika Frontend meminta request endpoint API yang tidak terdaftar
 * maka akan menampilkan HTTP 404
 */
Route::fallback(function () {
    return response()->failed(['Endpoint yang anda minta tidak tersedia']);
});