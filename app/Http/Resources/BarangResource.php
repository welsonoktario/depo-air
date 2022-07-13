<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'satuan' => $this->satuan,
            'harga' => $this->harga,
            'minPembelian' => $this->min_pembelian,
            'gambar' => $this->gambar,
            'stok' => $this->whenPivotLoaded('depo_barangs', fn () => $this->pivot->stok),
            'kategori' => $this->whenPivotLoaded('kategori', fn () => $this->kategori)
        ];
    }
}
