<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotSensors extends Model
{
    use HasFactory;
    use HasUuids;
    
    // Enable timestamps for proper tracking
    public $timestamps = true;
    
    // Use custom timestamp column names
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null; // Only track creation time
    
    protected $fillable = [
        'farm_id',
        'temperature',
        'humidity',
        'ammonia',
        'light_intensity',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'ammonia' => 'decimal:2',
        'light_intensity' => 'decimal:2',
        'createdAt' => 'datetime',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
