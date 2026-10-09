<?php

namespace App\Http\Requests\Transaction\Inspection\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodDetailRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'transaction_code'  => 'required|exists:resales_goods,transaction_code',
            'spesification'     => 'required|array|min:1',
            'spesification.*.symbol' => 'nullable|string|max:100',
            'spesification.*.value'  => 'nullable|string|max:255',
        ];
    }
}
