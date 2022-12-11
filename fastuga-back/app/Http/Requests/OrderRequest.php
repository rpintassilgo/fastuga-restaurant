<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\OrderItemRule;
use App\Rules\PaymentReferenceRule;

class OrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => 'nullable', // verificar se o id existe no user
            'total_paid' => 'required|between:0,99999999.99', // decimal(8,2)
            'points_gained' => 'required|integer',
            'points_used_to_pay' => 'required|integer',
            'payment_type' => 'nullable|in:VISA,PAYPAL,MBWAY',
            'payment_reference' => ['required','string',new PaymentReferenceRule],
            'order_items' => ['required',new OrderItemRule]
        ];
    }

    public function messages(){
        return [  // completar melhor isto depois 
        ];
    }
}
