<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;

class Member extends Pivot
{
    protected $table = 'members';

    public function user(){
        return $this->belongsTo(User::class);
    }
}
