<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depo extends Model
{
    use HasFactory;

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
