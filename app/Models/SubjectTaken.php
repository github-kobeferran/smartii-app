<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class SubjectTaken extends Model
{

    protected $table = 'subjects_taken';

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function store($student_id, $subject_id){
        $subjectToBeTaken = new SubjectTaken;

        $subjectToBeTaken->student_id = $student_id;
        $subjectToBeTaken->subject_id = $subject_id;

        if(!$subjectToBeTaken->save()){
            SubjectTaken::abort(500, 'Error');
        }

    }

}
