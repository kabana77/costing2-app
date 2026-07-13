<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

use App\Http\Controllers\CostingController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\MasterMesinController;
use App\Http\Controllers\MasterProdukController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('costing.history');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------
    // HALAMAN BLADE
    // ------------------------------------------------------------------
    Route::get('/costing/input',   [PageController::class, 'costingInput'])->name('costing.input');
    Route::get('/costing/history', [PageController::class, 'costingHistory'])->name('costing.history');

    // Halaman master — sekarang full Blade view, bukan placeholder
    Route::get('/master/mesin',  fn() => view('master.mesin.index'))->name('master.mesin.index');
    Route::get('/master/produk', fn() => view('master.produk.index'))->name('master.produk.index');

    // ------------------------------------------------------------------
    // EXPORT
    // ------------------------------------------------------------------
    Route::get('/costing/export', [ExportController::class, 'costing'])->name('costing.export');

    // ------------------------------------------------------------------
    // ENDPOINT JSON — web session auth
    // ------------------------------------------------------------------
    Route::prefix('api')->group(function () {

        // Master (read-only, dipakai dropdown form input & filter riwayat)
        Route::get('master/mesin',         [CostingController::class, 'getMesin']);
        Route::get('master/mesin/{mesin}', [CostingController::class, 'getMesinById']);
        Route::get('master/produk',        [CostingController::class, 'getProduk']);

        // Costing
        Route::post('costing/calculate',   [CostingController::class, 'calculate']);
        Route::get('costing/history',      [CostingController::class, 'history']);

        // ------------------------------------------------------------
        // MASTER CRUD — Tahap 9
        // ------------------------------------------------------------
        Route::prefix('master-crud')->group(function () {

            // Mesin — pakai route model binding standar (PK: id)
            Route::get('mesin',           [MasterMesinController::class, 'index']);
            Route::post('mesin',          [MasterMesinController::class, 'store']);
            Route::put('mesin/{mesin}',   [MasterMesinController::class, 'update']);
            Route::delete('mesin/{mesin}',[MasterMesinController::class, 'destroy']);

            // Produk — PK custom (kode_produksi), pakai string biasa di route
            Route::get('produk',                  [MasterProdukController::class, 'index']);
            Route::post('produk',                 [MasterProdukController::class, 'store']);
            Route::put('produk/{kodeProduksi}',   [MasterProdukController::class, 'update']);
            Route::delete('produk/{kodeProduksi}',[MasterProdukController::class, 'destroy']);

        });

    });

});