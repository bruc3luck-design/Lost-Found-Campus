<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'is_pickup_point',
    ];

    protected function casts(): array
    {
        return [
            'is_pickup_point' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function pickupItems(): HasMany
    {
        return $this->hasMany(Item::class, 'pickup_point_id');
    }

    /**
     * Scope untuk memfilter hanya lokasi yang merupakan titik pengambilan.
     */
    public function scopeTitikPengambilan(Builder $query): Builder
    {
        return $query->where('is_pickup_point', true);
    }
}
