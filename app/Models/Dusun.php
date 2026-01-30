<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get users assigned to this dusun
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get map points in this dusun
     */
    public function mapPoints(): HasMany
    {
        return $this->hasMany(MapPoint::class);
    }

    /**
     * Get keluargas in this dusun
     */
    public function keluargas(): HasMany
    {
        return $this->hasMany(Keluarga::class);
    }

    /**
     * Get penduduks in this dusun
     */
    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    /**
     * Scope for active dusuns
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
