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
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Production Costing System
|--------------------------------------------------------------------------
| Semua endpoint JSON dipindah ke sini (web.php) agar pakai middleware
| 'auth' session Breeze — tidak perlu Sanctum stateful sama sekali.
|
| Prefix tetap /api/... agar JS fetch tidak perlu diubah.
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('costing.input');
});

// Route auth bawaan Breeze — jangan dihapus
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------
    // HALAMAN BLADE
    // ------------------------------------------------------------------
    Route::get('/costing/input',   [PageController::class, 'costingInput'])->name('costing.input');
    Route::get('/costing/history', [PageController::class, 'costingHistory'])->name('costing.history');

    // Placeholder master — dikerjakan Tahap 9
    Route::get('/master/mesin',  fn() => view('master.mesin.index'))->name('master.mesin.index');
    Route::get('/master/produk', fn() => view('master.produk.index'))->name('master.produk.index');

    // ------------------------------------------------------------------
    // ENDPOINT JSON — prefix /api, pakai session auth (bukan Sanctum)
    // ------------------------------------------------------------------
    Route::prefix('api')->group(function () {

        // Master
        Route::get('master/mesin',         [CostingController::class, 'getMesin']);
        Route::get('master/mesin/{mesin}', [CostingController::class, 'getMesinById']);

        // Costing
        Route::post('costing/calculate',   [CostingController::class, 'calculate']);
        Route::get('costing/history',      [CostingController::class, 'history']);

    });

});