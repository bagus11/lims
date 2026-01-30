<?php

namespace App\Http\Requests\Setting\Menus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenusRequest extends FormRequest
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
            'name_edit' => 'required',
            // 'link_edit' => 'required|unique:menus,link',
            'type_edit' => 'required',
            'icon_edit' => 'required',
        ];
    }
}
