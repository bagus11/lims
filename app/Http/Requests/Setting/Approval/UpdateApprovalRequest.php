<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-approval');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'approval_id' => 'required|exists:approval_headers,approval_code',
            'user_array' => 'required|array|min:1',
            'user_array.*.user_id' => 'required|exists:users,id',
            'user_array.*.step' => 'required|integer|min:1',
        ];
    }
}
