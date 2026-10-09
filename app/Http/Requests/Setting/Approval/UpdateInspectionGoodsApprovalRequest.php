<?php

namespace App\Http\Requests\Setting\Approval;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateInspectionGoodsApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     * Decision: 1 = Approve, 2 = Reject.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $validation =[];
        if($request->inspection_type == 2 && $request->type == 1){
            $validation =[
                'transaction_code' =>'required|string',
                'imr_remark' =>'required|string',
                'approval_resin' =>['required', Rule::in([1, 2])],
            ];
        }else if($request->inspection_type == 2 && $request->type == 2){
              $validation =[
                'imr_pellet_remark' =>'required|string',
                'approval_pellet' =>['required', Rule::in([1, 2])],
                'label_pellet_inspection_code' =>'required|string',
            ];
        }else{
            $validation =[
                'transaction_code' =>'required|string',
                'approval_insepection_goods_shift_id' =>['required', Rule::in([1, 2])],
                'ig_remark' =>'required|string',
            ];
        }
        return $validation;
    }
}
