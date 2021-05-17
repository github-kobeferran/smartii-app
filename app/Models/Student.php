<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class, 'subjects_taken', );
    }    

}
