<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Customer;

class CustomerResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'blocked' => $this->blocked,
            'photo_url' => $this->photo_url,
            'phone' => $this->phone,
            'points' => $this->points,
            'nif' => $this->nif,
            'default_payment_type' => $this->default_payment_type,
            'default_payment_reference' => $this->default_payment_reference,
        ];
    }
}
