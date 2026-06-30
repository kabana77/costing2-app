<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Saat update, kode_produksi di URL adalah kode LAMA (sebelum diedit)
        $kodeLama = $this->route('kodeProduksi');

        return [
            'kode_produksi'   => [
                'required', 'string', 'max:20',
                Rule::unique('mst_produk', 'kode_produksi')->ignore($kodeLama, 'kode_produksi'),
            ],
            'konstruksi'      => ['nullable', 'string', 'max:100'],
            'panjang_default' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_produksi.required'   => 'Kode produksi wajib diisi.',
            'kode_produksi.unique'     => 'Kode produksi sudah digunakan, gunakan kode lain.',
            'kode_produksi.max'        => 'Kode produksi maksimal 20 karakter.',
            'panjang_default.required' => 'Panjang default wajib diisi.',
            'panjang_default.min'      => 'Panjang default tidak boleh 0 atau negatif.',
        ];
    }
}