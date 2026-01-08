<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fasum extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'place_id',
        'name',
        'location',
        'capacity',
        'description',
        'image_url',
        'is_active',
        'longitude',
        'latitude',
        'pan_width',
        'pan_height',
        'pan_length',
    ];

    // dont fill updatedAt and createdAt
    public $timestamps = false;
}

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

class TempatIbadah extends Model
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

class LokasiUsaha extends Model
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

class FasilitasKesehatan extends Model
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

class Pertanian extends Model
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

class Perikanan extends Model
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

class Perkebunan extends Model
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