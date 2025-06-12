<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\NilaiSiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama (landing page)
Route::get('/', function () {
    return view('welcome');
});

// Login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Register
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.submit');

// Setelah login, masuk ke homepage
Route::get('/home', function () {
    return view('siswa.home'); // pastikan file-nya ada
})->middleware(['auth'])->name('home');

// Hanya untuk yang sudah login
Route::middleware(['auth'])->group(function () {
    // CRUD Nilai Siswa
    Route::resource('nilai', NilaiSiswaController::class);

    // Upload File
    Route::get('upload', [FileUploadController::class, 'showUploadForm'])->name('upload.form');
    Route::post('upload', [FileUploadController::class, 'uploadFile'])->name('upload.submit');
});

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
