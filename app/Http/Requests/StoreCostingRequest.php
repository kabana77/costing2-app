<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Sudah dijaga middleware auth di route
        return true;
    }

    public function rules(): array
    {
        return [
            // --- Parameter identitas ---
            'tanggal'        => ['required', 'date', 'date_format:Y-m-d'],
            'id_mesin'       => ['required', 'integer', 'exists:mst_mesin,id'],
            'kode_produksi'  => ['required', 'string', 'exists:mst_produk,kode_produksi'],

            // --- Parameter kain ---
            'slah_sisir'     => ['required', 'integer', 'min:1'],
            'pick'           => ['required', 'integer', 'min:1'],       // pembagi, tidak boleh 0
            'panjang_pcs'    => ['required', 'numeric', 'min:0.01'],    // pembagi, tidak boleh 0

            // --- Parameter mesin ---
            'jam_kerja'      => ['required', 'numeric', 'min:0.01', 'max:24'],
            'efisiensi'      => ['required', 'numeric', 'min:0', 'max:100'], // input dalam %, service konversi ke desimal
            'rpm_aktual'     => ['nullable', 'numeric', 'min:0.01'],         // opsional, fallback ke rpm_default
            'jumlah_mesin'   => ['required', 'integer', 'min:1'],

            // --- Komponen biaya (nested array) ---
            'biaya'                    => ['required', 'array'],
            'biaya.upah_operator'      => ['required', 'numeric', 'min:0'],
            'biaya.listrik_mesin'      => ['required', 'numeric', 'min:0'],
            'biaya.maintenance'        => ['required', 'numeric', 'min:0'],
            'biaya.penyusutan_mesin'   => ['required', 'numeric', 'min:0'],
            'biaya.biaya_lain'         => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required'                  => 'Tanggal produksi wajib diisi.',
            'tanggal.date_format'               => 'Format tanggal harus YYYY-MM-DD.',
            'id_mesin.exists'                   => 'Mesin yang dipilih tidak terdaftar.',
            'kode_produksi.exists'              => 'Kode produksi tidak terdaftar di master.',
            'pick.min'                          => 'Pick tidak boleh 0 (digunakan sebagai pembagi).',
            'panjang_pcs.min'                   => 'Panjang pcs tidak boleh 0 (digunakan sebagai pembagi).',
            'jam_kerja.max'                     => 'Jam kerja tidak boleh melebihi 24 jam.',
            'efisiensi.min'                     => 'Efisiensi tidak boleh negatif.',
            'efisiensi.max'                     => 'Efisiensi tidak boleh melebihi 100%.',
            'jumlah_mesin.min'                  => 'Jumlah mesin minimal 1 unit.',
            'biaya.upah_operator.min'           => 'Upah operator tidak boleh negatif.',
            'biaya.listrik_mesin.min'           => 'Listrik mesin tidak boleh negatif.',
            'biaya.maintenance.min'             => 'Biaya maintenance tidak boleh negatif.',
            'biaya.penyusutan_mesin.min'        => 'Biaya penyusutan tidak boleh negatif.',
            'biaya.biaya_lain.min'              => 'Biaya lain tidak boleh negatif.',
        ];
    }

    /**
     * Normalisasi sebelum validasi:
     * - biaya_lain yang null diisi 0 agar kalkulasi aman
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'biaya' => array_merge(
                ['biaya_lain' => 0],
                $this->input('biaya', [])
            ),
        ]);
    }
}
