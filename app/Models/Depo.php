<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

class Depo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'lokasi' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kurirs()
    {
        return $this->hasMany(Kurir::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
