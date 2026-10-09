<?php

namespace App\Http\Requests\Transaction\Inspection\Laboratory;

use Illuminate\Foundation\Http\FormRequest;

class LockLaboratoryRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'laboratory_code' => 'required|exists:laboratory_headers,laboratory_code',
        ];
    }
}
