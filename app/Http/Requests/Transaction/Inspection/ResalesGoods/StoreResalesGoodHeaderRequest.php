<?php

namespace App\Http\Requests\Transaction\Inspection\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodHeaderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'location_id'   => 'required',
            'product_id'    => 'required',
            'inspector_id'  => 'required',
            'supplier_id'   => 'required',
            'produsen_id'   => 'required',
            'quantity'      => 'required|integer',
            'type'          => 'required',
        ];
    }
}
