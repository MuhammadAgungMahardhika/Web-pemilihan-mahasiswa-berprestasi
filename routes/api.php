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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth'])->group(function () {
    Route::get('dokumen-prestasi/data', [DokumenPrestasiController::class, 'getDokumenPrestasiData'])->name('dokumen-prestasi.data');
    Route::get('dokumen-prestasi/admin-departmen/data', [DokumenPrestasiController::class, 'getDokumenPrestasiDataByAdminDepartmen'])->name('dokumen-prestasi.admin-departmen.data');
    Route::get('capaian-unggulan/data', [CapaianUnggulanController::class, 'getCapaianUnggulanData'])->name('capaian-unggulan.data');
    Route::get('bidang/data', [BidangController::class, 'getBidangData'])->name('bidang.data');
    Route::get('kategori/data', [KategoriController::class, 'getKategoriData'])->name('kategori.data');
    Route::get('mahasiswa/data', [MahasiswaController::class, 'getMahasiswaData'])->name('mahasiswa.data');
    Route::get('departmen/data', [DepartmenController::class, 'getDepartmenData'])->name('departmen.data');
    Route::get('fakultas/data', [FakultasController::class, 'getFakultasData'])->name('fakultas.data');
    Route::get('user/data', [UserController::class, 'getUserData'])->name('user.data');


    Route::post('user/activate', [UserController::class, 'activateUser']);
    Route::post('user/deactivate', [UserController::class, 'deactivateUser']);

    Route::apiResource('dokumen-prestasi', DokumenPrestasiController::class);
    Route::apiResource('capaian-unggulan', CapaianUnggulanController::class);
    Route::apiResource('bidang', BidangController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::apiResource('departmen', DepartmenController::class);
    Route::apiResource('fakultas', FakultasController::class);
    Route::apiResource('user', UserController::class);

    // costume
    Route::patch('dokumen-prestasi/status/{id}', [DokumenPrestasiController::class, 'changeStatus']);
});
