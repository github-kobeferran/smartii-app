<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admins';
    protected $appends = [
                            'is_trashed' => null
                         ];

    public function member(){
        return $this->hasOne(Member::class, 'member_id', 'id');
    }

    public function getIsTrashedAttribute(){
        if($this->trashed())
            return $this->attributes['is_trashed'] = true;
        else    
            return $this->attributes['is_trashed'] = false;
    }

}
