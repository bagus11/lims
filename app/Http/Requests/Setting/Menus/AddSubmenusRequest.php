<?php

namespace App\Http\Requests\Setting\Menus;

use Illuminate\Foundation\Http\FormRequest;

class AddSubmenusRequest extends FormRequest
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
            'submenus_name'=>'required',
            'submenus_link'=>'required|unique:submenus,link',
            'submenus_icon'=>'required',
            'parent_id'=>'required',
        ];
    }
}
