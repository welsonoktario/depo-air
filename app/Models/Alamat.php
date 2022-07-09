<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @property \MatanYadaev\EloquentSpatial\Objects\Point $lokasi
 * @method static SpatialBuilder query()
 */
class Alamat extends Model
{
    use HasFactory, \Znck\Eloquent\Traits\BelongsToThrough;

    protected $guarded = ['id', 'customer_id'];
    protected $casts = [
        'lokasi' => Point::class,
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsToThrough(User::class, Customer::class);
    }
}
