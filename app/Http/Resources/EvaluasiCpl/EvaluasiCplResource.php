<?php

namespace App\Http\Resources\EvaluasiCpl;

use Illuminate\Http\Resources\Json\JsonResource;

class EvaluasiCplResource extends JsonResource
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
        ];
    }
}
