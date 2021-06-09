<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentClass;
use App\Models\SubjectTaken;
use App\Models\Schedule;


class StudentClassesController extends Controller
{
    

    public function store(Request $request){          
            
        $status = '';
    $msg = '';

        $noOfSched = $request->input('multi_sched');                

        if($noOfSched > 1){

        $days;
        $froms;
        $untils;
        $room_ids;
        $instructor_ids;        

        $counter = 1;

            for($i=0; $i<$noOfSched; $i++){

                if($i < 1){
                    
                    $validator = Validator::make($request->all(), [

                        'day' => 'required', 
                        'student_ids' => 'required',

                    ],
                    [
                        'student_ids.required' => 'Students are required',
                    ]);

                } else {

                    $validator = Validator::make($request->all(), [
                                                
                        'day_' . $i => 'required',                                          
                        'day_' . $i => 'required',  
                        'student_ids' => 'required',                                        
                        'instructor_id_' . $i => 'in:'. $request->input('instructor_id'),                                          

                    ],
                    [
                        'day_' .$i. '.required' => 'day ' . ($i+1) . ' is required',
                        'day_' .$i. '.required' => 'day ' . ($i+1) . ' is required',
                        'instructor_id_' . $i. '.in' => 'Must be the same Instructor for all schedules',
                        'student_ids.required' => 'Students are required',
                    ]);                   
                    
                }

            } 
            
    
            if ($validator->fails()) {
                return redirect()->route('adminClasses')
                             ->withErrors($validator)
                             ->withInput()
                             ->with('active', 'create');
            }

            for($i=0; $i<$noOfSched; $i++){ 

                if($i < 1){
                    $days[$i] = $request->input('day');
                    $froms[$i] = $request->input('from');
                    $untils[$i] = $request->input('until');
                    $room_ids[$i] = $request->input('room_id');
                    $instructor_ids[$i] = $request->input('instructor_id');
                } else {                   

                    $days[$i] = $request->input('day_' . $counter );
                    $froms[$i] = $request->input('from_' .$counter );
                    $untils[$i] = $request->input('until_' . $counter );
                    $room_ids[$i] = $request->input('room_id_' . $counter );
                    $instructor_ids[$i] = $request->input('instructor_id_' . $counter );
                    ++$counter;
                }
              
            }

            $students = $request->input('student_ids');

           /**
            *  INSERT DATA INTO CLASSES, SCHEDULES and LINK IT TO SUBJECT TAKEN
            */
            
            $class = new StudentClass;

            $class->faculty_id = $request->input('instructor_id');

            $class->save();

            $classID = $class->id;
        
            for($i=0; $i<$noOfSched; $i++){

                $schedule = new Schedule;

                $schedule->day = $days[$i];
                $schedule->from = $froms[$i];
                $schedule->until = $untils[$i];
                $schedule->room_id = $room_ids[$i];                            
                $schedule->class_id = $classID;
                
                $schedule->save();
                
            }

            $takenSubjects = SubjectTaken::pendingClasses();            

            for($i=0; $i<count($takenSubjects); $i++){

              for($j=0; $j<count($students); $j++){

                    if($takenSubjects[$i]->subject_id == $request->input('subj') 
                        && $takenSubjects[$i]->student_id == $students[$j]){
                        
                        $takenSubjects[$i]->rating = 3.5;
                        $takenSubjects[$i]->class_id = $classID;

                        $takenSubjects[$i]->save();
                                
                    }

              }
                            
            }        

            $status ="success";
            $msg ="Class Added Successfully";

            return redirect()->route('adminClasses')
                             ->with($status , $msg)
                             ->with('active', 'create');
            
            
            

        } else {
            /**
             * 
             *  ONLY ONE SCHED BLOCK
             * 
             */

            $validator = Validator::make($request->all(), [
                'day' => 'required',   
                'student_ids' => 'required',
            ],
            [
                'student_ids.required' => 'Students are required',
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('adminClasses')
                             ->withErrors($validator)
                             ->withInput()
                             ->with('active', 'create');
            }

            $students = $request->input('student_ids');

            $class = new StudentClass;

            $class->faculty_id = $request->input('instructor_id');

            $class->save();

            $classID = $class->id;

            $schedule = new Schedule;

            $schedule->day = $request->input('day');
            $schedule->start_time = $request->input('from');
            $schedule->until = $request->input('until');
            $schedule->room_id = $request->input('room_id');
            $schedule->class_id = $classID;
            
            $schedule->save();

            $takenSubjects = SubjectTaken::pendingClasses();

            for($i=0; $i<count($takenSubjects); $i++){

              for($j=0; $j<count($students); $j++){

                    if($takenSubjects[$i]->subject_id == $request->input('subj') 
                        && $takenSubjects[$i]->student_id == $students[$j]){

                            $takenSubjects[$i]->rating = 3.5;
                            $takenSubjects[$i]->class_id = $classID;

                            $takenSubjects[$i]->save();

                    }

              }
                            
            }

            $status ="success";
            $msg ="Class Added Successfully";

            return redirect()->route('adminClasses')
                             ->with($status , $msg)
                             ->with('active', 'create');
            
        
            
        }

    }
    
}
