<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @property \MatanYadaev\EloquentSpatial\Objects\Point $lokasi
 * @method static SpatialBuilder query()
 */
class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'user_id'];
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

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class)->with('barangs');
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'customer_barangs')->withPivot(['jumlah']);
    }
}
