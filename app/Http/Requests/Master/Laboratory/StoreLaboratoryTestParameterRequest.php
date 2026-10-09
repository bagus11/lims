<?php

namespace App\Http\Requests\Master\Laboratory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLaboratoryTestParameterRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_laboratory');
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:100|unique:laboratory_test_parameters,code',
            'name' => 'required|string|max:255',
            'result_type' => ['required', Rule::in(['numeric', 'text', 'boolean'])],
            'default_unit' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
