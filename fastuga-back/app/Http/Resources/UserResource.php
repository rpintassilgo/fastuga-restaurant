<?php

namespace App\Http\Resources;

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
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'blocked' => $this->blocked,
            'photo_url' => $this->photo_url,

        ];  // será que temos de meter aqui created_at, updated_at, deleted_at ??
    }       // o que é a coluna 'custom' existente em todas as tabelas ??
}
