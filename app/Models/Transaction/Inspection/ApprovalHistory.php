<?php

namespace App\Models\Transaction\Inspection;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;
    protected $guarded = [];
    function userRelation() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
