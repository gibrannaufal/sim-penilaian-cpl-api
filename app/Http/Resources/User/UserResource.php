<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'nrp' => $this->nrp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'prodi' => $this->prodi,
            'user_roles_id' => $this->user_roles_id,
            'roles_name' => $this->userRoles->name,
          
        ];
    }
}
