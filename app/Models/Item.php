<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'category_id',
        'deskripsi',
        'foto',
        'location_id',
        'pickup_point_id',
        'tanggal',
        'status',
        'disembunyikan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'       => 'date',
            'disembunyikan' => 'boolean',
        ];
    }

    // ==========================================
    // Relasi
    // ==========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function pickupPoint(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pickup_point_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function approvedClaim(): HasOne
    {
        return $this->hasOne(Claim::class)->where('status', 'disetujui');
    }

    // ==========================================
    // Query Scopes (Mengabaikan parameter kosong)
    // ==========================================

    /**
     * Tampilkan item yang tidak disembunyikan oleh admin.
     */
    public function scopeTampil(Builder $query): Builder
    {
        return $query->where('disembunyikan', false);
    }

    /**
     * Filter berdasarkan tipe (hilang / temuan).
     */
    public function scopeTipe(Builder $query, ?string $tipe): Builder
    {
        if (blank($tipe)) {
            return $query;
        }

        return $query->where('tipe', $tipe);
    }

    /**
     * Pencarian kata kunci pada judul dan deskripsi.
     */
    public function scopeCari(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('judul', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }

    /**
     * Filter berdasarkan kategori.
     */
    public function scopeKategori(Builder $query, ?int $categoryId): Builder
    {
        if (blank($categoryId)) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Filter berdasarkan lokasi kejadian.
     */
    public function scopeLokasi(Builder $query, ?int $locationId): Builder
    {
        if (blank($locationId)) {
            return $query;
        }

        return $query->where('location_id', $locationId);
    }

    /**
     * Filter rentang tanggal (dari dan/atau sampai).
     */
    public function scopeAntara(Builder $query, ?string $dari, ?string $sampai): Builder
    {
        if (filled($dari)) {
            $query->whereDate('tanggal', '>=', $dari);
        }

        if (filled($sampai)) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        return $query;
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    public function isOpen(): bool
    {
        return in_array($this->status, ['hilang', 'ditemukan'], true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'hilang'       => 'Hilang',
            'ditemukan'    => 'Ditemukan',
            'diklaim'      => 'Diklaim',
            'dikembalikan' => 'Dikembalikan',
            default        => ucfirst($this->status),
        };
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'hilang'       => 'bg-rose-100 text-rose-800 border-rose-200',
            'ditemukan'    => 'bg-sky-100 text-sky-800 border-sky-200',
            'diklaim'      => 'bg-amber-100 text-amber-800 border-amber-200',
            'dikembalikan' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default        => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function tipeLabel(): string
    {
        return match ($this->tipe) {
            'hilang' => 'Barang Hilang',
            'temuan' => 'Barang Temuan',
            default  => ucfirst($this->tipe),
        };
    }

    public function tipeBadgeClasses(): string
    {
        return match ($this->tipe) {
            'hilang' => 'bg-rose-50 text-rose-700 border-rose-200',
            'temuan' => 'bg-uin-50 text-uin-700 border-uin-500/20',
            default  => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }
}
