<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'type' => 'required|in:EC,ED,EM',
            'blocked' => 'required|boolean',
            'photo_url' => 'nullable|image|max:8192',
            'phone' => '',
            'points' => '',
            'nif' => '',
            'default_payment_type' => '',
            'default_payment_reference' => '',
        ];
    }

    public function messages(){
        return [  // completar melhor isto depois 
            'name.required' => 'Name required to create new user',
            'email.unique' => 'Email must not be present already',
            'password.required' => 'Password required to create new user',
            'name.alpha' => 'Name must contain only alphabetic characters',
            'type.in' => 'Invalid type',
        ];
    }
}
