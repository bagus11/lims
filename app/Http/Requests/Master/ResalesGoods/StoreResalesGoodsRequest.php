<?php

namespace App\Http\Requests\Master\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'size' => 'nullable',
            'spesification' => 'nullable',
        ];
    }
}
