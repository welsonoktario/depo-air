<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alamats()
    {
        return $this->hasMany(Alamat::class);
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
