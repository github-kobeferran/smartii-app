<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\StudentClass;
use App\Models\Balance;
use App\Models\SubjectTaken;
use App\Models\Setting;
use App\Models\Member;
use App\Models\User;
use App\Models\Invoice;
use App\Models\PaymentRequest;
use App\Mail\WelcomeMember;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {        
                

        if($id != null){      
            
    
            if(Student::where('student_id', $id)->exists()){

                $student = Student::where('student_id', $id)->first();                            

                $appLink = $student->applicant;     
                
                $member = Member::where('member_type', 'student')->where('member_id', $student->id)->first();                
                $userLink = User::where('id', $member->user_id)->first();                
                                                            
                $student->age = $student->id;
                $student->dept = $student->department;
                $student->program_desc = $student->program_id;
                $student->balance_amount = $student->balance_id;
                $student->level_desc = $student->level;                

               return view('student.profile')
                        ->with('student', $student)
                        ->with('appLink', $appLink)                           
                        ->with('userLink', $userLink);
                                               

            }else {

                return abort(404);

            }

          
            return view('student.profile')->with('student', $student->toJson());

        } else {

            if(auth()->user()->user_type == 'student'){

                $id = auth()->user()->member->member_id;                
                
                $student = Student::where('id', $id)->first();

                $appLink = $student->applicant;  

                $member = Member::where('member_type', 'student')->where('member_id', $student->id)->first();                
                $userLink = User::where('id', $member->user_id)->first();                
                                            
                $student->age = $student->id;
                $student->dept = $student->department;
                $student->program_desc = $student->program_id;
                $student->balance_amount = $student->balance_id;
                $student->level_desc = $student->level;
                
                
                

               return view('student.profile')
                        ->with('student', $student)
                        ->with('appLink', $appLink)                           
                        ->with('userLink', $userLink);                         
                

            } else {

                return abort(404);
                
            }

        }

        return abort(404);
        
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        

        $validator = Validator::make($request->all(), [
            'department' => 'required', // 0 = shs, 1 = college
            'level' => 'required', // first_year, grade_11
            'program_id' => 'required', // 3 =>  shs, 4 => college
            'semester' => 'required', 
            'email' => 'required',
            'contact' => 'required|numeric',
            'last_name' => 'required|regex:/^[\s\w-]*$/|max:100',
            'first_name' => 'required|regex:/^[\s\w-]*$/|max:100',
            'middle_name' => 'regex:/^[\s\w-]*$/|max:100',
            'dob' => 'required|date',            
            'permanent_address' => 'max:191',
            'present_address' => 'max:191',      
        ]);
    
        if ($validator->fails()) {
            return redirect()
                            ->route('adminCreate')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('active', 'student');
        }      
        
        if($request->input('student_id') != ''){
            
            if(Student::where('student_id', $request->input('student_id'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->with('error', 'Student ID Already Exist')
                            ->with('active', 'student');
            }
        }

        if($request->input('email')){
            
            if(Student::where('email', $request->input('email'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->with('error', 'Email Already Exist')
                            ->with('active', 'student');
            }

            if(User::where('email', $request->input('email'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->with('error', 'Email Already Exist')
                            ->with('active', 'student');
            }
            
        }

        $subjects = $request->input('subjects');
        $ratings = $request->input('ratings');
        $from_years = $request->input('from_years');
        $to_years = $request->input('to_years');        
        $semesters = $request->input('semesters');  

        if(is_countable($subjects) > 0 ){

            $subjectsToBeTakenLength = count($subjects);
            $valid = true;

            for($i=0; $i < $subjectsToBeTakenLength; $i++){

                if( ( !empty($ratings[$i]) && empty($from_years[$i]) && empty($to_years[$i]) && empty($semesters[$i]) ) ||
                    ( empty($ratings[$i])  && !empty($from_years[$i]) && empty($to_years[$i]) && empty($semesters[$i]) ) ||
                    ( empty($ratings[$i]) && empty($from_years[$i]) && !empty($to_years[$i]) && empty($semesters[$i]) ) ||
                    ( empty($ratings[$i])  && empty($from_years[$i]) && empty($to_years[$i]) && !empty($semesters[$i]) )
                    ){
                    $valid = false;
                    return redirect()
                                    ->route('adminCreate')
                                    ->with('error', 'Subject Details incomplete')
                                    ->with('active', 'student');

                }

            }

            
        }
        
        $student = new Student;        

        $student->last_name = $request->input('last_name');
        $student->first_name = $request->input('first_name');
        $student->middle_name = $request->input('middle_name');
        $student->gender = $request->input('gender');
        $student->dob = $request->input('dob');
      
        $student->email = $request->input('email');
        $student->contact = $request->input('contact');

        $student->level = $request->input('level');
        $student->department = $request->input('department');
        $student->program_id = $request->input('program_id');
        $student->semester = $request->input('semester');
        $student->permanent_address = $request->input('permanent_address');
        $student->present_address = $request->input('present_address');                  

        $student->created_by_admin = '1';              
        $student->student_type = $request->input('cur_status');   
        $student->transferee = $request->input('transferee');   
        
        $status = '';
        $message = '';
        $id = 0; 
        $balanceID = 0;                 
        $department = $request->input('department');

        $user = new User;
        $password = Setting::generateRandomString();
        
        $user->name = $request->input('first_name') . ' ' . $request->input('last_name');
        $user->email = $request->input('email');
        
        $user->password = Hash::make($password);
        $user->user_type = 'student';


      
        
                               
        if($request->input('student_id') != ''){
            $student->student_id = $request->input('student_id');                         

            $balance = new Balance;             
            $balanceID = $balance->init($student);   

            $student->balance_id = $balanceID;

            if($student->save()){
                $id = $student->id;

                if($user->save()){
                    $member = new Member;

                    $member->user_id = $user->id;
                    $member->member_type = $user->user_type;
                    $member->member_id = $id;

                    $member->save();

                    Mail::to($user)->send(new WelcomeMember(ucfirst($student->first_name), $password));

                    $status ='success';
                    $message = 'Student '. ucfirst($student->first_name) . ' ' .
                     ucfirst($student->last_name) . ' has been successfully created';
                }

          
            } else {
                
                $status = 'error';
                $message.= 'Student Creation Failed';        

            }

            
        } else {               

            $student->save();
            
            $id = $student->id;

            $balance = new Balance;             
            $balanceID = $balance->init($student);

            $student->balance_id = $balanceID;
            
            $year =  date("y");
            $prefix = "C";
            $user_id = $prefix . $year . '-' . sprintf('%04d', $id);

            $student->student_id = $user_id;

            if($student->save()){

                if($user->save()){
                    $member = new Member;

                    $member->user_id = $user->id;
                    $member->member_type = $user->user_type;
                    $member->member_id = $id;

                    $member->save();

                    Mail::to($user)->send(new WelcomeMember(ucfirst($student->first_name) . ' ' . ucfirst($student->last_name), $password));

                    $status ='success';
                    $message = 'Student '. ucfirst($student->first_name) . ' ' .
                     ucfirst($student->last_name) . ' has been successfully created';
                }              

            } else {
                
                $status = 'error';
                $message.= 'Student Creation Failed';        

            }
                      
        }

         

        $totalBalance = 0;

        $studentBalance = Balance::find($balanceID);

        $subjectsToBeTakenLength = count($subjects);
        $valid = true;
                
        
        if(is_countable($subjects)){

            for($i=0; $i < $subjectsToBeTakenLength; $i++){

                $subjectToTake = new SubjectTaken;

                
                $subject = Subject::find($subjects[$i]);   
    
                $subjectToTake->student_id = $id;
                $subjectToTake->subject_id = $subject->id;                                                

                if( ($ratings[$i] != '' && $from_years[$i] == '' && $to_years[$i] == '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] != '' && $to_years[$i] == '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] != '' && $semesters[$i] == '') ||
                    ($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] != '' && $semesters[$i] != '')
                  ){
                    $valid = false;
                }
                                     
                   
                if($valid){

                    if($ratings[$i] == '' && $from_years[$i] == '' && $to_years[$i] == '' && $semesters[$i] == ''){

                        if($department == 0)
                            $totalBalance+= Setting::first()->shs_price_per_unit * $subject->units;
                        else 
                            $totalBalance+= Setting::first()->college_price_per_unit * $subject->units; 
                               
                    } else {
    
                        $totalBalance+= 0;
    
                    }
    
                    if($i == $subjectsToBeTakenLength - 1 ) {                
                        $studentBalance->amount = $totalBalance;
                        $studentBalance->save();
                    }
                    
        
                    if($ratings[$i] != '')
                        $subjectToTake->rating = $ratings[$i];
                    else 
                        $subjectToTake->rating = 4.5;
        
                    if($from_years[$i] != '')
                        $subjectToTake->from_year = $from_years[$i];
                    else 
                        $subjectToTake->from_year = Setting::first()->from_year;
        
                    if($to_years[$i] != '')
                        $subjectToTake->to_year = $to_years[$i];
                    else 
                        $subjectToTake->to_year = Setting::first()->to_year;
        
                    if($semesters[$i] != '')
                        $subjectToTake->semester = $semesters[$i];
                    else 
                        $subjectToTake->semester = Setting::first()->semester;                                  
        
                    $subjectToTake->save();

                } else {
                    $status = 'warning';
                    $message = 'Subjects not added due to missing data. Please use Add Subjects to Students to add it again.';

                    return redirect()->route('adminCreate')->with($status, $message)->with('active', 'student');
                }
                                                                                                  
            }                      
           

        } else {
            $status = 'warning';
            $message = 'Student Created with no Subjects taken';
        }
      
        return redirect()->route('adminCreate')->with($status, $message)->with('active', 'student');
                
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    public function getClasses(){
              
        $stud_id = auth()->user()->member->member_id;                            

        $student = Student::where('id',$stud_id)->first();

        $settings = Setting::first();
        // ->('from_year', $settings->from_year)
        $currentSubjectsTaken = SubjectTaken::enrolledSubjectsbyStudent($student->id);

        $currentSubjects = collect(new Subject);
        $currentSubjectsSchedule = collect([]);

        foreach($currentSubjectsTaken as $currentSubjectTaken){
            
            $subject = Subject::find($currentSubjectTaken->subject_id);

            if($currentSubjectTaken->class_id != null){
                if(Schedule::where('class_id', $currentSubjectTaken->class_id)->count() > 1){
                    

                    for($i=0; $i<Schedule::where('class_id', $currentSubjectTaken->class_id)->count(); $i++){

                        $sched[$i] = Schedule::where('class_id', $currentSubjectTaken->class_id)->get()[$i];                    

                    }
                                      
                }else{
                    $sched = Schedule::where('class_id', $currentSubjectTaken->class_id)->first();
                }
            }else{
                $sched = null;
            }         
            
            $currentSubjects->push($subject);
            $currentSubjectsSchedule->push($sched);

        }
                
        

        foreach($currentSubjectsSchedule as $sched){

            

            if(!empty($sched)){

                if(is_array($sched)){

                    for($i=0; $i<count($sched); $i++){
    
                        if(!empty($sched)){      
                                                            
                            $sched[$i]->faculty_name = $sched[$i]->id;
                            $sched[$i]->room_name = $sched[$i]->id;
                            $sched[$i]->day_name = $sched[$i]->day;
                            $sched[$i]->formatted_start = $sched[$i]->start_time;
                            $sched[$i]->formatted_until = $sched[$i]->until;
    
                        }
                    }
    

                }else{
                    $sched->faculty_name = $sched->id;
                    $sched->room_name = $sched->id;
                    $sched->day_name = $sched->day;
                    $sched->formatted_start = $sched->start_time;
                    $sched->formatted_until = $sched->until;
                }
            }            
    
                
                                
            
        }

        $allSubjectsTaken = SubjectTaken::getAllSubjectsTakenByStudent($student->id);
        $allSubjects = collect(new Subject);

        foreach($allSubjectsTaken as $subjectTaken){

            $subject = Subject::find($subjectTaken->subject_id);

            $allSubjects->push($subject);
        }
        
        
        
        $settings = Setting::first();

        $settings->sem_desc = $settings->sem;
        
        $currentSubjectsSchedule = $currentSubjectsSchedule->filter(function ($value, $key) {
            return $value != null;
        });

        

        return view('student.classes')
                        ->with('student' , $student)       
                        ->with('currentSubjects' , $currentSubjects)          
                        ->with('settings' , $settings)          
                        ->with('currentSubjectsSchedule' , $currentSubjectsSchedule)                                  
                        ->with('allSubjectsTaken' , $allSubjectsTaken)                                  
                        ->with('allSubjects' , $allSubjects->values()->all());                                   

    }

    public function getBalance(){

        $stud_id = auth()->user()->member->member_id;                

        $student = Student::where('id',$stud_id)->first();
        $student->balance_amount = $student->balance_id;

        $settings = Setting::first();        

        

        $invoices = Invoice::where('student_id', $student->id)
                           ->orderBy('created_at', 'desc')
                           ->get();

        $requests = PaymentRequest::where('student_id', $student->id)->get();        

        return view('student.balance')                                           
                        ->with('invoices' , $invoices)  
                        ->with('settings' , $settings)  
                        ->with('requests' , $requests)  
                        ->with('student' , $student);    
    }

    public function getSubjectsForNextSemester($id){

        if($id != auth()->user()->member->member_id)
            return redirect()->back();

        $student = Student::find($id);

       

        $subjects = SubjectTaken::subjectsToBeTaken($student);
        
        $lastSemStatus = collect([]);

        foreach($subjects as $subject){

            $lastSemStatus->push(Subject::PreReqChecker($subject->id, $student->id));            

        }

        $student->program_desc = $student->program_id;

        

        $level = '';
        $semester = '';

        if($student->level == 11 && $student->semester == 1){
            $level = 'Freshman Year';
            $semester = 'Second Semester';
        } elseif($student->level == 11 && $student->semester == 2){
            $level = 'Sophomore Year';
            $semester = 'First Semester';
        } elseif($student->level == 12 && $student->semester == 1){
            $level = 'Sophomore Year';
            $semester = 'Second Semester';
        } elseif($student->level == 1 && $student->semester == 1){
            $level = 'Grade 11 ';
            $semester = 'Second Semester';
        } elseif($student->level == 1 && $student->semester == 2){
            $level = 'Grade 12 ';
            $semester = 'First Semester';
        } elseif($student->level == 2 && $student->semester == 1){
            $level = 'Grade 12';
            $semester = 'Second Semester';
        } else {
            $graduate = true;
        }              
        
        return view('student.enrollmentstatus')
             ->with('student', $student)
             ->with('level', $level)
             ->with('semester', $semester)
             ->with('subjectsToTake', $subjects)
             ->with('lastSemStatus', $lastSemStatus);



    }

    public function enroll(Request $request){         
        
        if($request->method() != 'POST'){
            redirect()->back();
        }

        $settings = Setting::first();
        
        if($settings->enrollment_mode == 0){
            return redirect()->back();
        }
                
        // if($request->input('student_id') != auth()->user()->member->member_id){
        //     return redirect()->back();
        // }      

        $student = Student::find($request->input('student_id'));        

        if($student->access_grant == 1){
            return redirect()->back();
        }

        $subject_ids = $request->input('subjects');        

        $eligibles = $request->input('eligibility');

        $subjects = collect([]);        

        $counter = 0;

        foreach($subject_ids as $subj_id){
            $subject = Subject::find($subj_id);    
            
            if($eligibles[$counter] == 1){
                $subjects->push($subject);
            }          
            
            $counter++;

        }

        if($subjects->count() < 1){
            return redirect()->back()->with('warning', 'No Elligble Subjects, can\'t enroll.');
        }

        $length = count($subjects);
        $counter = 0;
        $totalBalance = 0;
        $price = 0;

        if($student->department == 0){
            $price = $settings->shs_price_per_unit;
        } else {
            $price = $settings->college_price_per_unit;
        }

        foreach($subjects as $subject){

            $totalBalance+= $price;

            $subjectToTake = new SubjectTaken;

            $subjectToTake->student_id = $student->id;            
            $subjectToTake->subject_id = $subject->id;
            $subjectToTake->rating = 4.5;
            $subjectToTake->from_year = $settings->from_year;
            $subjectToTake->to_year = $settings->to_year;
            $subjectToTake->semester = $settings->semester;
            
            
            $subjectToTake->save();

            $counter++;

        }

        $user = auth()->user();

        $user->access_grant = 1;
        $balance = Balance::find($student->balance_id);

        $balance->amount+= $totalBalance;


        if($student->level == 11 && $student->semester == 1){
            $student->level = 11;
            $student->semester = 2;
        } elseif($student->level == 11 && $student->semester == 2){
            $student->level = 12;
            $student->semester = 1;
        } elseif($student->level == 12 && $student->semester == 1){
            $student->level = 12;
            $student->semester = 2;
        } elseif($student->level == 1 && $student->semester == 1){
            $student->level = 1;
            $student->semester = 2;
        } elseif($student->level == 1 && $student->semester == 2){
            $student->level = 2;
            $student->semester = 1;
        } elseif($student->level == 2 && $student->semester == 1){
            $student->level = 2;
            $student->semester = 2;
        } else {
            $graduate = true;
        }  

        $appLink = null;

        if($student->created_by_admin == false){
            $appLink = $student->applicant;   
        }
        
            
        $balance->save();
        $user->save();
        $student->save();

        $student->age = $student->id;
        $student->dept = $student->department;
        $student->program_desc = $student->program_id;
        $student->balance_amount = $student->balance_id;
        $student->level_desc = $student->level;  

        return redirect('studentprofile/');           

    }

}
