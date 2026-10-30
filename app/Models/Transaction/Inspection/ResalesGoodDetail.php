<?php

namespace App\Models\Transaction\Inspection;

use App\Models\Master\MasterProductResalesGood;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResalesGoodDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'resales_goods_detail';
    protected $casts = [
        'spesification' => 'array',
    ];

    function headerRelation() {
        return $this->hasOne(ResalesGood::class, 'transaction_code', 'transaction_code');
    }
    function productRelation() {
        return $this->hasOne(MasterProductResalesGood::class, 'id', 'product_id');
    }
    function createdByRelation() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
