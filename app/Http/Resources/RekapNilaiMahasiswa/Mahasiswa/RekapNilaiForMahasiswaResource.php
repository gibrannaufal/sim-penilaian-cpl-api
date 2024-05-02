<?php

namespace App\Http\Resources\RekapNilaiMahasiswa\Mahasiswa;

use Illuminate\Http\Resources\Json\JsonResource;

class RekapNilaiForMahasiswaResource extends JsonResource
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
            'nama_matakuliah' => $this->nama_matakuliah,
            'total_nilai' => $this->total_nilai,
            'id_mk_fk' => $this->id_mk_fk,
            'id_detailmk_fk' => $this->id_detailmk_fk,
            'nrp' => $this->nrp,
        ];
    }
}
