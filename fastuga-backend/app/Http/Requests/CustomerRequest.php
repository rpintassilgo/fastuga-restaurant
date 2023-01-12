<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PaymentReferenceRule;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() // verificar isto depois mais tarde
    { 
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'photo_url' => 'nullable|string',
            'phone' => 'required',
            'nif' => 'required|digits:9',
            'default_payment_type' => 'required|in:VISA,PAYPAL,MBWAY',
            'default_payment_reference' => ['required','string',new PaymentReferenceRule],
        ];
    }

    public function messages(){
        return [  // completar melhor isto depois 
            'name.required' => 'Name required to create new user',
            'email.unique' => 'Email must not be present already',
            'password.required' => 'Password required to create new user'
        ];
    }
}