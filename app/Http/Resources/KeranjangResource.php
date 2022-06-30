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
                'harga' => $this->harga,
            ],
            'jumlah' => $this->pivot->jumlah,
            'kategori' => KategoriResource::make($this->whenLoaded('kategori'))
        ];
    }
}
