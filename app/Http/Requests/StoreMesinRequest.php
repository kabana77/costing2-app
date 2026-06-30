<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sudah dijaga middleware auth di route
    }

    public function rules(): array
    {
        // Saat update, abaikan kode_mesin milik record yang sedang diedit dari rule unique
        $mesinId = $this->route('mesin')?->id;

        return [
            'kode_mesin'  => [
                'required', 'string', 'max:10',
                Rule::unique('mst_mesin', 'kode_mesin')->ignore($mesinId),
            ],
            'nama_mesin'  => ['required', 'string', 'max:50'],
            'rpm_default' => ['required', 'numeric', 'min:0.01'],
            'keterangan'  => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_mesin.required'  => 'Kode mesin wajib diisi.',
            'kode_mesin.unique'    => 'Kode mesin sudah digunakan, gunakan kode lain.',
            'kode_mesin.max'       => 'Kode mesin maksimal 10 karakter.',
            'nama_mesin.required'  => 'Nama mesin wajib diisi.',
            'rpm_default.required' => 'RPM default wajib diisi.',
            'rpm_default.min'      => 'RPM default tidak boleh 0 atau negatif.',
        ];
    }
}