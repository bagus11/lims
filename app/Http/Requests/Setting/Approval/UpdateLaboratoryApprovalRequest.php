<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLaboratoryApprovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'laboratory_code' => 'required|exists:laboratory_headers,laboratory_code',
            'decision' => ['required', Rule::in([1, 4])],
            'remark' => 'required|string',
        ];
    }
}
