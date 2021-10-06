<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\StudentClass;
use App\Models\SubjectTaken;
use App\Models\Faculty;
use App\Models\Schedule;
use App\Models\Subject;
use \Carbon\Carbon;


class StudentClassesController extends Controller
{
    

    public function store(Request $request){ 
        
        $theSubject = Subject::find($request->input('subj'));

        $status = '';
        $msg = '';

        $noOfSched = $request->input('multi_sched');                

        if($noOfSched > 1){

        $days;
        $froms;
        $untils;
        $room_ids;
        $instructor_ids;   
        $proceed = false;

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
                        'instructor_id_' . $i => 'required|in:'. $request->input('instructor_id'),                                          

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

                    if($days[--$i] == $request->input('day_' . $counter )){
                        $proceed = false;
                        $msg = "Day can't be the same on separate schedule.";
                        $status = 'warning';
                        break;
                    }
                        

                    $days[$i] = $request->input('day_' . $counter );
                    $froms[$i] = $request->input('from_' .$counter );
                    $untils[$i] = $request->input('until_' . $counter );
                    $room_ids[$i] = $request->input('room_id_' . $counter );
                    $instructor_ids[$i] = $request->input('instructor_id_' . $counter );
                    ++$counter;
                }

                if($proceed)
                    break;
              
            }

           

            $totalDuration = 0; 

             for($i = 0; $i<$noOfSched; $i++){

                if($proceed)
                    break;

                $from_time = Carbon::parse($froms[$i]);
                $until_time = Carbon::parse($untils[$i]);

                $totalDuration+= $until_time->diffInHours($from_time);

            }            

            if($totalDuration > $theSubject->units){
                return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is above the units of the subject. '. $theSubject->desc . ' has only ' . $theSubject->units . ' units.');
            }elseif($totalDuration < $theSubject->units){
                return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is below the units of the subject. '. $theSubject->desc . ' has ' . $theSubject->units . ' units.');
            }

            $students = $request->input('student_ids');

           /**
            *  INSERT DATA INTO CLASSES, SCHEDULES and LINK IT TO SUBJECT TAKEN
            */
            
            $class = new StudentClass;

            $class->faculty_id = $request->input('instructor_id');
            $class->class_name = $request->input('class_name');                            

            $class->save();

            $classID = $class->id;
        
            for($i=0; $i<$noOfSched; $i++){

                $schedule = new Schedule;

                $schedule->day = $days[$i];
                $schedule->start_time = $froms[$i];
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

            if(!$proceed){
                $status ="success";
                $msg ="Class Added Successfully";

                return redirect()->route('adminClasses')
                                 ->with($status , $msg)
                                 ->with('active', 'create');
            }else{
                return redirect()->route('adminClasses')
                                 ->with($status , $msg)
                                 ->with('active', 'create');
            }

           
            
            
            

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

            $from_time = Carbon::parse(($request->input('from')));
            $until_time = Carbon::parse(($request->input('until')));

            $totalDuration = $until_time->diffInHours($from_time);

            if($totalDuration > $theSubject->units){
                return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is  above the units of the subject. '. $theSubject->desc . ' has only ' . $theSubject->units . ' units.');
            }elseif($totalDuration < $theSubject->units){
                return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is below the units of the subject. '. $theSubject->desc . ' has ' . $theSubject->units . ' units.');
            }

            $students = $request->input('student_ids');

            $class = new StudentClass;

            $class->faculty_id = $request->input('instructor_id');
            $class->class_name = $request->input('class_name');                            

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
                             ->withInput()
                             ->with($status , $msg)
                             ->with('active', 'create');
            
        
            
        }

    }

    public static function sortStudents($classid, $facultyid, $sortby){

        $class = StudentClass::find($classid);
        $faculty = Faculty::find($facultyid);

        if($class->faculty_id != $faculty->id){
            return redirect()->back();
        }


        $students = StudentClass::getStudentsbyClass($class->id)->filter(function ($value, $key) {
            return $value != null;
        });
                
        

        $alphabetical = $students->sortBy('last_name');
        $idAsc = $students->sortBy('student_id');        

        foreach($alphabetical as $student){
            $student->rating = $values = ['class_id' => $class->id, 'student_id' => $student->id];
        }

        foreach($idAsc as $student){
            $student->rating = $values = ['class_id' => $class->id, 'student_id' => $student->id];
        }

        $rating =  $alphabetical->sortBy('rating');
        

        switch($sortby){
            case 'id':
                return $idAsc->values()->all();;
            break;

            case 'alpha':
                return $alphabetical->values()->all();
            break;
            case 'rating':
                return $rating->values()->all();
            break;

            default:
                return $alphabetical->values()->all();;
        }       

    }

    public function archiveClass(Request $request){

        if($request->method() != 'POST'){
            return redirect()->back();
        }

        $class = StudentClass::find($request->input('class_id'));

        if($class->faculty_id != $request->input('faculty_id')){
            return redirect()->back();
        }

        $class->archive = 1;
        $class->save();

        DB::table('schedules')->delete(['class_id' => $class->id]);


        return redirect()->route('facultyClasses');

    }
    
}
