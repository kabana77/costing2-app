<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Models\MstProduk;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterProdukController extends Controller
{
    /**
     * GET /api/master-crud/produk
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);

        $data = MstProduk::orderBy('kode_produksi')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $data->items(),
            'meta'   => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'per_page'     => $data->perPage(),
                'total'        => $data->total(),
            ],
        ]);
    }

    /**
     * POST /api/master-crud/produk
     */
    public function store(StoreProdukRequest $request): JsonResponse
    {
        $produk = MstProduk::create($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil ditambahkan.',
            'data'    => $produk,
        ], 201);
    }

    /**
     * PUT /api/master-crud/produk/{kodeProduksi}
     *
     * Route model binding manual karena PK bukan 'id' melainkan kode_produksi.
     */
    public function update(StoreProdukRequest $request, string $kodeProduksi): JsonResponse
    {
        $produk = MstProduk::where('kode_produksi', $kodeProduksi)->firstOrFail();
        $produk->update($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil diperbarui.',
            'data'    => $produk,
        ]);
    }

    /**
     * DELETE /api/master-crud/produk/{kodeProduksi}
     *
     * Proteksi: jika produk masih dipakai di trx_produksi_header (FK restrictOnDelete).
     */
    public function destroy(string $kodeProduksi): JsonResponse
    {
        $produk = MstProduk::where('kode_produksi', $kodeProduksi)->firstOrFail();

        try {
            $produk->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Produk berhasil dihapus.',
            ]);

        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Produk tidak dapat dihapus karena masih memiliki data transaksi produksi.',
                ], 409);
            }

            throw $e;
        }
    }
}