<?php

namespace App\Http\Requests\Master\ResalesGoods;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResalesGoodsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->canManageModule('view-master_product_resales_goods');
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'size' => 'nullable',
            'uom' => 'nullable|string|max:50',
            // Drawing disimpan sebagai data URI; batasi ke gambar supaya isi
            // kolom ini tidak bisa dipakai menyuntik markup ke report.
            'drawing' => ['nullable', 'string', 'max:4000000', 'regex:/^data:image\/(png|jpe?g|gif|webp);base64,[A-Za-z0-9+\/=\s]+$/'],
            'spesification' => 'nullable',
        ];
    }
}
