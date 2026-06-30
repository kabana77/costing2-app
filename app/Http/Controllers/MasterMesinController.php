<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMesinRequest;
use App\Models\MstMesin;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class MasterMesinController extends Controller
{
    /**
     * GET /api/master-crud/mesin
     * Daftar lengkap mesin untuk tabel di halaman master.
     */
    public function index(): JsonResponse
    {
        $mesin = MstMesin::orderBy('kode_mesin')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $mesin,
        ]);
    }

    /**
     * POST /api/master-crud/mesin
     */
    public function store(StoreMesinRequest $request): JsonResponse
    {
        $mesin = MstMesin::create($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Mesin berhasil ditambahkan.',
            'data'    => $mesin,
        ], 201);
    }

    /**
     * PUT /api/master-crud/mesin/{mesin}
     */
    public function update(StoreMesinRequest $request, MstMesin $mesin): JsonResponse
    {
        $mesin->update($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Mesin berhasil diperbarui.',
            'data'    => $mesin,
        ]);
    }

    /**
     * DELETE /api/master-crud/mesin/{mesin}
     *
     * Proteksi: jika mesin masih dipakai di trx_produksi_header (FK restrictOnDelete),
     * MySQL akan melempar QueryException — kita tangkap dan ubah jadi response 409.
     */
    public function destroy(MstMesin $mesin): JsonResponse
    {
        try {
            $mesin->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Mesin berhasil dihapus.',
            ]);

        } catch (QueryException $e) {
            // Error code 23000 = integrity constraint violation (FK restrict)
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Mesin tidak dapat dihapus karena masih memiliki data transaksi produksi.',
                ], 409);
            }

            throw $e;
        }
    }
}