<?php

namespace App\Http\Requests\Master\Laboratory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveLaboratoryMethodParametersRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_laboratory');
    }

    public function rules()
    {
        $productFields = ['min_od', 'max_od', 'avg_od', 'min_wt', 'max_wt', 'avg_wt'];

        return [
            'product_method_id' => 'required|exists:laboratory_product_methods,id',
            'parameters' => 'required|array|min:1',
            'parameters.*.test_parameter_id' => 'required|distinct|exists:laboratory_test_parameters,id',
            'parameters.*.sequence' => 'required|integer|min:1',
            'parameters.*.source_type' => ['nullable', Rule::in(['PRODUCT_FIELD', 'STATIC'])],
            'parameters.*.source_min_field' => ['nullable', Rule::in($productFields)],
            'parameters.*.source_max_field' => ['nullable', Rule::in($productFields)],
            'parameters.*.source_target_field' => ['nullable', Rule::in($productFields)],
            'parameters.*.operator' => ['nullable', Rule::in(['>=', '<=', '=', 'BETWEEN'])],
            'parameters.*.min_value' => 'nullable|numeric',
            'parameters.*.max_value' => 'nullable|numeric',
            'parameters.*.target_value' => 'nullable|numeric',
            'parameters.*.tolerance_plus' => 'nullable|numeric|min:0',
            'parameters.*.tolerance_minus' => 'nullable|numeric|min:0',
            'parameters.*.expected_value' => 'nullable|string',
            'parameters.*.unit' => 'nullable|string|max:100',
            'parameters.*.measurement_count' => 'nullable|integer|min:1|max:100',
            'parameters.*.calculation_type' => ['required', Rule::in(['NONE', 'AVG', 'MIN', 'MAX'])],
            'parameters.*.is_required' => 'required|boolean',
            'parameters.*.status' => 'nullable|boolean',
        ];
    }
}
