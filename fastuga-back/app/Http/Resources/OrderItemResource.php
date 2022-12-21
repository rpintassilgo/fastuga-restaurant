<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductResource;

class OrderItemResource extends JsonResource
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
            'order_id' => $this->order_id,
            'order_local_number' => $this->order_local_number,
            'status' => $this->status,
            'price' => $this->price,
            'preparation_by' => $this->preparation_by,
            'notes' => $this->notes,
            'product' => $this->product
        ];
    }
}
