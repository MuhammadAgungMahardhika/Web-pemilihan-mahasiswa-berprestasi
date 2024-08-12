<?php

use App\Http\Controllers\BidangController;
use App\Http\Controllers\CapaianUnggulanController;
use App\Http\Controllers\DepartmenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DokumenPrestasiController;
use App\Http\Controllers\FakultasController;
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
    Route::get('dokumen-prestasi/data', [DokumenPrestasiController::class, 'getDokumenPrestasiData']);
    Route::get('dokumen-prestasi/departmen/{id}', [DokumenPrestasiController::class, 'getDokumenPrestasiDataByDepartmen']);
    Route::get('capaian-unggulan/data', [CapaianUnggulanController::class, 'getCapaianUnggulanData']);
    Route::get('bidang/data', [BidangController::class, 'getBidangData']);
    Route::get('kategori/data', [KategoriController::class, 'getKategoriData']);
    Route::get('mahasiswa/data', [MahasiswaController::class, 'getMahasiswaData']);
    Route::get('mahasiswa/departmen/{id}', [MahasiswaController::class, 'getMahasiswaDataByDepartmen']);
    Route::get('mahasiswa/ranking/departmen/{id}', [MahasiswaController::class, 'getMahasiswaRankingDataByDepartmen']);
    Route::get('fakultas/data', [FakultasController::class, 'getFakultasData']);
    Route::get('departmen/data', [DepartmenController::class, 'getDepartmenData']);
    Route::get('user/data', [UserController::class, 'getUserData']);
    Route::get('user/fakultas/data', [UserController::class, 'getUserDataByFakultas']);
    Route::get('user/departmen/data', [UserController::class, 'getUserDataByDepartmen']);

    Route::get('utusan/universitas/data', [UtusanController::class, 'getUtusanDataByUniversitas']);
    Route::get('utusan/fakultas/{id}', [UtusanController::class, 'getUtusanDataByFakultas']);
    Route::get('utusan/departmen/{id}', [UtusanController::class, 'getUtusanDataByDepartmen']);

    // API CRUD
    Route::apiResource('dokumen-prestasi', DokumenPrestasiController::class);
    Route::apiResource('capaian-unggulan', CapaianUnggulanController::class);
    Route::apiResource('bidang', BidangController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::apiResource('departmen', DepartmenController::class);
    Route::apiResource('fakultas', FakultasController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('utusan', UtusanController::class);
    Route::apiResource('portal', PortalController::class);

    // costume
    Route::patch('dokumen-prestasi/status/{id}', [DokumenPrestasiController::class, 'changeStatus']);
    Route::get('departmen/fakultas/{id}', [DepartmenController::class, 'getDepartmenDataByFakultas']);
    Route::post('user/activate', [UserController::class, 'activateUser']);
    Route::post('user/deactivate', [UserController::class, 'deactivateUser']);
});
