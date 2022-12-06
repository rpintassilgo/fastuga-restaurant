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
            'name' => 'required|alpha|max:255',
            'email' => 'required|email',
            'password' => 'required|string',
            'type' => 'required|in:C',
            'blocked' => 'required|boolean',
            'photo_url' => 'nullable|image|max:8192',
            'phone' => 'required|digits:9', // nao sei se convem verificar o segundo digito tbm pq n existe numeros começados por 99, 90 etc
            'points' => 'required|integer|min:0',
            'nif' => 'required|digits:9',
            'default_payment_type' => 'required|in:VISA,PAYPAL,MBWAY',
            'default_payment_reference' => ['required','string',new PaymentTypeRule],
        ];
    }

    public function messages(){
        return [  // completar melhor isto depois 
            'name.required' => 'Name required to create new user',
            'email.unique' => 'Email must not be present already',
            'password.required' => 'Password required to create new user',
            'name.alpha' => 'Name must contain only alphabetic characters',
            'type.enum_value' => 'Invalid type',
        ];
    }
}