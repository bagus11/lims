<?php

namespace App\Http\Requests\Master\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class StoreLaboratoryTestMethodRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_laboratory');
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:100|unique:laboratory_test_methods,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
