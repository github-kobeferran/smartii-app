<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;
use App\Models\Admin;

class Member extends Pivot
{
    protected $table = 'members';

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function admin(){
        if($this->user_type == 'admin')
            return $this->belongsTo(Admin::class, 'member_id', 'id');    
        else 
            return;    
    }

}
