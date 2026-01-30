<?php

namespace App\Http\Requests\Setting\UserAccess;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'roleId'=>'required',
            'userId'=>['required','unique:model_has_roles,model_id'],
        ];
    }
}
