<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Faculty;

class Program extends Model
{
    use HasFactory;

    protected $appends = [
                            'dept_desc' => null, 
                            'student_count' => null
                        ];

    public function subjects(){
        return $this->hasMany(Subject::class);
    }

    public function faculties(){
        return Faculty::where(function($query){
                            $query->where('program_id', $this->id)
                                  ->orWhereNull('program_id');
                        })->get();
    }

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

    public function students(){

        return $this->hasMany(Student::class);

    }

    public function getStudentCountAttribute(){

        return $this->hasMany(Student::class)->count();

    }

   
}
