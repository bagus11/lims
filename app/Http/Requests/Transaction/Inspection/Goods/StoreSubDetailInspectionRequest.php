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
            'detail_inspection_code_id'=>'required|exists:inspection_goods_details,detail_inspection_code',
            'product'=>'required|exists:master_product,id',
            'material'=>'required|exists:materials,id',
            'length_of_pipe'=>'required|numeric|min:0',
            'appearance_of_pipe'=>'required|string|max:100',
            'marking'=>'required|string|max:100',
            'condition_pipe_of_cut'=>'required|string|max:100',
            'angle_pipe_of_spigot'=>'required|string|max:100',
            'color_of_pipes'=>'required|string|max:100',
            'evaluation_spec'=>'required|string|max:100',
            'machine_id'    =>'required|exists:machines,id',
            'time'    =>'required|string|max:20',
            'add_remark'    =>'required|string|max:1000',
            'inspector'    =>'required|exists:users,id',
            'tape_diameter' =>'nullable|numeric',
            'wt_1' =>'nullable|numeric', 'wt_2' =>'nullable|numeric',
            'wt_3' =>'nullable|numeric', 'wt_4' =>'nullable|numeric',
            'wt_5' =>'nullable|numeric', 'wt_6' =>'nullable|numeric',
            'wt_7' =>'nullable|numeric', 'wt_8' =>'nullable|numeric',
        ];
    }
}
