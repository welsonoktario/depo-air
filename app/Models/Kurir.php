<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kurir extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function depo()
    {
        return $this->belongsTo(Depo::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Transaksi::class);
    }
}
