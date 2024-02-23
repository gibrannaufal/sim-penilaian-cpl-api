<?php

namespace App\Http\Resources\SubCpmk;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCpmkResource extends JsonResource
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
            'id_subcpmk' => $this->id_subcpmk,
            'kode_subcpmk' => $this->kode_subcpmk,
            'nama_subcpmk' => $this->nama_subcpmk,
            'indikator_pencapaian' => $this->indikator_pencapaian,
            'metode_pembelajaran' => $this->settingMetode(),
            'teknik_penilaian' => $this->settingPenilaian(),
            'instrumen_penilaian' => $this->instrumen_penilaian,
            'pertemuan' => $this->pertemuan,
            
        ];
    }
}
