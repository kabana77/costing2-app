<?php

namespace App\Http\Controllers;

use App\Models\MstProduk;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * GET /costing/input
     * Render halaman form input produksi.
     * Produk list di-pass ke Blade untuk dropdown (server-side).
     * Mesin list di-load via fetch API (client-side).
     */
    public function costingInput(): View
    {
        $produkList = MstProduk::orderBy('kode_produksi')->get(['kode_produksi', 'konstruksi']);

        return view('costing.input', compact('produkList'));
    }

    /**
     * GET /costing/history
     * Render halaman riwayat — data di-load via fetch API dari JS.
     */
    public function costingHistory(): View
    {
        return view('costing.history');
    }
}
