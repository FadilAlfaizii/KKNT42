<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Penduduk extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'penduduk_id',
        'name',
        'usia',
        'jenis_kelamin',
        'alamat',
        'pekerjaan',
        'pendidikan',
    ];

    // dont fill updatedAt and createdAt
    public $timestamps = false;
}
