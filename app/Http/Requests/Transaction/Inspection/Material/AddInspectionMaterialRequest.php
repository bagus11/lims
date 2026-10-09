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
            'type'  =>['required', \Illuminate\Validation\Rule::in([1, 2])],
            'location'  =>'required|integer',
            'material'  =>'required|exists:materials,id',
            'inspector'  =>'required|exists:users,id',
            'supplier'  =>'required|exists:suppliers,id',
            'produsen_id' =>'nullable|required_if:type,2|exists:suppliers,id',
            'brand'  =>'required|string|max:255',
            'amount'  =>'required|integer|min:1',
            'weight'  =>'required|numeric|min:0',
            'certificate'  =>'required|string|max:255',
            'standard'  =>'required|string|max:255',
            'no_lot'  =>'required|string|max:255',
        ];
    }
}
