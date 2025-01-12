<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'item_image' => 'required|file|mimes:jpeg,png|max:2048',
            'item_categories' => 'required|array',
            'item_categories.*' => 'integer|exists:categories,id',
            'condition' => 'required|integer',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'item_image.required' => '商品画像のアップロードしてください',
            'item_image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',
            'item_categories.required' => '商品のカテゴリを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }
}
