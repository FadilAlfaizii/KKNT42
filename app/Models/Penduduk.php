<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Penduduk extends Model
{
    protected $fillable = [
        'keluarga_id',
        'dusun_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'umur',
        'agama',
        'pendidikan',
        'pekerjaan',
        'status_perkawinan',
        'status_dalam_keluarga',
        'nama_ayah',
        'nama_ibu',
        'golongan_darah',
        'kewarganegaraan',
        'no_paspor',
        'no_akta_lahir',
        'status_penduduk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the keluarga that owns the penduduk
     */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class);
    }

    /**
     * Get the dusun that owns the penduduk
     */
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    /**
     * Calculate age from tanggal_lahir
     */
    public function calculateUmur(): void
    {
        if ($this->tanggal_lahir) {
            $this->umur = Carbon::parse($this->tanggal_lahir)->age;
        }
    }

    /**
     * Boot method to auto-calculate age
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($penduduk) {
            $penduduk->calculateUmur();
        });
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
     * Scope for active residents
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status_penduduk', 'TETAP');
    }
}
