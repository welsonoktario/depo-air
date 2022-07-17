<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KeranjangResource extends JsonResource
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
            'barang' => [
                'id' => $this->id,
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'satuan' => $this->satuan,
                'harga' => $this->harga,
                'minPembelian' => $this->min_pembelian,
                'gambar' => $this->gambar,
            ],
            'jumlah' => $this->pivot->jumlah,
            'kategori' => $this->whenLoaded('kategori', KategoriResource::make($this->kategori))
        ];
    }
}
