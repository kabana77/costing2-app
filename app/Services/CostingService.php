<?php

namespace App\Services;

use App\Models\MstMesin;
use App\Models\TrxBiayaDetail;
use App\Models\TrxKalkulasiResult;
use App\Models\TrxProduksiHeader;
use Illuminate\Support\Facades\DB;
use Throwable;

class CostingService
{
    /**
     * Entry point utama.
     * Menerima array data tervalidasi dari Controller,
     * menjalankan kalkulasi, menyimpan ke DB, dan mengembalikan hasil.
     *
     * @param  array $data  Data sudah lolos validasi dari FormRequest
     * @return array        Hasil kalkulasi + id_transaksi
     * @throws Throwable    Jika DB transaction gagal
     */
    public function calculate(array $data): array
    {
        // 1. Normalisasi efisiensi: pastikan tersimpan dalam bentuk desimal (0–1)
        $efisiensi = $this->normalizeEfisiensi((float) $data['efisiensi']);

        // 2. Tentukan RPM — pakai rpm_aktual jika diisi, fallback ke rpm_default mesin
        $rpm = $this->resolveRpm($data);

        // 3. Hitung output produksi riil (Pcs/Hari)
        $outputPcs = $this->hitungOutputPcs(
            rpm:         $rpm,
            jamKerja:    (float) $data['jam_kerja'],
            efisiensi:   $efisiensi,
            jumlahMesin: (int)   $data['jumlah_mesin'],
            pick:        (int)   $data['pick'],
            panjangPcs:  (float) $data['panjang_pcs'],
        );

        // 4. Hitung total biaya harian (SUM 5 komponen)
        $totalBiaya = $this->hitungTotalBiaya($data['biaya']);

        // 5. Hitung cost per pcs & per kodi (dengan proteksi division by zero)
        $costResult = $this->hitungCostPerUnit($totalBiaya, $outputPcs);

        // 6. Satukan semua hasil
        $hasil = [
            'output_riil_pcs'  => round($outputPcs, 4),
            'total_biaya_hari' => round($totalBiaya, 2),
            'cost_per_pcs'     => round($costResult['cost_per_pcs'], 2),
            'cost_per_kodi'    => round($costResult['cost_per_kodi'], 2),
        ];

        // 7. Simpan ke database dalam satu transaksi atomik
        $header = $this->simpanKeDatabase($data, $efisiensi, $rpm, $hasil);

        return array_merge(['id_transaksi' => $header->id], $hasil);
    }

    /**
     * Normalisasi efisiensi.
     * Input bisa berupa persen (mis. 80) atau desimal (mis. 0.8).
     * Output selalu desimal 0.0000–1.0000.
     */
    public function normalizeEfisiensi(float $nilai): float
    {
        // Jika user menginput lebih dari 1, anggap dalam bentuk persen
        return $nilai > 1 ? round($nilai / 100, 4) : round($nilai, 4);
    }

    /**
     * Tentukan RPM yang dipakai untuk kalkulasi.
     * Prioritas: rpm_aktual (dari input) → rpm_default (dari master mesin).
     */
    public function resolveRpm(array $data): float
    {
        if (!empty($data['rpm_aktual']) && (float) $data['rpm_aktual'] > 0) {
            return (float) $data['rpm_aktual'];
        }

        $mesin = MstMesin::findOrFail($data['id_mesin']);

        return (float) $mesin->rpm_default;
    }

    /**
     * Formula inti output produksi (Dokumentasi Bag. II.A).
     *
     * Output_Pcs = (RPM × JamKerja × 60 × Efisiensi × JumlahMesin) / Pick × 2.54 / PanjangPcs
     */
    public function hitungOutputPcs(
        float $rpm,
        float $jamKerja,
        float $efisiensi,
        int   $jumlahMesin,
        int   $pick,
        float $panjangPcs,
    ): float {
        // Guard: pick dan panjang_pcs tidak boleh 0 (sudah dicek validasi, tapi double-check)
        if ($pick <= 0 || $panjangPcs <= 0) {
            return 0.0;
        }

        $totalAnyaman   = $rpm * ($jamKerja * 60) * $efisiensi * $jumlahMesin;
        $panjangInch    = $totalAnyaman / $pick;
        $panjangCm      = $panjangInch * 2.54;
        $outputPcs      = $panjangCm / $panjangPcs;

        return $outputPcs;
    }

    /**
     * Hitung total biaya harian dari 5 komponen.
     * Sesuai Dokumentasi Bag. II.B.
     */
    public function hitungTotalBiaya(array $biaya): float
    {
        return (float) $biaya['upah_operator']
             + (float) $biaya['listrik_mesin']
             + (float) $biaya['maintenance']
             + (float) $biaya['penyusutan_mesin']
             + (float) ($biaya['biaya_lain'] ?? 0);
    }

    /**
     * Hitung cost per pcs dan cost per kodi.
     * Sesuai Dokumentasi Bag. II.C.
     * Proteksi division by zero: jika output = 0, kembalikan 0.
     */
    public function hitungCostPerUnit(float $totalBiaya, float $outputPcs): array
    {
        if ($outputPcs <= 0) {
            return [
                'cost_per_pcs'  => 0.0,
                'cost_per_kodi' => 0.0,
            ];
        }

        $costPerPcs  = $totalBiaya / $outputPcs;
        $costPerKodi = $costPerPcs * 20;

        return [
            'cost_per_pcs'  => $costPerPcs,
            'cost_per_kodi' => $costPerKodi,
        ];
    }

    /**
     * Simpan hasil ke 3 tabel dalam satu DB transaction.
     * Jika salah satu INSERT gagal, semua di-rollback otomatis.
     *
     * @throws Throwable
     */
    public function simpanKeDatabase(
        array $data,
        float $efisiensi,
        float $rpm,
        array $hasil,
    ): TrxProduksiHeader {
        return DB::transaction(function () use ($data, $efisiensi, $rpm, $hasil) {

            // ① Simpan header transaksi
            $header = TrxProduksiHeader::create([
                'tanggal'       => $data['tanggal'],
                'id_mesin'      => $data['id_mesin'],
                'kode_produksi' => $data['kode_produksi'],
                'slah_sisir'    => $data['slah_sisir'],
                'pick'          => $data['pick'],
                'panjang_pcs'   => $data['panjang_pcs'],
                'jam_kerja'     => $data['jam_kerja'],
                'efisiensi'     => $efisiensi,           // sudah dinormalisasi ke desimal
                'rpm_aktual'    => $rpm,                 // sudah di-resolve
                'jumlah_mesin'  => $data['jumlah_mesin'],
            ]);

            // ② Simpan 5 baris komponen biaya
            $now      = now();
            $biayaMap = [
                TrxBiayaDetail::KOMPONEN[0] => $data['biaya']['upah_operator'],
                TrxBiayaDetail::KOMPONEN[1] => $data['biaya']['listrik_mesin'],
                TrxBiayaDetail::KOMPONEN[2] => $data['biaya']['maintenance'],
                TrxBiayaDetail::KOMPONEN[3] => $data['biaya']['penyusutan_mesin'],
                TrxBiayaDetail::KOMPONEN[4] => $data['biaya']['biaya_lain'] ?? 0,
            ];

            $biayaRows = [];
            foreach ($biayaMap as $komponen => $nilai) {
                $biayaRows[] = [
                    'id_transaksi'   => $header->id,
                    'komponen_biaya' => $komponen,
                    'nilai_per_hari' => (float) $nilai,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];
            }
            TrxBiayaDetail::insert($biayaRows);

            // ③ Simpan hasil kalkulasi (cache result)
            TrxKalkulasiResult::create([
                'id_transaksi'    => $header->id,
                'output_riil_pcs' => $hasil['output_riil_pcs'],
                'total_biaya_hari'=> $hasil['total_biaya_hari'],
                'cost_per_pcs'    => $hasil['cost_per_pcs'],
                'cost_per_kodi'   => $hasil['cost_per_kodi'],
            ]);

            return $header;
        });
    }
}
