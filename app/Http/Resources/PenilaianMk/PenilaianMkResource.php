<?php

namespace App\Http\Resources\PenilaianMk;

use Illuminate\Http\Resources\Json\JsonResource;

class PenilaianMkResource extends JsonResource
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
            'id_matakuliah' => $this->id_matakuliah,
            'nama_matakuliah' => $this->nama_matakuliah,
            'kode_matakuliah' => $this->kode_matakuliah,
            'deskripsi' => $this->deskripsi,
            'sks' => $this->sks,
            'bobot' => $this->bobot,
            'semester' => $this->semester,
            'bobot_kajian' => $this->bobot_kajian,
            'id_kurikulum_fk' => $this->kurikulum['id_kurikulum'],
            'kode_kurikulum' => $this->kurikulum['kode_kurikulum'],
            'kurikulum' => $this->kurikulum,
            'prodi' => $this->prodi,
            'kelas' => $this->kelas,
            'uuid_api' => $this->uuid_api,
            'mk_detail' => $this->detailMk
        ];
    }
}
