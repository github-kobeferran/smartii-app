<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\StudentClass;
use App\Models\SubjectTaken;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Program;
use App\Models\RegistrarRequest;
use \Carbon\Carbon;
use App\Exports\StudentClassAdvancedExport;
use Maatwebsite\Excel\Facades\Excel;


class StudentClassesController extends Controller
{
    
    public function view(){

        $archivedClasses = StudentClass::where('archive', 1)
                                        ->orderBy('id', 'desc')
                                        ->get();   
                                        
        foreach($archivedClasses as $class){
            foreach($class->subjectsTaken as $subject_taken){
                foreach(RegistrarRequest::all() as $request){
                    if($request->type_id == $subject_taken->id){
                        $subject_taken->student->drop_status = $request->status;
                        $subject_taken->student->drop_request = $request;
                    }
                }
            }
        }
        

        return view('admin.classes')
             ->with('active', 'create')
             ->with('archivedClasses', $archivedClasses);             

    }

    public function viewArchived(){        
    
        $archivedClasses = StudentClass::where('archive', 1)->paginate(1);     
        $archivedClasses->withPath('/admin/classes/archived');

        return view('admin.classes')
               ->with('active', 'archived')               
               ->with('archivedClasses', $archivedClasses);
    }

    public function searchArchived($text, $faculty_id = null){

        $archivedClasses = collect();
    
        if($text != 'iamnotsearchingforanarchiveclass'){

            $archivedClasses = collect([]);           

            if(!is_null($faculty_id))
                $archivedClasses = StudentClass::where('archive', 1)->where('faculty_id', $faculty_id)->get();
            else
                $archivedClasses = StudentClass::where('archive', 1)->get();            

            $archivedClasses = $archivedClasses->filter(function($archived_class) use($text, $faculty_id){
                $valid = false;
                $text = strtolower($text);
                
                if(str_contains(strtolower($archived_class->subjectsTaken->first()->sy_and_sem), $text)){
                    $valid = true;
                }
                
                if(str_contains(strtolower($archived_class->subjectsTaken->first()->student->program->desc), $text)){
                    $valid = true;
                }

                if(str_contains(strtolower($archived_class->subjectsTaken->first()->student->program->abbrv), $text)){
                    $valid = true;
                }

                if(str_contains(strtolower($archived_class->class_name), $text)){
                    $valid = true;
                }

                if(str_contains(strtolower($archived_class->class_name), $text)){
                    $valid = true;
                }

                if(str_contains(strtolower($archived_class->subjectsTaken->first()->subject->desc), $text)){
                    $valid = true;
                }

                if(is_null($faculty_id))
                    if(str_contains(strtolower($archived_class->faculty->first_name), $text))
                        $valid = true;
                
                if(!is_null($faculty_id))
                    if(str_contains(strtolower($archived_class->faculty->last_name), $text))
                        $valid = true;
                

                return $valid == true;


            });        
                        
        } else {

            if(!is_null($faculty_id))
                $archivedClasses = StudentClass::where('archive', 1)->where('faculty_id', $faculty_id)->get();
            else
                $archivedClasses = StudentClass::where('archive', 1)->get();

        }     
                
        $sorted = $archivedClasses->sortByDesc('id');        

        foreach($sorted as $class){
            
            $class->subjectsTaken = $class->subjectsTaken->filter(function($value){
                return !is_null($value);
            });        

            $class->subjectsTaken->first()->student->program_desc = $class->subjectsTaken->first()->student->program_id;                        
          
            foreach($class->subjectsTaken as $subject_taken){
                $subject_taken->student;
                $subject_taken->student->program;
                $subject_taken->subject;
            }

            $class->faculty_name = $class->faculty_id;
            $class->schedules = $class->schedules->filter(function($value){      
                $value->start_time = Carbon::parse($value->start_time)->format('g:i A');
                $value->until = Carbon::parse($value->until)->format('g:i A');
                
                return !is_null($value);
            });   
            
                     
        }     
        
        return $sorted;
    }



    public function store(Request $request){                  
        
        $theSubject = Subject::find($request->input('subj'));

        foreach (StudentClass::all() as $class) {
            if($class->subjectsTaken()->first()->student->program->id == $request->input('prog')){
                if($class->subjectsTaken()->first()->subject_id == $theSubject->id){
                    if(str_replace(' ', '', $class->class_name) == str_replace(' ', '', $request->input('class_name')))
                        return redirect()->back()->with('error', 'There is already a class named "' . $request->input('class_name') . '" in this program and subject');
                }
            }                
        }

        $status = '';
        $msg = '';

        $noOfSched = $request->input('multi_sched');                

        if($noOfSched > 1){

        $days = [];
        $froms = [];
        $untils = [];
        $room_ids = [];
        $instructor_ids = [];   
        $proceed = true;

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
                        'student_ids.required' => 'Students are required.',
                    ]);                   
                    
                }

            } 
            
    
            if ($validator->fails()) {
                return redirect()->route('adminClasses')
                             ->withErrors($validator)
                             ->withInput()
                             ->with('active', 'create');
            }


            $students = $request->input('student_ids'); 

            $proper_level = '';            

            $sample_student = Student::find($students[0]);
            switch($sample_student->level){
                case 1:
                    $proper_level = '11';
                break;
                case 2:
                    $proper_level = '12';
                break;
                case 11:
                    $proper_level = '1';
                break;
                case 12:
                    $proper_level = '2';
                break;

            }

            $validator = Validator::make($request->all(), [
                'class_name' => 'required|regex:/^'. $sample_student->program->abbrv . ' ' . $proper_level. '-[A-G]{1}[1-9]{1}$/',                     
            ],
            [
                'class_name.regex' => 'Class Name pattern is wrong, it must be exactly "' . $sample_student->program->abbrv . '<space>'. $proper_level . '-" followed by a section indicator that requires one capital letter from A-G and one letter from 1-9. (ex. ' . $sample_student->program->abbrv . ' ' . $proper_level . '-A1)' ,
            ]);

            if ($validator->fails())
                return redirect()->route('adminClasses')->withInput()->withErrors($validator)->with('active', 'create');

            for($i=0; $i<$noOfSched; $i++){ 

                if($i == 0){

                    array_push($days, $request->input('day'));
                    array_push($froms, $request->input('from'));
                    array_push($untils, $request->input('until'));
                    array_push($room_ids, $request->input('room_id'));
                    array_push($instructor_ids, $request->input('instructor_id'));

                   
                } else {                                       

                    if(end($days) == $request->input('day_' . $counter )){
                        $proceed = false;
                        $msg = "Day can't be the same on separate schedule.";
                        $status = 'warning';
                        break;
                    }                
                    
                    array_push($days, $request->input('day_' . $counter));
                    array_push($froms, $request->input('from_' . $counter));
                    array_push($untils, $request->input('until_' . $counter));
                    array_push($room_ids, $request->input('room_id_' . $counter));
                    array_push($instructor_ids, $request->input('instructor_id_' . $counter));

                    ++$counter;
                }

                if(!$proceed)
                    break;
              
            }                          

            if($proceed){

                $totalDuration = 0;          

                for($i = 0; $i<$noOfSched; $i++){
                     
                    $from_time = Carbon::parse($froms[$i]);
                    $until_time = Carbon::parse($untils[$i]);
    
                    $totalDuration+= $until_time->diffInHours($from_time);
    
                } 
                
                if(!Program::find($request->input('prog'))->is_tesda){
                    if($totalDuration > $theSubject->units){
                        return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is above the units of the subject. '. $theSubject->desc . ' has only ' . $theSubject->units . ' units.');
                    }elseif($totalDuration < $theSubject->units){
                        return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is below the units of the subject. '. $theSubject->desc . ' has ' . $theSubject->units . ' units.');
                    }
                }
                                   
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
                'student_ids.required' => 'Students are required.',
            ]);
    
            if ($validator->fails()) {
                return redirect()->route('adminClasses')
                             ->withErrors($validator)
                             ->withInput()
                             ->with('active', 'create');
            }

            $students = $request->input('student_ids');


            $proper_level = '';            

            $sample_student = Student::find($students[0]);
            switch($sample_student->level){
                case 1:
                    $proper_level = '11';
                break;
                case 2:
                    $proper_level = '12';
                break;
                case 11:
                    $proper_level = '1';
                break;
                case 12:
                    $proper_level = '2';
                break;

            }

            $validator = Validator::make($request->all(), [
                'class_name' => 'required|regex:/^'. $sample_student->program->abbrv . ' ' . $proper_level. '-[A-G]{1}[1-9]{1}$/',                     
            ],
            [
                'class_name.regex' => 'Class Name pattern is wrong, it must be exactly "' . $sample_student->program->abbrv . '<space>'. $proper_level . '-" followed by a section indicator that requires one capital letter from A-G and one letter from 1-9. (ex.' . $sample_student->program->abbrv . ' ' . $proper_level . '-A1)',
            ]);

            if ($validator->fails())
                return redirect()->route('adminClasses')->withInput()->withErrors($validator)->with('active', 'create');

            $from_time = Carbon::parse(($request->input('from')));
            $until_time = Carbon::parse(($request->input('until')));            

            $totalDuration = $until_time->diffInHours($from_time);

           
            
            if($from_time->lt($until_time->subMinutes(59))){
                if(!Program::find($request->input('prog'))->is_tesda){
                    if($totalDuration > $theSubject->units){
                        return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is above the units of the subject. '. $theSubject->desc . ' has only ' . $theSubject->units . ' units.');
                    }elseif($totalDuration < $theSubject->units){
                        return redirect()->route('adminClasses')->with('warning', 'Submission failed, schedule total hours is below the units of the subject. '. $theSubject->desc . ' has ' . $theSubject->units . ' units.');
                    }
                } else {
                    if($totalDuration < 1)
                        return redirect()->route('adminClasses')->with('warning', 'TESDA Courses are required atleast 1 hour per session ');
                }
                
            } else {
                return redirect()->route('adminClasses')->with('warning', 'Until Time must be greater than From Time.');
            }
            

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

    public function updateSchedule(Request $request){        

        if($request->method() != 'POST'){
            return redirect()->back();
        }         
        
        $valid = true;

        $sched = Schedule::find($request->input('sched_id'));
        $class = StudentClass::find($request->input('class_id'));                

        $otherScheds = Schedule::where('id', '!=', $request->input('sched_id'))
                               ->where('class_id', $request->input('class_id'))->get();

        $otherSchedHours = 0;
                               
                
        if(count($otherScheds) > 0){
            foreach($otherScheds as $otherSched){                            

                $otherSchedHours+= Carbon::parse($otherSched->until)->diffInHours(Carbon::parse($otherSched->start_time));

                if($otherSched->day == $request->input('day')){
                    $valid = false;                
                    $status = 'error';
                    $msg = 'Day must not duplicate in a class';                    
                }

                if(!$valid)
                    break;
            }
        }

        $proper_level = '';            

        $sample_student = $class->subjectsTaken->first()->student;
        switch($sample_student->level){
            case 1:
                $proper_level = '11';
            break;
            case 2:
                $proper_level = '12';
            break;
            case 11:
                $proper_level = '1';
            break;
            case 12:
                $proper_level = '2';
            break;

        }

        $validator = Validator::make($request->all(), [
            'class_name' => 'required|unique:classes,class_name,' . $request->input('class_id') . '|regex:/^'. $sample_student->program->abbrv . ' ' . $proper_level. '-[A-G]{1}[1-9]{1}$/',
        ],[
            'class_name.unique' => 'The class name had already been taken.',
            'class_name.regex' => 'Class Name pattern is wrong, it must be exactly "' . $sample_student->program->abbrv . '<space>'. $proper_level . '-" followed by a section indicator that requires one capital letter from A-G and one letter from 1-9. (ex.' . $sample_student->program->abbrv . ' ' . $proper_level . '-A1)',
        ]);

        if ($validator->fails()) 
            return redirect()->route('adminClasses')->withErrors($validator)->withInput()->with('active','view');

        $class->faculty_id = $request->input('instructor');
        $class->class_name = $request->input('class_name');

        $sched->day = $request->input('day');

        $until_time = Carbon::parse($request->input('until'));
        $from_time = Carbon::parse($request->input('from'));

        $inputHours = $until_time->diffInHours($from_time);                

        $subject = $class->subjectsTaken->first()->subject;         
        $program = $class->subjectsTaken->first()->student->program;                 

        if($from_time->lt($until_time->subMinutes(59))){
            if(!$program->is_tesda){
                if(($inputHours + $otherSchedHours) > $subject->units ){
    
                    $status = 'warning';
                    $msg = 'Updated failed, schedule total hours is above the units of the subject. '. $subject->desc . ' has only ' . $subject->units . ' units.';
        
                    $valid = false;
                    
                }
                
                if(($inputHours + $otherSchedHours) < $subject->units ){
                    
                    $status = 'warning';
                    $msg = 'Updated failed, schedule total hours is below the units of the subject. '. $subject->desc . ' has ' . $subject->units . ' units.';
                    
                    $valid = false;
                }
            } else {
                if(($inputHours + $otherSchedHours) < 1){
                    return redirect()->route('adminClasses')->with('warning', 'TESDA Courses are required atleast 1 hour per session ')->with('active', 'view');
                }
            }
        } else {
            return redirect()->route('adminClasses')->with('warning', 'Until Time must be greater than From Time')->with('active', 'view');
        }

       
        $sched->start_time = $request->input('from');
        $sched->until = $request->input('until');


        $sched->room_id = $request->input('room');

        if($valid){

            $sched->save();
            $class->save();

            $status = 'success';
            $msg = 'Schedule Updated';

        }

        return redirect()->route('adminClasses')->with($status, $msg)->with('active', 'view');
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

        foreach($students as $student){            
            foreach($student->subject_taken as $subject_taken){
                foreach(RegistrarRequest::all() as $request){
                    if($request->type_id == $subject_taken->id){
                        if($request->status != 0)
                            $request->admin;
                        if($request->type == 'drop'){
                            $student->drop_status = $request->status;
                            $student->drop_request = $request;
                        } else if ($request->type == 'rating'){                                                        
                            $subject_taken->request_rating_update = $request;
                        }
                            
                    }
                }

                $subject_taken->class;
            }                   
        }
        
                
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

    public function countClasses($prog, $subj){        
        $classes = StudentClass::where('archive', 0)->get();

        $classes = $classes->filter(function($class)use($subj){
            return $class->subjectsTaken()->first()->subject_id == $subj;
        });

        $classes = $classes->filter(function($class)use($prog){
            return $class->subjectsTaken()->first()->student->program->id == $prog;
        });

        return $classes->count();

    }

    public function advanceExport($from_year, $to_year, $dept, $prog, $level, $sem, $faculty, $subj, $ac){                                        

        $level_desc = "";
        switch($level){
            case 1:
                $level_desc = "Grade 11";
                break;
            case 2:
                $level_desc = "Grade 11";
                break;
            case 11:
                $level_desc = "First Year";
                break;
            case 12:
                $level_desc = "Second Year";
                break;
        }

        return Excel::download(new StudentClassAdvancedExport($from_year, $to_year, $dept, $prog, $level, $sem, $faculty, $subj, $ac), 'SMARTII Classes A. Y' . $from_year . '-'. $to_year . '.xlsx');

    }
}
