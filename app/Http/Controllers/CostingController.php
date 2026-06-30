<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCostingRequest;
use App\Models\MstMesin;
use App\Models\MstProduk;
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
     *
     * Query params:
     *   tanggal_start  (Y-m-d)
     *   tanggal_end    (Y-m-d)
     *   kode_produksi  (string)
     *   id_mesin       (integer) ← tambahan Tahap 7
     *   sort_by        (tanggal|cost_per_pcs|output_riil_pcs) default: tanggal
     *   sort_dir       (asc|desc) default: desc
     *   per_page       (integer, default 20)
     */
    public function history(Request $request): JsonResponse
    {
        $request->validate([
            'tanggal_start' => ['nullable', 'date', 'date_format:Y-m-d'],
            'tanggal_end'   => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:tanggal_start'],
            'kode_produksi' => ['nullable', 'string', 'exists:mst_produk,kode_produksi'],
            'id_mesin'      => ['nullable', 'integer', 'exists:mst_mesin,id'],
            'sort_by'       => ['nullable', 'in:tanggal,cost_per_pcs,output_riil_pcs,total_biaya_hari'],
            'sort_dir'      => ['nullable', 'in:asc,desc'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $sortBy  = $request->input('sort_by', 'tanggal');
        $sortDir = $request->input('sort_dir', 'desc');

        // Kolom sort yang ada di tabel result pakai join
        $sortOnResult = in_array($sortBy, ['cost_per_pcs', 'output_riil_pcs', 'total_biaya_hari']);

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
            ->when($sortOnResult, fn($q) =>
                $q->join('trx_kalkulasi_result as kr', 'trx_produksi_header.id', '=', 'kr.id_transaksi')
                  ->orderBy('kr.' . $sortBy, $sortDir)
                  ->select('trx_produksi_header.*')
            )
            ->when(!$sortOnResult, fn($q) =>
                $q->orderBy('trx_produksi_header.' . $sortBy, $sortDir)
                  ->orderBy('trx_produksi_header.id', 'desc')
            );

        $data = $query->paginate($request->input('per_page', 20));

        $rows = $data->getCollection()->map(function (TrxProduksiHeader $row) {
            return [
                'id_transaksi'     => $row->id,
                'tanggal'          => $row->tanggal->format('Y-m-d'),
                'kode_produksi'    => $row->kode_produksi,
                'mesin'            => $row->mesin?->nama_mesin,
                'kode_mesin'       => $row->mesin?->kode_mesin,
                'slah_sisir'       => $row->slah_sisir,
                'pick'             => $row->pick,
                'panjang_pcs'      => $row->panjang_pcs,
                'rpm'              => $row->rpm_aktual,
                'efisiensi_pct'    => round($row->efisiensi * 100, 2),
                'jam_kerja'        => $row->jam_kerja,
                'jumlah_mesin'     => $row->jumlah_mesin,
                'output_riil_pcs'  => $row->kalkulasiResult?->output_riil_pcs,
                'total_biaya_hari' => $row->kalkulasiResult?->total_biaya_hari,
                'cost_per_pcs'     => $row->kalkulasiResult?->cost_per_pcs,
                'cost_per_kodi'    => $row->kalkulasiResult?->cost_per_kodi,
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
     */
    public function getMesinById(MstMesin $mesin): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => $mesin->only(['id', 'kode_mesin', 'nama_mesin', 'rpm_default', 'keterangan']),
        ]);
    }

    /**
     * GET /api/master/produk
     * Untuk dropdown filter di halaman riwayat.
     */
    public function getProduk(): JsonResponse
    {
        $produk = MstProduk::orderBy('kode_produksi')
            ->get(['kode_produksi', 'konstruksi']);

        return response()->json([
            'status' => 'success',
            'data'   => $produk,
        ]);
    }
}