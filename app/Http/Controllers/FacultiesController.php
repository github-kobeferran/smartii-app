<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\StudentClass;
use App\Models\SubjectTaken;
use App\Models\Subject;
use App\Models\Program;
use App\Mail\WelcomeMember;
use Carbon\Carbon;
use App\Exports\ClassStudenList;
use Maatwebsite\Excel\Facades\Excel;


class FacultiesController extends Controller
{
    public function index()
    {
        return view('faculty.dashboard');
    }

    public function store(Request $request){                             
        
        $status ='';
        $msg = '';
        $id = 0;

        $before_date = Carbon::now()->subYears(15);       
        $after_date = new Carbon('1903-01-01');

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100', 
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100', 
            'middle_name' => 'regex:/^[\pL\s\-]+$/u|max:100', 
            'dob' => 'required|date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'email' => 'required',             
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminCreate')
                         ->withErrors($validator)
                         ->withInput()
                         ->with('active', 'faculty');
        }

        if(Faculty::where('email', $request->input('email'))->exists() ||
           User::where('email', $request->input('email'))->exists()){

            return redirect()->route('adminCreate')
                             ->with('error', 'Email Already Exist')
                             ->with('active', 'faculty');
                            
        }

        $faculty = new Faculty;

        $faculty->last_name = $request->input('last_name');
        $faculty->first_name = $request->input('first_name');
        $faculty->middle_name = $request->input('middle_name');
        $faculty->dob = $request->input('dob');
        $faculty->email = $request->input('email');

        if($request->input('all_program') == 0)
            $faculty->program_id = $request->input('program_id');

        $user = new User;
        $password = Setting::generateRandomString();
        
        $user->name =  $request->input('first_name') . ' ' .  $request->input('last_name');
        $user->email = $request->input('email');
        
        $user->password = Hash::make($password);
        $user->user_type = 'faculty';


        if($faculty->save()){
            $id = $faculty->id;

            $year =  date("y");
            $prefix = "B";
            $faculty_id = $prefix . $year . '-' . sprintf('%04d', $id);

            $faculty->faculty_id = $faculty_id;
            
            $faculty->save();

            if($user->save()){

                $member = new Member;

                $member->user_id = $user->id;
                $member->member_type = $user->user_type;
                $member->member_id = $id;

                $member->save();

                Mail::to($user)->send(new WelcomeMember(ucfirst($faculty->first_name) . ' ' . ucfirst($faculty->last_name), $password));

                $status ='success';
                $msg = 'Faculty '. ucfirst($user->name) . ' has been successfully created';


            } else {
                return redirect()->route('adminCreate')
                             ->with('error' , 'There\'s a problem creating this member, please try again.')
                             ->with('active', 'faculty');
            }


        } else {
            $status ='error';
            $msg = 'There\'s a problem creating this member, please try again.';
        }

        return redirect()->route('adminCreate')
                             ->with($status , $msg)
                             ->with('active', 'faculty'); 


    }


    public function show($id = null){

        $faculty = null;

        if(!empty($id))
            $faculty = Faculty::where('faculty_id', $id)->first();
        else 
           $faculty = Faculty::find(auth()->user()->member->member_id);            


        if($faculty->id != auth()->user()->member->member_id){
            return redirect()->back();
        }

        $faculty->age = $faculty->id;

        return view('faculty.show')->with('faculty', $faculty);        

    }


    public function availableFaculty($programid, $from, $until, $day = null){
        

        if($day != null){
         

             $scheds = Schedule::select('class_id')
             ->where('day', $day)
             ->where('start_time','<', $until)
             ->where('until','>', $from)                   
             ->get();

                                 
             if($scheds != null){

                $classes = collect();

                foreach($scheds as $sched){

                    $classes->push(StudentClass::where('id', $sched->class_id)
                                     ->where('archive', 0)
                                     ->first());  

                } 
                
                $classes = $classes->filter(function ($value, $key) {
                    return $value != null;
                });

                $invalids = collect();

                foreach($classes as $class){

                    $invalids->push(Faculty::find($class->faculty_id));

                }
                

                $invalids = $invalids->filter(function ($value, $key) {
                    return $value != null;
                });

                $valid = collect();

                $faculties = Faculty::where('program_id', $programid)->orWhere('program_id', null)->get();                

                // return $invalids;
                
                foreach($faculties as $faculty){

                    $bawal = false;

                    foreach($invalids as $invalid){

                        if($faculty->id == $invalid->id)
                            $bawal = true;
                        
                        // if($faculty->program_id != $programid || $faculty->program_id != null)
                        //     $bawal = true;

                    }

                    if(!$bawal)
                        $valid->push($faculty);

                }

                $valid = $valid->filter(function ($value, $key) {
                    return $value != null;
                });

                return $valid;
                
             } else {
                 
                return Faculty::whereNull('program_id')->orWhere('program_id', '=', $programid)->toJson();
                
             }              
 
        }else{

            return Faculty::all()->toJson();

        }
 
 
     }


    public function availableFacultyExcept($from, $until, $day = null, $exceptid, $programid){

        
        if($day != null){
         

             $scheds = Schedule::select('class_id')
             ->where('day', $day)
             ->where('start_time','<', $until)
             ->where('until','>', $from)                   
             ->get();
                                 
             if($scheds != null){

                $classes = collect();

                foreach($scheds as $sched){

                    $classes->push(StudentClass::where('id', $sched->class_id)
                                     ->where('archive', 0)
                                     ->first());  
                }

                $invalids = collect();               

                $classes = $classes->filter(function ($value, $key) {
                    return $value != null;
                });

                foreach($classes as $class){

                    $invalids->push(Faculty::find($class->faculty_id));

                }

                $invalids = $invalids->filter(function ($value, $key) {
                    return $value != null;
                });

                $valid = collect();           
                
                foreach(Faculty::orWhere(function($query) use($programid){
                                            $query->where('program_id', $programid)
                                                  ->orWhereNull('program_id');
                                        })->get() as $faculty){

                    $bawal = false;

                    foreach($invalids as $invalid){

                        if($faculty->id == $invalid->id)
                            $bawal = true;
                        

                        if($faculty->id == $exceptid)
                            $bawal = false;

                    }

                    if(!$bawal)
                        $valid->push($faculty);

                }

                $valid = $valid->filter(function ($value, $key) {
                    return $value != null;
                });

                return $valid;
                
             } else {
                 
                return Faculty::all()->toJson();
                
             }              
 
        }else{

            return Faculty::all()->toJson();

        }
 
 
     }

     /**
      * will return a collection of classes grouped by class name 
      * wich is then collected by their program
      */

     public function getClasses(){

        $id = auth()->user()->member->member_id;                                   

        $classesByProgram = StudentClass::getFacultyClassesByProgram($id); //base the loop here
        
        
        $programs = collect(new Program); 
        $classes = collect(new StudentClass);        

        if(is_array($classesByProgram)){

            $program_ids = array_keys($classesByProgram);

            foreach($program_ids as $id){        

                $programs->push(Program::find($id));

            }            

            foreach($classesByProgram as $classids){ 
                
                
                $classesToPush = collect(new StudentClass);

                foreach($classids as $class){

                    $class = StudentClass::find($class);

                    $class->topic = $class->id;

                    $classesToPush->push($class);

                }

                $filtered = $classesToPush->filter(function ($value, $key) {
                    return $value != null;
                });

                $grouped = $filtered->groupBy(function ($value, $key) {
                    return $value->class_name;
                });
                
                $classes->push($grouped);

            }

        }  

        $classesThisSemester = $classes->filter(function ($value, $key) {
            return $value != null;
        });                                    
    
        return view('faculty.classes')            
                ->with('classesThisSemester', $classesThisSemester);

     }

     public function getClass($id){      
         
        if(StudentClass::where('id', $id)->count() < 0){
            return redirect()->route('facultyClasses');
        }

        $class = StudentClass::find($id);

        if($class->archive == 1){
            return redirect()->back();
        }

        $class->topic = $class->id;
        $class->prog = $class->id;

        $faculty = Faculty::find(auth()->user()->member->member_id);
        

        if($class->faculty_id != $faculty->id){
            return redirect()->back();
        }

        $students = StudentClass::getStudentsbyClass($class->id)->filter(function ($value, $key) {
            return $value != null;
        });                        
        
        
        $alphabetical = $students->sortBy('last_name');
        $idAsc = $students->sortBy('student_id');        
        $schedules = Schedule::getSchedulebyClass($class->id)->sortBy('day');

        
        foreach($schedules as $sched){

            $sched->formatted_start = $sched->start_time;
            $sched->formatted_until = $sched->until;
            $sched->day_name = $sched->day;
            $sched->room_name = $sched->id;
            
        }

        foreach($alphabetical as $student){
            $student->rating = $values = ['class_id' => $class->id, 'student_id' => $student->id];
        }

        foreach($idAsc as $student){
            $student->rating = $values = ['class_id' => $class->id, 'student_id' => $student->id];
        }       

        return view('faculty.class')
        ->with('class', $class)
        ->with('students', $alphabetical)                   
        ->with('schedules', $schedules);

                     

     }

     public function showDetail($id, $detail){

        if($id != auth()->user()->member->member_id)
            return redirect()->back();

        return Faculty::select($detail . ' as detail')->where('id', $id)->first();

     }

     public function update(Request $request){

        $before_date = Carbon::now()->subYears(18);       
        $after_date = new Carbon('1903-01-01');        

        if($request->method() != 'POST'){
            return redirect()->back();
        }

        switch($request->input('detail_name')){

            case 'dob': 

                $this->validate($request, [            
                    'detail' => 'nullable|date|before:'. $before_date->toDateString() . '|after:' . $after_date->toDateString(),            
                ],[
                    'detail.date' => 'Date Format invalid, please enter a date in a yyyy-mm-dd format',
                    'detail.before' => 'Date of Birth must be before ' . $before_date->isoFormat('MMM DD YYYY'),
                    'detail.after' => 'Date of Birth must be before ' . $after_date->isoFormat('MMM DD YYYY'),
                ]);

            break;
            case 'last_name': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:100',
                ]);

            break;
            case 'first_name': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:100',                      
                ]);

            break;
            case 'middle_name': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:100',                       
                ]);

            break;
            case 'contact': 

                $this->validate($request, [            
                    'detail' => 'nullable|digits:11',                           
                ]);

            break;
            case 'gender': 

                $this->validate($request, [           
                    'detail' => 'in:male,female',                       
                ]);

            break;
            case 'civil_status': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50|in:married,single,divorced,widowed,annuled',                       
                ],[
                    'detail.in' => 'Invalid detail value. Civil Status value must either be: Single, Married, Divorced, Annuled or Widowed.'
                ]);

            break;
            case 'religion': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:25',                      
                ]);

            break;
            case 'college_alumni': 

                $this->validate($request, [            
                    'detail' => 'nullable|regex:/^[\pL\s\-]+$/u|max:100',                     
                ]);

            break;


        }         
       
        if ($validator->fails()) {
            return redirect()
                            ->route('studentProfile')
                            ->withErrors($validator);                            
                            
        }  

        


        DB::table('faculty')
              ->where('id', $request->input('faculty_id'))
              ->update([$request->input('detail_name') => $request->input('detail')]);

        return redirect()->route('facultydetails')->with('success', 'Update Successful');

    }

    public function exportClass($id){      
         
        if(StudentClass::where('id', $id)->count() < 0){
            return redirect()->route('facultyClasses');
        }

        $class = StudentClass::find($id);

        if($class->archive == 1){
            return redirect()->back();
        }

        $class->topic = $class->id;
        $class->prog = $class->id;

        $faculty = Faculty::find(auth()->user()->member->member_id);
        

        if($class->faculty_id != $faculty->id){
            return redirect()->back();
        }

        $students = StudentClass::getStudentsbyClass($class->id)->filter(function ($value, $key) {
            return $value != null;
        });                        
        
        
        $alphabetical = $students->sortBy('last_name');
        $idAsc = $students->sortBy('student_id');        
        $schedules = Schedule::getSchedulebyClass($class->id)->sortBy('day');

        
        foreach($schedules as $sched){

            $sched->formatted_start = $sched->start_time;
            $sched->formatted_until = $sched->until;
            $sched->day_name = $sched->day;
            $sched->room_name = $sched->id;
            
        }

        foreach($alphabetical as $student){
            $student->rating = $values = ['class_id' => $class->id, 'student_id' => $student->id];
        }             

        $class->topic = $class->id;

        $semester = "";

        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester";            

        return Excel::download(new ClassStudenList($alphabetical), 'SMARTII Class ' . strtoupper($class->class_name) . ' ' . Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');            

     }


}
