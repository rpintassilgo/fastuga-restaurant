<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'photo_file' => 'required|mimes:jpg,jpeg,png|max:8191',
        ];
    }
}
