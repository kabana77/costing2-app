<?php

use App\Http\Controllers\CostingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Production Costing System
|--------------------------------------------------------------------------
| Semua route di sini otomatis diawali prefix /api
| dan dijaga middleware 'auth:sanctum' (Breeze sudah pasang Sanctum).
|
| Akses: http://localhost:8003/api/...
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // --- Master Data ---
    Route::prefix('master')->group(function () {
        Route::get('mesin',      [CostingController::class, 'getMesin']);      // GET /api/master/mesin
        Route::get('mesin/{mesin}', [CostingController::class, 'getMesinById']); // GET /api/master/mesin/{id}
    });

    // --- Costing Transaksi ---
    Route::prefix('costing')->group(function () {
        Route::post('calculate', [CostingController::class, 'calculate']); // POST /api/costing/calculate
        Route::get('history',    [CostingController::class, 'history']);   // GET  /api/costing/history
    });
});
