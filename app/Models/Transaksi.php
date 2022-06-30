<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $guarded = ['id'];

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
