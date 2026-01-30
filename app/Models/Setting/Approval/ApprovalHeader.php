<?php

namespace App\Models\Setting\Approval;

use App\Models\Master\MasterLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHeader extends Model
{
    use HasFactory;
    protected $guarded = [];

    function locationRelation() {
        return $this->hasOne(MasterLocation::class,'id','location');
    }

}
