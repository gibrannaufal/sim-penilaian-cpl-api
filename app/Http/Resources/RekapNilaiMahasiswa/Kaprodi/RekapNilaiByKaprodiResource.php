<?php

namespace App\Http\Resources\RekapNilaiMahasiswa\Kaprodi;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapNilaiByKaprodiResource extends JsonResource
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
            'nrp' => $this->nrp,
            'nama' => $this->nama,
            'total_nilai' => $this->total_nilai,
        ];
    }
}
