<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @property \MatanYadaev\EloquentSpatial\Objects\Point $lokasi
 * @method static SpatialBuilder query()
 */
class Transaksi extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $guarded = ['id'];
    protected $casts = [
        'lokasi' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function depo()
    {
        return $this->belongsTo(Depo::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function userCustomer()
    {
        return $this->belongsToThrough(User::class, Customer::class);
    }

    public function kurir()
    {
        return $this->belongsTo(Kurir::class)->with('user');
    }

    public function userKurir()
    {
        return $this->belongsToThrough(User::class, Kurir::class);
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'transaksi_details')->withPivot(['jumlah']);
    }
}
