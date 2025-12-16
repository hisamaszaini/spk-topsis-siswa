<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\HitungController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HasilController;


// Rute Publik (Bisa diakses siapa saja)
// Rute Publik
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// GROUP: Harus Login dulu
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard redirection logic -> handled by DashboardController@index
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // GROUP: KHUSUS ADMIN
    Route::middleware(['role:admin'])->group(function () {
        // CRUD Data Master
        Route::resource('kriteria', KriteriaController::class);
        Route::resource('sub-kriteria', SubKriteriaController::class);
        Route::resource('alternatif', AlternatifController::class);
        Route::resource('users', UserController::class);
    });

    // GROUP: USER BIASA & ADMIN
    Route::middleware(['role:admin,user'])->group(function () {
        // Fitur Penilaian & Hasil
        Route::resource('penilaian', PenilaianController::class);
        Route::get('/perhitungan', [HitungController::class, 'index']);
        Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');

        // Edit Profile Sendiri
        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::put('/profile', [ProfileController::class, 'update']);
    });
});
