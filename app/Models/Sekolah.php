<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Sekolah extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'place_id',
        'name',
        'location',
        'description',
        'image_url',
        'longitude',
        'latitude',
    ];

    // dont fill updatedAt and createdAt
    public $timestamps = false;
}
