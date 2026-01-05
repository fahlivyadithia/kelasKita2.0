<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\SubMateriController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\DokumenController;
use App\Http\Controllers\Api\MentorDashboardController;
use App\Http\Controllers\Api\Murid\ApiReviewController;
use App\Http\Controllers\Api\Murid\ApiProgressController;
use App\Http\Controllers\Api\Murid\ApiReportController;

// === PUBLIC ROUTES ===
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// === PROTECTED ROUTES (pakai custom middleware) ===
Route::middleware('custom.auth')->group(function () {
    
    // Auth endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/become-mentor', [AuthController::class, 'becomeMentor']);
    
    // Kelas endpoints
    Route::get('/kelas', [KelasController::class, 'index']);
    Route::post('/kelas', [KelasController::class, 'store']);
    Route::get('/kelas/{id_kelas}', [KelasController::class, 'show']);
    Route::put('/kelas/{id_kelas}', [KelasController::class, 'update']);
    Route::delete('/kelas/{id_kelas}', [KelasController::class, 'destroy']);

    Route::get('/materi', [MateriController::class, 'index']); // ?id_kelas=...
    Route::post('/materi', [MateriController::class, 'store']);
    Route::put('/materi/{id_materi}', [MateriController::class, 'update']);
    Route::delete('/materi/{id_materi}', [MateriController::class, 'destroy']);

    // === SUB MATERI (KONTEN) ===
    Route::get('/sub-materi', [SubMateriController::class, 'index']); // ?id_materi=...
    Route::post('/sub-materi', [SubMateriController::class, 'store']);
    Route::put('/sub-materi/{id_sub_materi}', [SubMateriController::class, 'update']);
    Route::delete('/sub-materi/{id_sub_materi}', [SubMateriController::class, 'destroy']);

    Route::post('/dokumen', [DokumenController::class, 'store']);
    Route::delete('/dokumen/{id_dokumen}', [DokumenController::class, 'destroy']);

    Route::post('/video', [VideoController::class, 'store']);
    Route::delete('/video/{id_video}', [VideoController::class, 'destroy']);

    Route::get('/mentor/dashboard', [MentorDashboardController::class, 'getDashboardSummary']);
    
    // 2. View Detail Keuangan
    Route::get('/mentor/pendapatan', [MentorDashboardController::class, 'getPendapatan']);
    
    // 3. View Detail Review
    Route::get('/mentor/reviews', [MentorDashboardController::class, 'getReviews']);
});



/*
|--------------------------------------------------------------------------
| User Global Info
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Student Features (Progress, Review, Report)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Progress
    Route::get('/progress', [ApiProgressController::class, 'index']);
    Route::get('/progress/{id_kelas}', [ApiProgressController::class, 'show']); 
    Route::post('/progress/toggle', [ApiProgressController::class, 'toggle']);

    // Reviews
    Route::get('/reviews/{id_kelas}', [ApiReviewController::class, 'index']);
    Route::post('/reviews/{id_kelas}', [ApiReviewController::class, 'store']);
    Route::put('/reviews/{id_review}', [ApiReviewController::class, 'update']);
    Route::delete('/reviews/{id_review}', [ApiReviewController::class, 'destroy']);

    // Reports
    Route::post('/reports/{id_kelas}', [ApiReportController::class, 'store']);
});


Route::prefix('admin')->group(function () {
    // Public routes
    Route::post('/login', [\App\Http\Controllers\Api\Admin\AuthController::class, 'login']);

    // Protected routes
    Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Admin\AuthController::class, 'logout']);
        Route::get('/me', [\App\Http\Controllers\Api\Admin\AuthController::class, 'me']);

        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\DashboardController::class, 'index']);

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\Api\Admin\UserController::class, 'store']);
            Route::get('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'show']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\Admin\UserController::class, 'destroy']);
            Route::patch('/{id}/status', [\App\Http\Controllers\Api\Admin\UserController::class, 'updateStatus']);
            Route::patch('/{id}/catatan', [\App\Http\Controllers\Api\Admin\UserController::class, 'updateCatatan']);
            Route::patch('/{id}/activate', [\App\Http\Controllers\Api\Admin\UserController::class, 'activate']);
        });
    });
});