<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMasterProduct extends Model
{
    use HasFactory;
    protected $table = 'log_master_product';
    protected $guarded = [];

    function userRelation() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
