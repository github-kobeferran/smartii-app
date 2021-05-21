<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $attributes = ['program_desc' => ''];

    public function subject_taken(){
        return $this->hasMany(SubjectTaken::class, 'subjects_taken', );
    }  
    
    public function getProgramDescAttribute()
    {
        return $this->attributes['program_desc'];
    }

    public function setProgramDescAttribute($value)
    {
        $this->attributes['program_desc'] = $value;
    }

}
