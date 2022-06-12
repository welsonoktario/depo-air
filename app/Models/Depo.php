<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depo extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_utama' => 'boolean'
    ];

    public function kurirs()
    {
        return $this->hasMany(Kurir::class);
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
