<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'claimer_id',
        'bukti',
        'bukti_foto',
        'status',
        'catatan',
    ];

    // ==========================================
    // Relasi
    // ==========================================

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function claimer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimer_id');
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu'  => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'menunggu'  => 'bg-amber-100 text-amber-800 border-amber-200',
            'disetujui' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'ditolak'   => 'bg-rose-100 text-rose-800 border-rose-200',
            default     => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
