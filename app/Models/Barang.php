<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'harga' => 'integer'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function depos()
    {
        return $this->belongsToMany(Depo::class, 'depo_barangs');
    }

    public function transaksis()
    {
        return $this->belongsToMany(Transaksi::class, 'transaksi_details')->withPivot(['jumlah']);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_barangs')->withPivot(['jumlah']);
    }
}
