<?php

namespace App\Http\Requests\Transaction\Inspection\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class StoreResalesGoodHeaderRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            // location di DB kedua (work_order.master_kantor) — tidak bisa pakai exists: biasa
            'location_id'   => 'required|integer',
            'product_id'    => 'required|exists:master_product_resales_goods,id',
            'inspector_id'  => 'required|exists:users,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'produsen_id'   => 'required|exists:suppliers,id',
            'quantity'      => 'required|integer|min:1',
            'type'          => 'required|string|max:50',
            'brand'         => 'nullable|string|max:255',
            'packaging'     => 'nullable|string|max:255',
            'certificate'   => 'nullable|string|max:255',
            'standard'      => 'nullable|string|max:255',
        ];
    }
}
