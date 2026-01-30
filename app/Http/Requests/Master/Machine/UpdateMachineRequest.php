<?php

namespace App\Http\Requests\Master\Machine;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMachineRequest extends FormRequest
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
            'name_edit'  =>'required',
            'category_id_edit'  =>'required',
            'no_machine_edit'  =>'required',
            'description_edit'  =>'required',
        ];
    }
}
