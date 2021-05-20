<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectTaken;

class SubjectsTakenController extends Controller
{
    public static function store($student_id, $subject_id){
        
        $subjectToBeTaken = new SubjectTaken;

        $subjectToBeTaken->student_id = $student_id;
        $subjectToBeTaken->subject_id = $subject_id;

        if(!$subjectToBeTaken->save()){
            SubjectTaken::abort(500, 'Error');
        }

    }
}
