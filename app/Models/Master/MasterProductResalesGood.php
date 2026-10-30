<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProductResalesGood extends Model
{
    use HasFactory;

    protected $table = 'master_product_resales_goods';

    protected $guarded = [];

    protected $casts = [
        'spesification' => 'array',
    ];
}
