<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kurir extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function depo()
    {
        return $this->belongsTo(Depo::class);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
