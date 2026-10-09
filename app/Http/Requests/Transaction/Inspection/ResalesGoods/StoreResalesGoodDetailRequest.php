<?php

namespace App\Http\Requests\Transaction\Inspection\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'transaction_code'  => 'required',
            'product_id'        => 'required',
            'spesification'     => 'required|array',
        ];
    }
}
