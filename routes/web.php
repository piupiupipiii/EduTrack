<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\NilaiSiswaController;
use App\Http\Controllers\MateriController;

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

Route::get('/nilai', [NilaiSiswaController::class, 'index'])->name('nilai.index');
Route::post('/nilai', [NilaiSiswaController::class, 'store'])->name('nilai.store');
Route::put('/nilai/{id}', [NilaiSiswaController::class, 'update'])->name('nilai.update');
Route::delete('/nilai/{id}', [NilaiSiswaController::class, 'destroy'])->name('nilai.destroy');

Route::get('/materi', [MateriController::class, 'index'])->name('materi.index');
Route::post('/materi/upload', [MateriController::class, 'upload'])->name('materi.upload');

// Logout
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
