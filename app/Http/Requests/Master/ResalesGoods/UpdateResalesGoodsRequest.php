<?php

namespace App\Http\Requests\Master\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResalesGoodsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'size' => 'nullable',
            'spesification' => 'nullable',
        ];
    }
}
