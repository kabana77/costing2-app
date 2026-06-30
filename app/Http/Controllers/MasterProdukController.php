<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Models\MstProduk;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class MasterProdukController extends Controller
{
    /**
     * GET /api/master-crud/produk
     */
    public function index(): JsonResponse
    {
        $produk = MstProduk::orderBy('kode_produksi')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $produk,
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