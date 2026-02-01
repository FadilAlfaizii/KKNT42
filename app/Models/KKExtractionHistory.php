<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KKExtractionHistory extends Model
{
    protected $table = 'kk_extraction_histories';
    
    protected $fillable = [
        'user_id',
        'extraction_mode',
        'use_mapping',
        'total_files',
        'successful_files',
        'failed_files',
        'total_people',
        'imported_kk',
        'imported_penduduk',
        'duplicates_found',
        'summary_stats',
        'file_names',
        'error_details',
        'excel_file_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'use_mapping' => 'boolean',
        'summary_stats' => 'array',
        'file_names' => 'array',
        'error_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'completed' => 'success',
            'imported' => 'success',
            'partial' => 'warning',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'completed' => 'Selesai',
            'imported' => 'Diimpor',
            'partial' => 'Sebagian',
            'failed' => 'Gagal',
            default => 'Unknown',
        };
    }
}
