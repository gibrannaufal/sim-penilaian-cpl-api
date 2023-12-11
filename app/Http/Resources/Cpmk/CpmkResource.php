<?php

namespace App\Http\Resources\Cpmk;

use Illuminate\Http\Resources\Json\JsonResource;

class CpmkResource extends JsonResource
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
            'detail_kurikulum' => $this->kurikulum,
            'detail_cpl' => $this->cpl,
            'id_cpmk' => $this->id_cpmk,
            'kode_cpmk' => $this->kode_cpmk,
            'deskripsi_cpmk' => $this->deskripsi_cpmk,
        ];
    }
}
