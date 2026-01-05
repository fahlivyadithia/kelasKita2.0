<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\Mentor\MentorDashboardController; // Pastikan path ini sesuai
use App\Http\Controllers\Api\Mentor\KelasController;
use App\Http\Controllers\Api\Mentor\MateriController;
use App\Http\Controllers\Api\Mentor\SubMateriController;

use App\Http\Controllers\ProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk menampilkan view ini
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Halaman Register & Prosesnya
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- RUTE PROGRESS, REVIEW, & REPORT (GABUNGAN) ---
Route::middleware(['auth'])->group(function () {
    
    // 1. FITUR PROGRESS (Halaman Belajar & Checklist)
    Route::get('/kelas/{id_kelas}/belajar', [ProgressController::class, 'show'])->name('kelas.belajar');
    Route::post('/progress/toggle', [ProgressController::class, 'toggle'])->name('progress.toggle');
    Route::get('/learning', [ProgressController::class, 'index'])->name('learning.index');

    // 2. FITUR REVIEW (CRUD: Create, Update, Delete)
    Route::post('/review/{id_kelas}', [ReviewController::class, 'store'])->name('review.store');
    Route::put('/review/{id_review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id_review}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // 3. FITUR REPORT (Pelaporan Masalah)
    Route::post('/report/store/{id_kelas}', [ReportController::class, 'store'])->name('report.store');

});

// --- RUTE MENTOR ---
Route::middleware(['auth'])->prefix('mentor')->name('mentor.')->group(function () {

    // 1. DASHBOARD
    Route::get('/dashboard', [MentorDashboardController::class, 'indexWeb'])->name('dashboard');

    // 2. MANAJEMEN KELAS (CRUD LENGKAP)
    Route::get('/kelas', [KelasController::class, 'indexWeb'])->name('kelas.index'); // List
    Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create'); // Form
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store'); // Simpan
    Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit'); // Edit
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update'); // Update
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy'); // Hapus

    // 3. MANAJEMEN MATERI (BAB) & SUB-MATERI
    // URL: mentor/kelas/{id_kelas}/materi/...
    Route::prefix('kelas/{id_kelas}/materi')->name('materi.')->group(function () {
        
        // --- BAGIAN BAB (Materi) ---
        Route::get('/', [MateriController::class, 'indexWeb'])->name('index'); // Halaman Kurikulum
        Route::post('/', [MateriController::class, 'store'])->name('store'); 
        
        Route::get('/{id_materi}/edit', [MateriController::class, 'edit'])->name('edit');
        Route::put('/{id_materi}', [MateriController::class, 'update'])->name('update');
        Route::delete('/{id_materi}', [MateriController::class, 'destroy'])->name('destroy');

        // --- BAGIAN SUB-MATERI (Video/Dokumen) ---
        Route::prefix('{id_materi}/sub')->name('sub.')->group(function () {
             Route::get('/create', [SubMateriController::class, 'create'])->name('create');
             Route::post('/', [SubMateriController::class, 'store'])->name('store');
             Route::get('/{id_sub_materi}/edit', [SubMateriController::class, 'edit'])->name('edit');
             Route::put('/{id_sub_materi}', [SubMateriController::class, 'update'])->name('update');
             Route::delete('/{id_sub_materi}', [SubMateriController::class, 'destroy'])->name('destroy');
        });
    });

});