<?php

namespace App\Http\Requests\Transaction\Inspection\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class SaveLaboratoryResultRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'detail_id' => 'required|exists:laboratory_details,id',
            'measurements' => 'nullable|array|max:100',
            'measurements.*' => 'nullable|numeric',
            'text_result' => 'nullable|string',
            'boolean_result' => 'nullable|boolean',
            'in_charge_user_id' => 'nullable|exists:users,id',
            'checker_user_id' => 'nullable|exists:users,id',
            'remark' => 'nullable|string',
        ];
    }
}
