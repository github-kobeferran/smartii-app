<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
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

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u', 
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u', 
            'middle_name' => 'regex:/^[\pL\s\-]+$/u', 
            'dob' => 'required|date',
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


    public function availableFaculty($from, $until, $day = null){

        
        if($day != null){
         

             $faculty_id = Schedule::select('class_id')
             ->where('day', $day)
             ->where('start_time','<', $until)
             ->where('until','>', $from)                   
             ->first();
             
            
             
             if($faculty_id != null){

                $class = StudentClass::where('id', $faculty_id->class_id)
                                     ->where('archive', 0)
                                     ->first();                

                return Faculty::where('id', '!=', $class->faculty_id)->get()->toJson();
                
             } else {
                 
                return Faculty::all()->toJson();
                
             }              
 
        }else{

            return Faculty::all()->toJson();

        }
 
 
     }


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
                
                $classes->push($filtered);

            }

        }  

        $classesArray = $classes->filter(function ($value, $key) {
            return $value != null;
        });    
                
                
    
        return view('faculty.classes')
                ->with('classesByProgram', collect($classesByProgram))
                ->with('programs', $programs)
                ->with('classesArray', $classesArray);

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


}
