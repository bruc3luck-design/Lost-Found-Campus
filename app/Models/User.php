<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'role',
        'fakultas',
        'prodi',
        'no_wa',
        'is_admin',
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Filament Admin Panel access authorization.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Helper cek admin.
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Relasi ke laporan barang.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Relasi ke klaim yang diajukan.
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class, 'claimer_id');
    }

    /**
     * Format nomor WhatsApp untuk URL wa.me (mengubah awalan 0 menjadi 62).
     */
    public function nomorWaFormatted(): ?string
    {
        if (! $this->no_wa) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $this->no_wa);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}
