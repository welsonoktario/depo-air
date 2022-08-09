<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

/**
 * @property \MatanYadaev\EloquentSpatial\Objects\Point $lokasi
 * @method static SpatialBuilder query()
 */
class Depo extends Model
{
    use HasFactory, SoftDeletes;

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

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'depo_barangs')
            ->withPivot('stok');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
