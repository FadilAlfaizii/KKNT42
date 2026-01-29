<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Keluarga extends Model
{
    protected $fillable = [
        'dusun_id',
        'no_kk',
        'kepala_keluarga',
        'alamat',
        'rt',
        'rw',
        'kelurahan_desa',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
        'status_kk',
        'tanggal_terbit',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    /**
     * Get the dusun that owns the keluarga
     */
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    /**
     * Get all penduduk (members) of this keluarga
     */
    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    /**
     * Get jumlah anggota keluarga
     */
    public function getJumlahAnggotaAttribute(): int
    {
        return $this->penduduks()->count();
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

    /**
     * Scope for active KK
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status_kk', 'AKTIF');
    }
}
