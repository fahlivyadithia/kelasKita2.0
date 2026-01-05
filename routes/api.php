    <?php

        use Illuminate\Http\Request;
        use App\Http\Controllers\Api\Home\HomeApiController;
        use App\Http\Controllers\Api\Detailkelas\KelasApiController;
        use App\Http\Controllers\Api\Keranjang\KeranjangApiController;
        use App\Http\Controllers\Api\Transaksi\TransaksiApiController;
        use Illuminate\Support\Facades\Route;

        // Home API
        Route::prefix('home')->group(function () {  
        Route::get('/kelas', [HomeApiController::class, 'getKelas']);
        Route::get('/mentors', [HomeApiController::class, 'getMentors']);
        Route::get('/reviews', [HomeApiController::class, 'getReviews']);
        });
        // Kelas API
        Route::prefix('kelas')->group(function () {
            Route::get('/{id}', [KelasApiController::class, 'show']);
            Route::get('/{id}/materi', [KelasApiController::class, 'getMateri']);
        });

        // Keranjang API
        Route::prefix('keranjang')->group(function () {
            Route::get('/', [KeranjangApiController::class, 'index']);
            Route::post('/tambah', [KeranjangApiController::class, 'store']);
            Route::delete('/{id}', [KeranjangApiController::class, 'destroy']);
        });

        // Transaksi API
        Route::prefix('transaksi')->group(function () {
            Route::get('/', [TransaksiApiController::class, 'index']);
            Route::post('/checkout', [TransaksiApiController::class, 'store']);
            Route::get('/{id}', [TransaksiApiController::class, 'show']);
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

Route::get('/user', function (Request $request) {
            return $request->user();
        })->middleware('auth:sanctum');