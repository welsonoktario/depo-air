<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function transaksis()
    {
        return $this->belongsToMany(Transaksi::class, 'pesanan_details')->withPivot(['jumlah']);
    }
}
