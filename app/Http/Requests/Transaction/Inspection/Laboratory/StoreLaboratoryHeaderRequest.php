<?php

namespace App\Http\Requests\Transaction\Inspection\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboratoryHeaderRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:master_product,id',
            'product_method_id' => 'required|exists:laboratory_product_methods,id',
            'test_date' => 'required|date',
            'report_no' => 'nullable|string|max:255',
            'production_code_number' => 'nullable|string|max:255',
            'material_lot_number' => 'nullable|string|max:255',
            'location_id' => 'required|integer',
            'shift' => 'nullable|integer|min:1|max:3',
            'machine_id' => 'nullable|exists:machines,id',
        ];
    }
}
