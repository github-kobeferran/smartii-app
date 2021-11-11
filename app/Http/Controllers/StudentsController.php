<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Student;
use App\Models\Program;
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
use App\Models\Fee;
use App\Models\Discount;
use App\Models\StudentDiscounts;
use App\Mail\WelcomeMember;
use Carbon\Carbon;
use App\Exports\StudentsExport;
use App\Exports\ActiveStudentsExport;
use App\Exports\AdvancedStudentExport;
use Maatwebsite\Excel\Facades\Excel;

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
                $student->pronoun;

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

    
    public function create()
    {
    
    
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                                    
        $before_date = Carbon::now()->subYears(15);       
        $after_date = new Carbon('1903-01-01');

        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'level' => 'required', 
            'program_id' => 'required',
            'semester' => 'required', 
            'email' => 'required',
            'gender' => 'required',
            'contact' => 'required|digits:11',
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:100',
            'middle_name' => 'regex:/^[\pL\s\-]+$/u|max:100',
            'dob' => 'required|date|before:'. $before_date->toDateString() . '|after:' . $after_date,            
            'permanent_address' => 'required|max:191',
            'present_address' => 'required|max:191',      
        ]);
    
        if ($validator->fails()) {
            return redirect()
                            ->route('adminCreate')
                            ->withInput()
                            ->withErrors($validator)                            
                            ->with('active', 'student');
        }      
        
        if($request->input('student_id') != ''){
            
            if(Student::where('student_id', $request->input('student_id'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->withInput()
                            ->with('error', 'Student ID Already Exist')
                            ->with('active', 'student');
            }
        }

        if($request->input('email')){
            
            if(Student::where('email', $request->input('email'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->withInput()
                            ->with('error', 'Email Already Exist')
                            ->with('active', 'student');
            }

            if(User::where('email', $request->input('email'))->exists()){
                return redirect()
                            ->route('adminCreate')
                            ->withInput()
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
                                    ->withInput()
                                    ->with('error', 'Subject Details incomplete')
                                    ->with('active', 'student');

                }

            }

            
        }

        $mergedDiscounts = collect(new Discount);
        $total_percentage = 0;

       if($request->filled('discount')){
            foreach($request->input('discount') as $id){
                if($id == 0)
                    return redirect()->route('adminCreate')->with('error', 'You must unselect "No Discount" option')->with('active', 'student');                            
                
                $mergedDiscounts->push(Discount::find($id));  
                
                $new_total = $total_percentage + Discount::find($id)->percentage;

                if($new_total >= 100)   
                    return redirect()->route('adminCreate')->with('error', 'Discount Percentage exceeds 100%')->with('active', 'student');
                else 
                    $total_percentage += $new_total;
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

        //student fees
        $mergedFees = Fee::getMergedFees($student->department, $student->program_id, $student->level, $student->semester);

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

                        if($student->program->is_tesda == 0){
                            if($department == 0)
                                $totalBalance+= Setting::first()->shs_price_per_unit * $subject->units;
                            else 
                                $totalBalance+= Setting::first()->college_price_per_unit * $subject->units; 
                        }
                               
                    } else {
    
                        $totalBalance+= 0;
    
                    }
    
                    if($i == $subjectsToBeTakenLength - 1 ) {    
                        
                        foreach($mergedFees as $fee){
                            $studentBalance->amount+= $fee->amount;
                        }

                        if($studentBalance->amount > 0){
                            if($mergedDiscounts->count() > 0){  
                                $total_percentage = 0;     

                                foreach($mergedDiscounts as $discount){
                                    $total_percentage+= ($discount->percentage / 100);

                                    $stud_discount = new StudentDiscounts;

                                    $stud_discount->student_id = $student->id;
                                    $stud_discount->discount_id = $discount->id;

                                    $stud_discount->save();

                                }

                                $studentBalance->amount-= ($studentBalance->amount * $total_percentage);
                            }                    
                        }

                        $studentBalance->amount+= $totalBalance;
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
        
    }

   
    public function update(Request $request)
    {
        if($request->method() != 'POST'){
            redirect()->back();
        }

        $id = auth()->user()->member->member_id;

        $student = Student::find($id);

        $validator = Validator::make($request->all(), [
            'nationality' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'civil_status' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50', 
            'religion' => 'nullable|regex:/^[\pL\s\-]+$/u|max:50',
            'contact' => 'nullable|digits:11',             
            'father_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:191',
            'mother_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:191',
            'guardian_name' => 'nullable|regex:/^[\pL\s\-]+$/u|max:191',
            'emergency_person_contact' => 'nullable|digits:11',                      
        ]);
       
        if ($validator->fails()) {
            return redirect()
                            ->route('studentProfile')
                            ->withErrors($validator);                            
                            
        }     
    
        $student->nationality = $request->input('nationality');
        $student->civil_status = $request->input('civil_status');
        $student->religion = $request->input('religion');
        $student->contact = $request->input('contact');
        $student->father_name = $request->input('father_name');
        $student->mother_name = $request->input('mother_name');
        $student->guardian_name = $request->input('guardian_name');
        $student->emergency_person_contact = $request->input('emergency_person_contact');

        $student->save();

        return redirect('/studentprofile')->with('success', 'Data Updated');

        
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
        
        $currentSubjects = $currentSubjects->filter(function ($value, $key) {
            return $value != null;
        });
        $currentSubjectsSchedule = $currentSubjectsSchedule->filter(function ($value, $key) {
            return $value != null;
        });

        
                

        return view('student.classes')
                        ->with('student' , $student)       
                        ->with('currentSubjects' , $currentSubjects)          
                        ->with('settings' , $settings)          
                        ->with('currentSubjectsSchedule' , $currentSubjectsSchedule)                                  
                        ->with('allSubjectsTaken' , $allSubjectsTaken)                                  
                        ->with('allSubjects' , $allSubjects);                                   

    }

    public function getBalance(){

        $stud_id = auth()->user()->member->member_id;                

        $student = Student::where('id',$stud_id)->first();
        $student->balance_amount = $student->balance_id;

        $settings = Setting::first();        

        

        $invoices = Invoice::where('student_id', $student->id)
                           ->orderBy('created_at', 'desc')
                           ->get();

        $requests = PaymentRequest::where('student_id', $student->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();        

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

        if($subjects == 'graduated'){
            return view('student.enrollmentstatus')->with('graduated', true);
        }
        
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
             ->with('lastSemStatus', $lastSemStatus)             
             ->with('graduated', false);

    }

    public function enroll(Request $request){         
        
        if($request->method() != 'POST'){
            redirect()->back();
        }

        $settings = Setting::first();
        
        if($settings->enrollment_mode == 0){
            return redirect()->back();
        }
                
        if($request->input('student_id') != auth()->user()->member->member_id){
            return redirect()->back();
        }

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

        //student fees
        $mergedFees = Fee::getMergedFees($student->department, $student->program_id, $student->level, $student->semester);

        foreach($mergedFees as $fee){
            $totalBalance+= $fee->amount;
        }
        

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

        $balance->amount += $totalBalance;


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

    public function allStudentsExport() 
    {        
        $semester = "";

        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester";            

        return Excel::download(new StudentsExport, 'SMARTII All-Time Students upto-A.Y.'. Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');
    }

    public function allActiveStudentsExport() 
    {        
        $semester = "";

        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester";            

        return Excel::download(new ActiveStudentsExport, 'SMARTII Active Students as of A.Y.'. Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');
    }   

    public function advancedStudentsExport($dept, $prog = 0, $level = 0) 
    {        

        $semester = "";
        $department = "";
        $program = "";
        $the_level = "";

        
        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester"; 

        if($dept == 0)      
            $department = 'SHS';
        else 
            $department  = 'College';

        if($prog)
            $program = Program::find($prog)->abbrv . ' - ' . Program::find($prog)->desc;
        else
            $program = 'All Programs';
        
        if($level){
            switch($level){
                case 1: 
                    $the_level = "Grade 11";
                    break;
                case 2: 
                    $the_level = "Grade 12";
                    break;
                case 11: 
                    $the_level = "First Year";
                    break;
                case 12: 
                    $the_level = "Second Year";
                default:
                    $the_level = "All";
                    break;
            }
        }

        return Excel::download(new AdvancedStudentExport($dept, $prog, $level), 'SMARTII '.  $the_level . ' ' . $department . ' Students of ' . $program . ' A.Y.'. Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');
    }   

}
