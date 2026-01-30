<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenus extends Model
{
    use HasFactory;
    protected $table = 'submenus';
    protected $guarded = [];
    
    function menusRelation() {
        return $this->hasOne(Menus::class, 'id','id_menu');
    }
}
