<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    public function depo()
    {
        return $this->belongsTo(Depo::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->with('user');
    }

    public function kurir()
    {
        return $this->belongsTo(Kurir::class)->with('user');
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'transaksi_details')->withPivot(['jumlah']);
    }
}
