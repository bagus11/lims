<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalHeaderRequest extends FormRequest
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
            'location'  =>'required',
            'inspection_type'  =>'required',
            'type'  =>'required',
            'step'  =>'required',
            'shift'  =>'required',
        ];
    }
}
