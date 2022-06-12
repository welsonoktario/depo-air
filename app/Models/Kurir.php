<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $guarded = ['id'];

    public function depo()
    {
        return $this->belongsTo(Depo::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
