<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\TemporaryFileController;
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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth/login');
    });
    Route::get('/login', function () {
        return view('auth/login');
    });
    Route::get('/forgot-password', function () {
        return view('auth/forgot-password');
    });
});

Route::middleware(['auth'])->group(function () {

    // Upload Temporary Files
    Route::post('/temp-upload', [TemporaryFileController::class, 'upload']);
    Route::delete('/temp-delete', [TemporaryFileController::class, 'delete']);

    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    });
    Route::get('/dokumen-prestasi', [PageController::class, 'dokumenPrestasi']);
    Route::get('/capaian-unggulan', [PageController::class, 'capaianUnggulan']);
    Route::get('/bidang', [PageController::class, 'bidang']);
    Route::get('/kategori', [PageController::class, 'kategori']);

    Route::get('/mahasiswa', [PageController::class, 'mahasiswa']);
    Route::get('/departmen', [PageController::class, 'departmen']);
    Route::get('/fakultas', [PageController::class, 'fakultas']);


    Route::get('/verifikasi-dokumen', [PageController::class, 'verifikasiDokumen']);
    Route::get('/admin-fakultas', [PageController::class, 'adminFakultas']);

    Route::get('/utusan-departmen', [PageController::class, 'utusanDepartmen']);
});
