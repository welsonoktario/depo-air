<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepoResource extends JsonResource
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
            'alamat' => $this->alamat,
            'tipeCabang' => $this->tipe_cabang,
            'lokasi' => [
                'lat' => $this->lokasi->latitude,
                'long' => $this->lokasi->longitude
            ],
            'barangs' => BarangResource::collection($this->whenLoaded('barangs')),
        ];
        ;
    }
}
