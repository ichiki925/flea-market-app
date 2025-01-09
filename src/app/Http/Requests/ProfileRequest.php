<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'profile_image' => 'nullable|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは.pngである必要があります',
        ];
    }
}
