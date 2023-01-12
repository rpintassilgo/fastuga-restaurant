<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // verificar os campos do pedido do produto
            'name' => 'required|string|max:255', 
            'type' => 'required|string|in:hot dish,cold dish,drink,dessert', // ver se strings com espaços funcionam tipo "hot dish"
            'description' => 'required|string|max:255', 
            'photo_url' => 'nullable|string', // isto é capaz de ser obrigatorio, mas agora para testar dá jeito nullable
            'price' => 'required|between:0,99999999.99',
        ];
    }

    public function messages(){
        return [  
            // completar melhor isto depois 
        ];
    }
}
