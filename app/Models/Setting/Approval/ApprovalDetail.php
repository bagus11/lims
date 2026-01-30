<?php

namespace App\Models\Setting\Approval;

use App\Models\Transaction\Inspection\ApprovalHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    function userRelation() {
        return $this->hasOne(User::class,'id','user_id');
    }
   
}
