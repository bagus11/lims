<?php

namespace App\Http\Requests\Master\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class SaveLaboratoryProductMethodRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:master_product,id',
            'test_method_ids' => 'required|array|min:1',
            'test_method_ids.*' => 'required|distinct|exists:laboratory_test_methods,id',
        ];
    }
}
