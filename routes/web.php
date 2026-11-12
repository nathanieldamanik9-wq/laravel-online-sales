<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ---------- Autentikasi ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Halaman SEMENTARA M1 (diganti pada M4.2 dan M7.4) ----------
Route::get('/', fn () => view('placeholder', ['title' => 'Katalog (sementara)']))->name('shop.index');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', fn () => view('placeholder', ['title' => 'Dashboard Admin (sementara)']))->name('dashboard');
});

