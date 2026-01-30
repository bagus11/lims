<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInspectionGoodSubDetailRequest extends FormRequest
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
            'product_edit'=>'required',
            'length_of_pipe_edit'=>'required',
            'appearance_of_pipe_edit'=>'required',
            'marking_edit'=>'required',
            'condition_pipe_of_cut_edit'=>'required',
            'angle_pipe_of_spigot_edit'=>'required',
            'color_of_pipes_edit'=>'required',
            'evaluation_spec_edit'=>'required',
            'machine_id_edit'    =>'required',
            'reason'            =>'required'
        ];
    }
}
