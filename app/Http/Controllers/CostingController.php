<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostingRequest;
use App\Models\MstMesin;
use App\Models\TrxProduksiHeader;
use App\Services\CostingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CostingController extends Controller
{
    public function __construct(
        private readonly CostingService $costingService
    ) {}

    /**
     * POST /api/costing/calculate
     * Menerima input produksi, kalkulasi HPP, simpan ke DB.
     */
    public function calculate(StoreCostingRequest $request): JsonResponse
    {
        try {
            $hasil = $this->costingService->calculate($request->validated());

            return response()->json([
                'status' => 'success',
                'data'   => $hasil,
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kalkulasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/costing/history
     * Riwayat produksi dengan filter tanggal & kode produksi.
     *
     * Query params:
     *   tanggal_start  (Y-m-d, opsional)
     *   tanggal_end    (Y-m-d, opsional)
     *   kode_produksi  (string, opsional)
     *   per_page       (integer, default 20)
     */
    public function history(Request $request): JsonResponse
    {
        $request->validate([
            'tanggal_start' => ['nullable', 'date', 'date_format:Y-m-d'],
            'tanggal_end'   => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:tanggal_start'],
            'kode_produksi' => ['nullable', 'string', 'exists:mst_produk,kode_produksi'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:200'],
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
            ->orderByDesc('tanggal')
            ->orderByDesc('id');

        $data = $query->paginate($request->input('per_page', 20));

        // Bentuk ulang response agar flat (tidak nested relasi)
        $rows = $data->getCollection()->map(function (TrxProduksiHeader $row) {
            return [
                'id_transaksi'    => $row->id,
                'tanggal'         => $row->tanggal->format('Y-m-d'),
                'kode_produksi'   => $row->kode_produksi,
                'mesin'           => $row->mesin?->nama_mesin,
                'kode_mesin'      => $row->mesin?->kode_mesin,
                'slah_sisir'      => $row->slah_sisir,
                'pick'            => $row->pick,
                'panjang_pcs'     => $row->panjang_pcs,
                'rpm'             => $row->rpm_aktual,
                'efisiensi'       => $row->efisiensi,          // desimal 0–1
                'efisiensi_pct'   => round($row->efisiensi * 100, 2), // persen untuk tampilan
                'jam_kerja'       => $row->jam_kerja,
                'jumlah_mesin'    => $row->jumlah_mesin,
                // Hasil kalkulasi dari tabel result (bisa null jika belum ada)
                'output_riil_pcs' => $row->kalkulasiResult?->output_riil_pcs,
                'total_biaya_hari'=> $row->kalkulasiResult?->total_biaya_hari,
                'cost_per_pcs'    => $row->kalkulasiResult?->cost_per_pcs,
                'cost_per_kodi'   => $row->kalkulasiResult?->cost_per_kodi,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $rows,
            'meta'   => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ],
        ]);
    }

    /**
     * GET /api/master/mesin
     * Daftar semua mesin untuk dropdown di form.
     */
    public function getMesin(): JsonResponse
    {
        $mesin = MstMesin::orderBy('nama_mesin')
            ->get(['id', 'kode_mesin', 'nama_mesin', 'rpm_default', 'keterangan']);

        return response()->json([
            'status' => 'success',
            'data'   => $mesin,
        ]);
    }

    /**
     * GET /api/master/mesin/{id}
     * Detail satu mesin — dipakai frontend untuk auto-fill RPM saat pilih mesin.
     */
    public function getMesinById(MstMesin $mesin): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $mesin->only(['id', 'kode_mesin', 'nama_mesin', 'rpm_default', 'keterangan']),
        ]);
    }
}
