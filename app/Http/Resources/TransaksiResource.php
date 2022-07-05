<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
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
            'tanggal' => $this->tanggal,
            'status' => $this->status,
            'depo' => DepoResource::make($this->whenLoaded('depo')),
            'kurir' => KurirResource::make($this->whenLoaded('kurir')),
            'details' => KeranjangCollection::make($this->whenLoaded('barangs'))
        ];
    }
}
