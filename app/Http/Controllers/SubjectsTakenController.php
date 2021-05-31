<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectTaken;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Program;

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

    public function pendingStudentClass($dept, $prog, $subj){             
    
        $students = new Student;        
        $programs = new Program;

        $pendingClasses = SubjectTaken::pendingClasses();

        for($i=0; $i<count($pendingClasses); $i++){
            $student = new Student;
            if($pendingClasses[$i]->subject_id == $subj) 
                $student = Student::find($pendingClasses[$i]->student_id);
                if($student->program_id == $prog)
                    $students[$i] = $student;

        }
                        
        return $students;

        // return $result = ['students' => $students->toJson(), 'programs' => $programs->toJson()];
        
    }



}
