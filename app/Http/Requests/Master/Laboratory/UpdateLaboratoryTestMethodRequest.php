<?php

namespace App\Http\Requests\Master\Laboratory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratoryTestMethodRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_laboratory');
    }

    public function rules()
    {
        return [
            'id' => 'required|exists:laboratory_test_methods,id',
            'code' => ['required', 'string', 'max:100', Rule::unique('laboratory_test_methods', 'code')->ignore($this->id)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ];
    }
}
