<?php

namespace App\Http\Requests\Transaction\Inspection\Material;

use Illuminate\Foundation\Http\FormRequest;

class AddInspectionMaterialRequest extends FormRequest
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
            'type'  =>'required',
            'location'  =>'required',
            'material'  =>'required',
            'inspector'  =>'required',
            'supplier'  =>'required',
            'brand'  =>'required',
            'amount'  =>'required',
            'weight'  =>'required',
            'certificate'  =>'required',
            'standard'  =>'required',
            'no_lot'  =>'required',
        ];
    }
}
