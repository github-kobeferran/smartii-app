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
        return $this->belongsTo(Admin::class, 'member_id', 'id')->withTrashed();    
    }

    public function student(){
        return $this->belongsTo(Admin::class, 'member_id', 'id')->withTrashed();    
    }

    public function faculty(){
        return $this->belongsTo(Admin::class, 'member_id', 'id');    
    }

}
