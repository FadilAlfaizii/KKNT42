<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class MapPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'address',
        'description',
        'image_url',
        'latitude',
        'longitude',
        'is_active',
        'dusun_id',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the dusun that owns the map point
     */
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    /**
     * Scope to filter by dusun
     */
    public function scopeForDusun(Builder $query, ?int $dusunId): Builder
    {
        if ($dusunId) {
            return $query->where('dusun_id', $dusunId);
        }
        return $query;
    }

    /**
     * Scope to filter by user's dusun (for kadus)
     */
    public function scopeForAuthUser(Builder $query): Builder
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query;
        }

        // If user can access all dusuns (kades/superadmin), return all
        if ($user->canAccessAllDusuns()) {
            return $query;
        }

        // Otherwise, filter by user's dusun
        return $query->where('dusun_id', $user->dusun_id);
    }
}
