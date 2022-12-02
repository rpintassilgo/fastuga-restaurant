<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Customer;

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

        $userData = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'blocked' => $this->blocked,
            'photo_url' => $this->photo_url,
        ];

        if($this->type === 'C') {
            $customer = Customer::where('user_id',$this->id)->first();
    
            $userData = array_merge($userData,[
                'phone' => $customer->phone,
                'points' => $customer->points,
                'nif' => $customer->nif,
                'default_payment_type' => $customer->default_payment_type,
                'default_payment_reference' => $customer->default_payment_reference,
            ]);
        }
        
        return $userData;
    }
}
