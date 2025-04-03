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
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|mimes:jpeg,png|max:2048',
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ];

    }

    public function messages()
    {
        return [
            'profile_image.mimes' => 'プロフィール画像は.jpegまたは.png形式である必要があります',
            'profile_image.max' => 'プロフィール画像は2MB以下である必要があります',
            'name.required' => 'お名前を入力してください',
            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号はハイフンを含む形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物名を入力してください',
        ];
    }
}
