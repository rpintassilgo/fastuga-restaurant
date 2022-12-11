<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
           /* 'password' => [  // isto pode ser um extra nao sei como na db as pws sao todas 123
                'required',
                'string',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ], */
            'password' => 'required|string',
            'type' => 'required|in:EC,ED,EM',
            'blocked' => 'required|boolean',
            'photo_url' => 'nullable|image|max:8192'
        ];
    }

    public function messages(){
        return [  // completar melhor isto depois 
            'name.required' => 'Name required to create new user',
            'email.unique' => 'Email must not be present already',
            'password.required' => 'Password required to create new user',
            'type.in' => 'Invalid type',
        ];
    }
}
