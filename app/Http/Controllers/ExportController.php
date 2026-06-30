<?php

namespace App\Http\Controllers;

use App\Models\MstMesin;
use App\Models\TrxProduksiHeader;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ExportController extends Controller
{
    public function __construct(
        private readonly ExportService $exportService
    ) {}

    /**
     * GET /costing/export
     * Filter sama persis dengan halaman riwayat — agar export selalu konsisten
     * dengan apa yang sedang dilihat user di tabel.
     */
    public function costing(Request $request)
    {
        $request->validate([
            'tanggal_start' => ['nullable', 'date', 'date_format:Y-m-d'],
            'tanggal_end'   => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:tanggal_start'],
            'kode_produksi' => ['nullable', 'string', 'exists:mst_produk,kode_produksi'],
            'id_mesin'      => ['nullable', 'integer', 'exists:mst_mesin,id'],
        ]);

        $query = TrxProduksiHeader::with(['mesin', 'kalkulasiResult'])
            ->when($request->tanggal_start, fn($q) =>
                $q->whereDate('tanggal', '>=', $request->tanggal_start)
            )
            ->when($request->tanggal_end, fn($q) =>
                $q->whereDate('tanggal', '<=', $request->tanggal_end)
            )
            ->when($request->kode_produksi, fn($q) =>
                $q->where('kode_produksi', $request->kode_produksi)
            )
            ->when($request->id_mesin, fn($q) =>
                $q->where('id_mesin', $request->id_mesin)
            )
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc');

        $rows = $query->get();

        // Tidak ada limit di export — beda dengan halaman riwayat yang pakai pagination
        if ($rows->isEmpty()) {
            return Redirect::back()->with('error', 'Tidak ada data untuk filter yang dipilih.');
        }

        // Info filter untuk ditulis di header laporan (baris 2)
        $filterInfo = [
            'tanggal_start' => $request->tanggal_start,
            'tanggal_end'   => $request->tanggal_end,
            'kode_produksi' => $request->kode_produksi,
            'mesin_nama'    => $request->id_mesin
                ? MstMesin::find($request->id_mesin)?->nama_mesin
                : null,
        ];

        $spreadsheet = $this->exportService->generateCostingExport($rows, $filterInfo);

        $filename = $this->buildFilename($request);

        return $this->exportService->streamDownload($spreadsheet, $filename);
    }

    /**
     * Bangun nama file berdasarkan filter tanggal yang aktif.
     * Contoh: Riwayat_Costing_2026-06-01_to_2026-06-30.xlsx
     */
    private function buildFilename(Request $request): string
    {
        $start = $request->tanggal_start ?? 'semua';
        $end   = $request->tanggal_end   ?? 'sekarang';

        return "Riwayat_Costing_{$start}_to_{$end}.xlsx";
    }
}