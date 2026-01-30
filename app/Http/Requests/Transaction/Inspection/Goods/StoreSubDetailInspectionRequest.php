<?php

namespace App\Http\Requests\Transaction\Inspection\Goods;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubDetailInspectionRequest extends FormRequest
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
            'product'=>'required',
            'length_of_pipe'=>'required',
            'appearance_of_pipe'=>'required',
            'marking'=>'required',
            'condition_pipe_of_cut'=>'required',
            'angle_pipe_of_spigot'=>'required',
            'color_of_pipes'=>'required',
            'evaluation_spec'=>'required',
            'machine_id'    =>'required',
            'time'    =>'required',
            'add_remark'    =>'required',
            'inspector'    =>'required',
        ];
    }
}
