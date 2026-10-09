<?php

namespace App\Http\Requests\Master\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_product_resales_goods');
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
