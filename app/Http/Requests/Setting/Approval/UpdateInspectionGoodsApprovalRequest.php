<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateInspectionGoodsApprovalRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validation =[];
        if($request->inspection_type == 2 && $request->type == 1){
            $validation =[
                'imr_remark' =>'required',
                'approval_resin' =>'required',
            ];
        }else{
            
            $validation =[
                'approval_insepection_goods_shift_id' =>'required',
                'ig_remark' =>'required'

            ];
        }
        return $validation;
    }
}
