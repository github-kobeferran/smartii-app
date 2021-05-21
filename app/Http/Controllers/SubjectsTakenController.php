<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectTaken;

class SubjectsTakenController extends Controller
{
    public static function store($values = [], $classID){
        
        $subjectToBeTaken = new SubjectTaken;        

        $subjectToBeTaken->student_id = $values['student_id'];
        $subjectToBeTaken->subject_id = $values['subject_id'];
        $subjectToBeTaken->rating = $values['rating'];
        $subjectToBeTaken->from_year = $values['from_year'];
        $subjectToBeTaken->to_year = $values['to_year'];
        $subjectToBeTaken->semester = $values['semester'];
        $subjectToBeTaken->class_id = $classID;
        

        if(!$subjectToBeTaken->save()){
            return true;
        } else {
            return false;
        }

    }
}
