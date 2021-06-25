<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Program extends Model
{
    use HasFactory;

    protected $appends = ['dept_desc' => null];


    public function setDeptDescAttribute($value){

        if($value == 0){

            $this->attributes['dept_desc'] = 'Senior High School';

        } else {

            $this->attributes['dept_desc'] = 'College';

        }

    }

    public function getDeptDescAttribute(){

        return $this->attributes['dept_desc'];

    }

    // public function student()
    // {
    //     return $this->belongsToMany(Student::class);
    // }
   
}
