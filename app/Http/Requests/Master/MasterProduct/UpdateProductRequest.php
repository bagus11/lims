<?php

namespace App\Http\Requests\Master\MasterProduct;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            // 'detail_berat_pcs' =>'required',
            // 'detail_berat' =>'required',
            // 'detail_formula' =>'required',
            // 'detail_min_od' =>'required',
            // 'detail_toleransi_od' =>'required',
            // 'detail_average_od' =>'required',
            // 'detail_max_od' =>'required',
            // 'detail_min_wt' =>'required',
            // 'detail_toleransi_wt' =>'required',
            // 'detail_average_wt' =>'required',
            // 'detail_max_wt' =>'required',
        ];
    }
}
