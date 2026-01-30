<?php

namespace App\Http\Requests\Master\Machine;

use Illuminate\Foundation\Http\FormRequest;

class StoreMachineRequest extends FormRequest
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
            'name'  =>'required',
            'no_machine'  =>'unique:machines,no_machine',
            'description'   =>'required',
            'category_id'   =>'required',
        ];
    }
}
