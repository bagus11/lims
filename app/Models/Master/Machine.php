<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $guarded = [];

    function categoryRelation() {
        return $this->hasOne(CategoryMachine::class,'id', 'category_id');
    }
}
