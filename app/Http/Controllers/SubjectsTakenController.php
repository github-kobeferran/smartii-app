<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectTaken;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Program;
use App\Models\StudentClass;
use App\Models\Schedule;
use App\Models\Faculty;
use Carbon\Carbon;

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
                        
        return $students->toJson();        
        
    }

    public function showClassSchedules($prog, $subj){

        $enrolledTakenSubjects = SubjectTaken::getEnrolledTakenSubjects();        

        $schedules = collect();
        $faculties = new Faculty;

        $subjectsTakenThatMatchesProgram = collect();
        $counter = 0;                   

        foreach($enrolledTakenSubjects as $enrolledTakenSubject){
                                    
            if($enrolledTakenSubject->subject_id == $subj){  

                if(Student::find($enrolledTakenSubject->student_id)->program_id == $prog){   

                    $subjectsTakenThatMatchesProgram->push($enrolledTakenSubject);
                  
                }

            }             

            ++$counter;

        }     
                        
        $classes = collect();

        $counter = 0;        

        for($i=0; $i<count($subjectsTakenThatMatchesProgram); $i++){ 

            $class = StudentClass::find($subjectsTakenThatMatchesProgram[$i]->class_id);
            $class->faculty_name = $class->faculty_id;     

            $class->schedules;          

            foreach($class->schedules as $sched){

                $sched->formatted_start = $sched->start_time;
                $sched->formatted_until = $sched->until;
                $sched->day_name = $sched->day;                
                $sched->room_name = $sched->id;
            }  

            $classes->push($class);
            
        }        

        // $classes = $classes->filter()->all();

        return $classes;       
        
    }

    public function updateRating(Request $request){   
        
        if($request->method() != 'POST'){
            redirect()->back();
        }
        
        $this->validate($request, [
            'rating' => 'required',         
        ]);

        
        $subjectTaken = SubjectTaken::where('class_id', $request->input('class_id'))
                                ->where('student_id', $request->input('stud_id'))->first();

        $subjectTaken->rating = $request->input('rating');

        $subjectTaken->save();

        return redirect('/myclass/' . $request->input('class_id'))
                       ->with('success', 'Rating Updated');
        

    }
   



}
