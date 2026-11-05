<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tipe'            => ['required', 'in:hilang,temuan'],
            'judul'           => ['required', 'string', 'max:255'],
            'category_id'     => ['required', 'exists:categories,id'],
            'location_id'     => ['required', 'exists:locations,id'],
            'tanggal'         => ['required', 'date', 'before_or_equal:today'],
            'deskripsi'       => ['required', 'string', 'max:2000'],
            'foto'            => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'hapus_foto'      => ['nullable', 'boolean'],
            'pickup_point_id' => [
                Rule::requiredIf($this->input('tipe') === 'temuan'),
                'nullable',
                Rule::exists('locations', 'id')->where(function ($query) {
                    $query->where('is_pickup_point', true);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipe.required'            => 'Tipe laporan wajib dipilih (Hilang atau Temuan).',
            'tipe.in'                  => 'Tipe laporan hanya boleh Hilang atau Temuan.',
            'judul.required'           => 'Judul laporan wajib diisi.',
            'judul.max'                => 'Judul laporan maksimal 255 karakter.',
            'category_id.required'     => 'Kategori barang wajib dipilih.',
            'category_id.exists'       => 'Kategori yang dipilih tidak valid.',
            'location_id.required'     => 'Lokasi kejadian wajib dipilih.',
            'location_id.exists'       => 'Lokasi yang dipilih tidak valid.',
            'tanggal.required'         => 'Tanggal kejadian wajib diisi.',
            'tanggal.date'             => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal'  => 'Tanggal kejadian tidak boleh di masa depan.',
            'deskripsi.required'       => 'Deskripsi barang wajib diisi.',
            'deskripsi.max'            => 'Deskripsi barang maksimal 2000 karakter.',
            'foto.image'               => 'File yang diunggah harus berupa gambar.',
            'foto.mimes'               => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto.max'                 => 'Ukuran foto maksimal 2 MB.',
            'pickup_point_id.required' => 'Titik pengambilan wajib dipilih untuk laporan barang temuan.',
            'pickup_point_id.exists'   => 'Titik pengambilan yang dipilih tidak valid atau bukan titik serah resmi.',
        ];
    }
}
