<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;
use App\Models\Faculty;

class Post extends Model
{
    use HasFactory;

    protected $appends = ['author_name' => null];

    public function setAuthorNameAttribute($values = []){

        switch($values['member_type']){
            case 'admin':

                $admin = Admin::find($values['member_id']);

                $this->attributes['author_name'] = $admin->name;

            break;
            case 'faculty':

                $facutly = Faculty::find($values['member_id']);

                $this->attributes['author_name'] = $facutly->first_name . ' ' . $facutly->last_name;

            break;
        }

    }

    public function getAuthorNameAttribute(){
        return $this->attributes['author_name'];
    }

}
