<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'bukti'      => ['required', 'string', 'min:10', 'max:2500'],
            'bukti_foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'bukti.required'   => 'Bukti kepemilikan atau detail penemuan wajib diisi.',
            'bukti.min'        => 'Jelaskan bukti secara lebih rinci (minimal 10 karakter).',
            'bukti.max'        => 'Uraian bukti maksimal 2.500 karakter.',
            'bukti_foto.image' => 'File bukti harus berupa gambar.',
            'bukti_foto.mimes' => 'Format foto bukti harus JPG, JPEG, atau PNG.',
            'bukti_foto.max'   => 'Ukuran foto bukti maksimal 2 MB.',
        ];
    }
}
