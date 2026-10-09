<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResalesGoodsApprovalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'transaction_code' => 'required|exists:resales_goods,transaction_code',
            'decision' => ['required', Rule::in([1, 4])],
            'remark' => 'required|string',
        ];
    }
}
