<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CpmkController;
use App\Http\Controllers\Api\SubCpmkController;
use App\Http\Controllers\Api\ApiStikiController;
use App\Http\Controllers\Api\KurikulumController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\Api\ValidasiMkController;
use App\Http\Controllers\Api\EvaluasiCplController;
use App\Http\Controllers\Api\PenilaianMkController;
use App\Http\Controllers\Api\RekapNilaiMahasiswaController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\ValidasiKurikulumController;
use App\Http\Controllers\UserController;

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
    
    // Sub-CPMK
    Route::get('/subCpmk', [SubCpmkController::class, 'index'])->middleware(['auth.api']);
    Route::get('/subCpmk/mk', [SubCpmkController::class, 'getMkSubCpmk'])->middleware(['auth.api']);
    Route::get('/subCpmk/{id}', [SubCpmkController::class, 'show'])->middleware(['auth.api']);
    Route::post('/subCpmk', [SubCpmkController::class, 'store'])->middleware(['auth.api']);
    Route::put('/subCpmk', [SubCpmkController::class, 'update'])->middleware(['auth.api']);
    Route::post('/subCpmk/status', [SubCpmkController::class, 'updateStatus'])->middleware(['auth.api']);
    Route::delete('/subCpmk/{id}', [SubCpmkController::class, 'destroy'])->middleware(['auth.api']);
    Route::post('/subCpmk/submit', [SubCpmkController::class, 'submit'])->middleware(['auth.api']);
    Route::post('/subCpmk/penilaian/diterima', [SubCpmkController::class, 'diterima'])->middleware(['auth.api']);
    Route::post('/subCpmk/penilaian/ditolak', [SubCpmkController::class, 'ditolak'])->middleware(['auth.api']);

    //validasi kurikulum
    Route::put('/validasiKurikulum/diterima', [ValidasiKurikulumController::class, 'diterima'])->middleware(['auth.api']);
    Route::put('/validasiKurikulum/ditolak', [ValidasiKurikulumController::class, 'ditolak'])->middleware(['auth.api']);


    //validasi mk
    Route::put('/validasiMk/diterima', [ValidasiMkController::class, 'diterima'])->middleware(['auth.api']);
    Route::put('/validasiMk/ditolak', [ValidasiMkController::class, 'ditolak'])->middleware(['auth.api']);
    Route::put('/validasiMk/diterimaDetail', [ValidasiMkController::class, 'diterimaDetail'])->middleware(['auth.api']);
    Route::put('/validasiMk/ditolakDetail', [ValidasiMkController::class, 'ditolakDetail'])->middleware(['auth.api']);

    //penilaian mk
    Route::get('/penilaianMk', [PenilaianMkController::class, 'index'])->middleware(['auth.api']);
    Route::post('/penilaianMk', [PenilaianMkController::class, 'store'])->middleware(['auth.api']);
    Route::post('/penilaianMk/detail', [PenilaianMkController::class, 'penilaianDetail'])->middleware(['auth.api']);

    //evaluasi cpl
    Route::get('/evaluasiCpl', [EvaluasiCplController::class, 'index'])->middleware(['auth.api']);
    Route::get('/evaluasiCpl/rekap', [EvaluasiCplController::class, 'rekap'])->middleware(['auth.api']);
    Route::get('/getCplMahasiswa', [EvaluasiCplController::class, 'getCplMahasiswa'])->middleware(['auth.api']);


    // rekap nilai
    Route::get('/rekapNilai', [RekapNilaiMahasiswaController::class, 'index'])->middleware(['auth.api']);
    Route::get('/rekapNilai/getMatkulRekap', [RekapNilaiMahasiswaController::class, 'getMatkulRekap'])->middleware(['auth.api']);
    Route::get('/rekapNilai/rekap', [RekapNilaiMahasiswaController::class, 'rekap'])->middleware(['auth.api']);
    Route::get('/rekapNilai/mahasiswa', [RekapNilaiMahasiswaController::class, 'rekapMahasiswa'])->middleware(['auth.api']);


    // api stiki sudah tidak dibutuhkan (dibutuhkan jika mau sambung ke siakad)
    Route::get('/getMatkul', [ApiStikiController::class, 'getMatkul'])->middleware(['auth.api']);
    Route::get('/getListMahasiswa', [ApiStikiController::class, 'getListMahasiswa'])->middleware(['auth.api']);
    Route::get('/getAllMahasiswa', [ApiStikiController::class, 'getAllMahasiswa'])->middleware(['auth.api']);

    //tabel dummy
    Route::get('/getMahasiswaDummy', [ApiStikiController::class, 'getMahasiswaDummy'])->middleware(['auth.api']);


    //filter
    Route::get('/kurikulumFilter', [FilterController::class, 'getKurilumFilter'])->middleware(['auth.api']);
    Route::get('/kurikulumFilterAll', [FilterController::class, 'getKurilumFilterAll'])->middleware(['auth.api']);
    Route::get('/cplFilter/{id}', [FilterController::class, 'getCplFilter'])->middleware(['auth.api']);
    Route::get('/cpmkFilter/{id}', [FilterController::class, 'getCpmkFilter'])->middleware(['auth.api']);
    Route::get('/cpmkFilterAll', [FilterController::class, 'getCpmkAll'])->middleware(['auth.api']);
    Route::get('/detailMk', [FilterController::class, 'getDetailMk'])->middleware(['auth.api']);
    Route::get('/mkById', [FilterController::class, 'getMkById'])->middleware(['auth.api']);
    Route::get('/subCpmkAll', [FilterController::class, 'getSubCpmkAll'])->middleware(['auth.api']);
    Route::get('/subCpmkById', [FilterController::class, 'getSubCpmkById'])->middleware(['auth.api']);
    Route::get('/penilaianAll', [FilterController::class, 'getPenilaianAll'])->middleware(['auth.api']);
    Route::get('/penilaianCpmk', [FilterController::class, 'getPenilaianCpmk'])->middleware(['auth.api']);
    Route::get('/CplByKurikulum', [FilterController::class, 'getCplByKurikulum'])->middleware(['auth.api']);
    Route::get('/getRoles', [FilterController::class, 'getRoles'])->middleware(['auth.api']);


    // users
    Route::get('/users', [UserController::class, 'getUsers'])->middleware(['auth.api']);
    Route::post('/users', [UserController::class, 'store'])->middleware(['auth.api']);
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware(['auth.api']);
    Route::put('/users', [UserController::class, 'update'])->middleware(['auth.api']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware(['auth.api']);

    // roles
    Route::get('/roles', [UserController::class, 'getRolesAll'])->middleware(['auth.api']);
    Route::post('/roles', [UserController::class, 'storeRoles'])->middleware(['auth.api']);
    Route::get('/roles/{id}', [UserController::class, 'showRoles'])->middleware(['auth.api']);
    Route::put('/roles', [UserController::class, 'updateRoles'])->middleware(['auth.api']);
    Route::delete('/roles/{id}', [UserController::class, 'destroyRoles'])->middleware(['auth.api']);
    

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
