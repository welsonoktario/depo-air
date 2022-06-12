<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $guarded = ['id'];

    public function pesanans()
    {
        return $this->belongsToMany(Pesanan::class, 'pesanan_details')->withPivot(['jumlah']);
    }
}
