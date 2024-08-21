<?php

use App\Http\Controllers\BahasaInggrisController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\CapaianUnggulanController;
use App\Http\Controllers\DepartmenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenPrestasiController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KaryaIlmiahController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UtusanController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth'])->group(function () {
    // route untuk datatable
    Route::get('portal/data', [PortalController::class, 'getPortalData']);
    Route::apiResource('portal', PortalController::class);

    Route::get('capaian-unggulan/data', [CapaianUnggulanController::class, 'getCapaianUnggulanData']);
    Route::apiResource('capaian-unggulan', CapaianUnggulanController::class);

    Route::get('bidang/data', [BidangController::class, 'getBidangData']);
    Route::apiResource('bidang', BidangController::class);

    Route::get('kategori/data', [KategoriController::class, 'getKategoriData']);
    Route::apiResource('kategori', KategoriController::class);

    Route::get('mahasiswa/data', [MahasiswaController::class, 'getMahasiswaData']);
    Route::get('mahasiswa/departmen/{id}', [MahasiswaController::class, 'getMahasiswaDataByDepartmen']);
    Route::get('mahasiswa/ranking/departmen/{id}', [MahasiswaController::class, 'getMahasiswaRankingDataByDepartmen']);
    Route::get('mahasiswa/ranking/fakultas/{id}', [MahasiswaController::class, 'getMahasiswaRankingDataByFakultas']);
    Route::get('mahasiswa/ranking/universitas', [MahasiswaController::class, 'getMahasiswaRankingDataByUniversitas']);
    Route::apiResource('mahasiswa', MahasiswaController::class);

    Route::get('fakultas/data', [FakultasController::class, 'getFakultasData']);
    Route::apiResource('fakultas', FakultasController::class);

    Route::get('departmen/data', [DepartmenController::class, 'getDepartmenData']);
    Route::get('departmen/fakultas/{id}', [DepartmenController::class, 'getDepartmenDataByFakultas']);
    Route::apiResource('departmen', DepartmenController::class);

    Route::get('user/data', [UserController::class, 'getUserData']);
    Route::get('user/fakultas/data', [UserController::class, 'getUserDataByFakultas']);
    Route::get('user/departmen/data', [UserController::class, 'getUserDataByDepartmen']);
    Route::get('user/juri-fakultas/data', [UserController::class, 'getUserDataByJuriFakultas']);
    Route::get('user/juri-universitas/data', [UserController::class, 'getUserDataByJuriUniversitas']);
    Route::post('user/activate', [UserController::class, 'activateUser']);
    Route::post('user/deactivate', [UserController::class, 'deactivateUser']);
    Route::apiResource('user', UserController::class);


    Route::middleware('portal')->group(function () {
        Route::get('dokumen-prestasi/data', [DokumenPrestasiController::class, 'getDokumenPrestasiData']);
        Route::get('dokumen-prestasi/mahasiswa/{id}', [DokumenPrestasiController::class, 'getDokumenPrestasiDataByMahasiswa']);
        Route::patch('dokumen-prestasi/status/{id}', [DokumenPrestasiController::class, 'changeStatus']);
        Route::apiResource('dokumen-prestasi', DokumenPrestasiController::class);

        Route::get('karya-ilmiah/data', [KaryaIlmiahController::class, 'getKaryaIlmiahData']);
        Route::get('karya-ilmiah/fakultas/{id}', [KaryaIlmiahController::class, 'getKaryaIlmiahDataByFakultas']);
        Route::get('karya-ilmiah/universitas', [KaryaIlmiahController::class, 'getKaryaIlmiahDataByUniversitas']);
        Route::patch('karya-ilmiah/review-fakultas/{id}', [KaryaIlmiahController::class, 'reviewKaryaIlmiahTingkatFakultas']);
        Route::patch('karya-ilmiah/review-universitas/{id}', [KaryaIlmiahController::class, 'reviewKaryaIlmiahTingkatUniversitas']);
        Route::apiResource('karya-ilmiah', KaryaIlmiahController::class);

        Route::get('bahasa-inggris/data', [BahasaInggrisController::class, 'getBahasaInggrisData']);
        Route::get('bahasa-inggris/fakultas/data', [BahasaInggrisController::class, 'getBahasaInggrisDataByFakultas']);
        Route::apiResource('bahasa-inggris', BahasaInggrisController::class);

        Route::get('utusan/data', [UtusanController::class, 'getUtusanData']);
        Route::get('utusan/universitas/data', [UtusanController::class, 'getUtusanDataByUniversitas']);
        Route::get('utusan/fakultas/{id}', [UtusanController::class, 'getUtusanDataByFakultas']);
        Route::get('utusan/departmen/{id}', [UtusanController::class, 'getUtusanDataByDepartmen']);
        Route::patch('utusan/tingkat/{id}', [UtusanController::class, 'updateTingkat']);
        Route::apiResource('utusan', UtusanController::class);
    });
});
