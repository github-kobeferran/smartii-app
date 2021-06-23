<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Admin;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Applicant;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Setting;
use App\Models\Balance;
use App\Models\User;
use App\Models\Member;
use App\Models\Room;
use App\Models\SubjectTaken;
use App\Models\PaymentRequest;
use App\Models\Invoice;
use App\Models\Schedule;
use App\Mail\WelcomeMember;
use App\Mail\ApprovedApplicant;
use PDF;



class AdminsController extends Controller
{    
    
    public function index(){            

        if(!empty(Setting::first())){
            return view('admin.dashboard');
        } else {
            $setting = new Setting;

            $year = date('Y');

            $setting->from_year = $year;
            $setting->to_year = ++$year;
            $setting->semester = 1;
            $setting->shs_price_per_unit = 0;
            $setting->college_price_per_unit = 300;
            $setting->class_quantity = 25;
            $setting->gcash_number = 'N/A';
            $setting->bank_number = 'N/A';
            $setting->bank_name = 'N/A';

            $setting->save();

            return view('admin.dashboard');            
        }
    }

    public function adminCreate(){
        return view('admin.create')->with('empty', 'active');
    }

    public function adminView(){
        return view('admin.view');
    }

    public function adminPayment(){
        return view('admin.payment');
    }

    public function adminSettings(){
        return view('admin.settings');
    }

    public function adminClasses(){
        return view('admin.classes')->with('create', true);
    }

    public function store(Request $request){

        $status ='';
        $msg = '';
        $id = 0;

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[\s\w-]*$/', 
            'email' => 'required', 
            'address' => 'required|max:100', 
            'contact' => 'required|numeric',
            'position' => 'required',             
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminPayment')
                         ->withErrors($validator)
                         ->withInput();                         
        }

        if(Admin::where('email', $request->input('email'))->exists() ||
           User::where('email', $request->input('email'))->exists()){

            return redirect()->route('adminCreate')
                             ->with('error', 'Email Already Exist')
                             ->with('active', 'admin');
                            
        }

        $admin = new Admin;     

        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->address = $request->input('address');
        $admin->contact = $request->input('contact');
        $admin->position = $request->input('position');

        // create user based on the new admin
        $user = new User;
        $password = Setting::generateRandomString();
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        
        $user->password = Hash::make($password);
        $user->user_type = 'admin';
                    
        
        if($admin->save()){
            $id = $admin->id;

            $year =  date("y");
            $prefix = "A";
            $admin_id = $prefix . $year . '-' . sprintf('%04d', $id);

            $admin->admin_id = $admin_id;
            
            $admin->save();

            if($user->save()){

                $member = new Member;

                $member->user_id = $user->id;
                $member->member_type = $user->user_type;
                $member->member_id = $id;

                $member->save();

                Mail::to($user)->send(new WelcomeMember($admin->name, $password));

                $status ='success';
                $msg = 'Admin '. ucfirst($user->name) . ' has been successfully created';


            } else {
                return redirect()->route('adminCreate')
                             ->with('error' , 'There\'s a problem creating this member, please try again.')
                             ->with('active', 'admin');
            }


        } else {
            $status ='error';
            $msg = 'There\'s a problem creating this member, please try again.';
        }

        return redirect()->route('adminCreate')
                             ->with($status, $msg)
                             ->with('active', 'admin');

          
                
    }
    


    public function showTable($table){
        switch($table){
            case 'admins':

                $admins = Admin::orderBy('created_at', 'desc')->get();
                return $admins->toJson();
            break;        
            case 'students':

                $students = Student::orderBy('created_at', 'desc')->get();                              

                foreach($students as $student){                    
                    $student->program_desc = $student->program_id;
                    $student->balance_amount = $student->balance_id;
                }
                    
                return $students->toJson();
            break;        
            case 'rooms':

                $rooms = Room::all();        

            return $rooms->toJson();
            break;        
            case 'faculty':

                $faculty = Faculty::all();        

                return $faculty->toJson();
            break;    
            case 'applicants':

                $applicants = Applicant::where('approved', 0)
                                        ->orderBy('created_at', 'desc')->get();  
                
                foreach($applicants as $applicant){                    
                    $applicant->prog_desc = $applicant->id;
                    $applicant->days_ago = $applicant->id;
                    $applicant->dept_desc = $applicant->id;
                }

                return $applicants->toJson();
            break;    
            case 'paymentrequests':

                $paymentrequests = PaymentRequest::whereNull('admin_id')
                                        ->orderBy('created_at', 'asc')
                                        ->get();  
                
                foreach($paymentrequests as $paymentrequest){                    
                    
                    $paymentrequest->stud_id = $paymentrequest->student_id;
                    $paymentrequest->stud_name = $paymentrequest->student_id;
                    $paymentrequest->stud_dept = $paymentrequest->student_id;
                    $paymentrequest->stud_prog = $paymentrequest->student_id;
                    $paymentrequest->stud_address = $paymentrequest->student_id;
                    $paymentrequest->time_ago = $paymentrequest->created_at;

                }

                return $paymentrequests->toJson();
            break;    

            default:
            redirect('/home');
        }
        
    }

    public function showData($table, $id){
        switch($table){
            case 'admins':
                $admins = Admin::find($id);

                return $admins->toJson();
            break;        
            case 'students':
                $student = Student::find($id);               
    
                $student->program_desc = $student->program_id;
                $student->balance_amount = $student->balance_id;                                
                    
                return $student->toJson();

            break; 
            case 'applicants':

                $applicant = Applicant::find($id);  
                
                           
                $applicant->prog_desc = $applicant->id;
                $applicant->days_ago = $applicant->id;
                $applicant->dept_desc = $applicant->id;
                $applicant->age = $applicant->id;
                

                return $applicant->toJson();
            break;           
            case 'paymentrequests':

                $paymentrequest = PaymentRequest::find($id);  
                
                           
                $paymentrequest->stud_id = $paymentrequest->student_id;
                $paymentrequest->stud_name = $paymentrequest->student_id;
                $paymentrequest->stud_dept = $paymentrequest->student_id;
                $paymentrequest->stud_prog = $paymentrequest->student_id;
                $paymentrequest->stud_address = $paymentrequest->student_id;
                $paymentrequest->time_ago = $paymentrequest->created_at;
                

                return $paymentrequest->toJson();
            break;           
            case 'schedule':

                $schedule = Schedule::find($id);  
                
                           
                $schedule->formatted_start = $schedule->start_time;
                $schedule->formatted_until = $schedule->start_time;
                $schedule->day_name = $schedule->day;
                $schedule->room_name = $schedule->id;
                

                return $schedule;
            break;           
            default:
            redirect('/home');
        }
        
    }


    public function showTableBy($table, $by, $value, $all = null){
        if($by == ''){

            showTable($table);

        } else {

            switch($table){
                case 'programs':                                      

                    if($all){
                        $programs = Program::where($by, $value)->get();
                    } else {
                        $programs = Program::where($by, $value)
                                           ->where('id', '!=', 3)
                                           ->where('id', '!=', 4)
                                           ->get();
                    }
                    

                    return $programs->toJson();
                break; 
                case 'students':
                    
                    $students = Student::where($by, $value)->get();

                    foreach($students as $student){
                        $student->age = $student->id;
                    }

                    return $students->toJson();

                break;
                case 'applicants':
                    
                    $applicants = Applicant::where($by, $value)
                                           ->where('approved', 0)
                                           ->get();

                    foreach($applicants as $applicant){
                        $applicant->dept_desc = $applicant->id;
                        $applicant->prog_desc = $applicant->id;
                        $applicant->days_ago = $applicant->id;
                    }

                    return $applicants->toJson();

                break;
                case 'invoices':
                    
                    $invoices = Invoice::where($by, $value)                                           
                                           ->get();

                    foreach($invoices as $invoice){
                        $invoice->stud_name = $invoice->student_id;
                        $invoice->formatted_date = $invoice->created_at;
                    }
               
                    return $invoices->toJson();

                break;

                default:
                redirect('/home');
            }
        }
    }

    public function showTableByTwo($table, $firstColumn, $firstValue, $secondColumn, $secondValue){
        switch($table){
            case 'subjects':                    
                $values = [$firstColumn => $firstValue,
                           $secondColumn => $secondValue];                
                        
                return Subject::subjectsForClasses($values)->toJson();                               
            }
    }    

    public function showTableByFour($table, $firstColumn, $firstValue, $secondColumn, $secondValue,
                                    $thirdColumn, $thirdValue, $fourthColumn, $fourthValue){                                   
                               
            switch($table){
                case 'subjects':                    
                    $values = [$firstColumn => $firstValue,
                               $secondColumn => $secondValue,
                               $thirdColumn => $thirdValue,
                               $fourthColumn => $fourthValue];

                    
                    $subjects = Subject::allWhere($values, true);
                    $subjects->toJson();                    
                    $programs;
                    $pre_reqs;
                                        
                    
                    $count = 0;
                    foreach($subjects as $subject){                        
                        $subCount = 0;                        
                        $programs[$count] = Program::find($subject->program_id);

                        if(count($subject->pre_reqs)){

                            foreach($subject->pre_reqs as $pre_req){
                                $pre_reqs[$count][$subCount] = $pre_req;
                                $subCount++;
                            }
                                
                        } else{
                            $pre_reqs[$count] = null;
                            $subCount++;
                        }
                        
                        $count++;
                    }
                    
                    $results = ['subjects' => $subjects, 'pre_reqs' => $pre_reqs, 'programs' => $programs];
                    
                    return $results;
                break;   
                case 'prereqs':
                    $values = [$firstColumn => $firstValue,
                               $secondColumn => $secondValue,
                               $thirdColumn => $thirdValue,
                               $fourthColumn => $fourthValue];

                    $subjects = Subject::getPossiblePreReq($values);
                    
                    return $subjects->toJson();
                    
                break;

                default:
                redirect('/home');
            }
        
    }

    public function search($table, $text = null, $dept = null){
        
        switch($table){     

            case 'admins':  

                if($text == null){

                    return Admin::all();                    

                }else{

                    return Admin::query()
                    ->where('name', 'LIKE',  $text . "%")
                    ->orWhere('email', 'LIKE',  $text . "%")
                    ->orWhere('position', 'LIKE', $text . "%")
                    ->get()->toJson();                  
                    
                }    

            break;
            case 'students':  

                if($text == null){
                    $students = Student::all();

                    foreach($students as $student){                    
                        $student->program_desc = $student->program_id;
                        $student->balance_amount = $student->balance_id;
                    }

                    return $students->toJson();
                                       
                }else{
                    $students = Student::query()
                    ->where('last_name', 'LIKE', '%' . $text . "%")
                    ->orWhere('first_name', 'LIKE',  '%' .$text . "%")
                    ->orWhere('middle_name', 'LIKE', '%' . $text . "%")                    
                    ->orwhere('student_id', 'LIKE', '%' . $text . "%")                    
                    ->get();                                        

                    foreach($students as $student){                    
                        $student->program_desc = $student->program_id;
                        $student->balance_amount = $student->balance_id;
                    }

                    return $students->toJson();
                }    

            break;
            case 'applicants':  
                                

                if($dept != null){

                        if($dept == 0){

                            if($text != null){

                                $allApplicants = Applicant::query()    
                                ->where('dept', 0)                    
                                ->where('last_name', 'LIKE', '%' . $text . "%")
                                ->orWhere('first_name', 'LIKE', '%' . $text . "%")
                                ->orWhere('middle_name', 'LIKE', '%' . $text . "%")
                                ->get();                        
    
                                $applicants = $allApplicants->filter(function ($applicant) {
                                    return $applicant->approved == 0;
                                });          
                                
    
                                foreach($applicants as $applicant){                    
                                    $applicant->dept_desc = $applicant->id;
                                    $applicant->prog_desc = $applicant->id;
                                    $applicant->days_asgo = $applicant->id;
                                }
    
                                return $applicants->toJson();
    

                            } else {

                                $applicants = Applicant::where('approved', 0)
                                ->where('dept', 0)->get();

                                foreach($applicants as $applicant){                    
                                    $applicant->dept_desc = $applicant->id;
                                    $applicant->prog_desc = $applicant->id;
                                    $applicant->days_ago = $applicant->id;
                                }
                                
                                return $applicants->toJson();

                            }

                           
                        } else {


                            if($text != null){

                                $allApplicants = Applicant::query()    
                                ->where('dept', 1)                    
                                ->where('last_name', 'LIKE', '%' . $text . "%")
                                ->orWhere('first_name', 'LIKE', '%' . $text . "%")
                                ->orWhere('middle_name', 'LIKE', '%' . $text . "%")
                                ->get();                        
    
                                $applicants = $allApplicants->filter(function ($applicant) {
                                    return $applicant->approved == 0;
                                });          
                                
    
                                foreach($applicants as $applicant){                    
                                    $applicant->dept_desc = $applicant->id;
                                    $applicant->prog_desc = $applicant->id;
                                    $applicant->days_asgo = $applicant->id;
                                }
    
                                return $applicants->toJson();
    

                            } else {

                                $applicants = Applicant::where('approved', 0)
                                ->where('dept', 1)->get();

                                foreach($applicants as $applicant){                    
                                    $applicant->dept_desc = $applicant->id;
                                    $applicant->prog_desc = $applicant->id;
                                    $applicant->days_ago = $applicant->id;
                                }
                                
                                return $applicants->toJson();

                            }

                           
                        }                
                                           
                    
                }else {

                    if($text != null) {

                        $allApplicants = Applicant::query()                        
                        ->where('last_name', 'LIKE', '%' . $text . "%")
                        ->orWhere('first_name', 'LIKE', '%' . $text . "%")
                        ->orWhere('middle_name', 'LIKE', '%' . $text . "%")
                        ->get();                        

                        $applicants = $allApplicants->filter(function ($applicant) {
                            return $applicant->approved == 0;
                        });          
                        

                        foreach($applicants as $applicant){                    
                            $applicant->dept_desc = $applicant->id;
                            $applicant->prog_desc = $applicant->id;
                            $applicant->days_asgo = $applicant->id;
                        }

                        return $applicants->toJson();

                    } else {

                        $applicants = Applicant::where('approved', 0)->get();

                        foreach($applicants as $applicant){                    
                            $applicant->dept_desc = $applicant->id;
                            $applicant->prog_desc = $applicant->id;
                            $applicant->days_ago = $applicant->id;
                        }
                        
                        return $applicants->toJson();
                    }


                }                                        

            break;
        }    
        
        
    }

    public function searchBy($table, $by, $text = null){

    }
    



    public function download($type, $filename){
    
        $file_path = '';

        switch($type){
            case 'idpic':

                $file_path = public_path() . '/storage/applicants/id_pics/' . $filename;
            
            break;
            case 'birthcert':

                $file_path = public_path() . '/storage/applicants/birth_certs/' . $filename;

            break;
            case 'goodmoral':

                $file_path = public_path() . '/storage/applicants/good_morals/' . $filename;

            break;
            case 'reportcard':

                $file_path = public_path() . '/storage/applicants/report_cards/' . $filename;

            break;
        }

        return response()->download($file_path);   


    }


    public function requestFileResubmission(Request $request){        
        
        // return $request->all();

        $filetype = $request->input('req_type');        
        $id = $request->input('app_id');        

        $applicant = Applicant::find($id);

        switch($filetype){
            case 'idpic': 
                
                

                if($applicant->resubmit_file == null || $applicant->resubmit_file == ''){

                    $applicant->resubmit_file = '1000';

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);

                } else {

                    $value = $applicant->resubmit_file;
                    $value[0] = '1';
                    $applicant->resubmit_file = $value;

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);
                }
                               
            break;
            case 'birthcert':

                if($applicant->resubmit_file == null || $applicant->resubmit_file == ''){

                    $applicant->resubmit_file = '0100';

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                
                    ->with('app-id', $id);

                } else {

                    $value = $applicant->resubmit_file;
                    $value[1] = '1';
                    $applicant->resubmit_file = $value;

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);
                }

            break;
            case 'goodmoral':

                if($applicant->resubmit_file == null || $applicant->resubmit_file == ''){

                    $applicant->resubmit_file = '0010';

                    $applicant->save();
                    
                    return redirect()->route('adminView')                                    
                    ->with('btn-active', 'app-' . $id);

                } else {

                    $value = $applicant->resubmit_file;
                    $value[2] = '1';
                    $applicant->resubmit_file = $value;

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);
                }
            break;
            case 'reportcard':

                if($applicant->resubmit_file == null || $applicant->resubmit_file == ''){

                    $applicant->resubmit_file = '0001';

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);

                } else {

                    $value = $applicant->resubmit_file;
                    $value[3] = '1';
                    $applicant->resubmit_file = $value;

                    $applicant->save();
                    
                    return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);
                }
            break;

            default:
            return redirect()->route('adminView')                    
                    ->with('active', 'applicants')                    
                    ->with('app-id', $id);

        }

    }


    public function approveApplicant(Request $request){

        //delete member link        
        
        $app_id = $request->input('app_id');    

        $applicant = Applicant::find($app_id);

        /**
         * STUDENT CREATE
         */
        
        $student = new Student;      //studid

        $student->department = $applicant->dept;
        $student->program_id = $applicant->program;
        $student->semester = 1;

        $student->student_type = 0;
        $student->transferee = 0;
        $student->created_by_admin = 0;

        if($applicant->dept == 0)
            $student->level = 1;
        else
            $student->level = 11;

        $student->email = $applicant->email;

        $student->last_name = $applicant->last_name;
        $student->first_name = $applicant->first_name;
        $student->middle_name = $applicant->middle_name;

        $student->dob = $applicant->dob;
        $student->gender = $applicant->gender;
        
        $student->present_address = $applicant->present_address;
        $student->last_school = $applicant->last_school;

        $student->save();
    
            $balance = new Balance;             
            $balanceID = $balance->init();                

            $student->balance_id = $balanceID;
                    
            $year =  date("y");
            $prefix = "C";
            $prefixID = $prefix . $year . '-' . sprintf('%04d', $student->id);
        
            $student->student_id = $prefixID;

        $student->save();
        $student_id = $student->id;

        $applicant->approved = 1;
        $applicant->student_id = $student->id;
        $applicant->save();
        

        $member_old = Member::query()->where('member_type', 'applicant')->where('member_id', $app_id)->first();
        $user_id = $member_old->user_id;

        $user = User::find($member_old->user_id);
        $user->user_type = 'student';
        $user->save();

        $student->dept = $student->department;
        $student->program_desc = $student->program_id;

        Mail::to($user)->send(new ApprovedApplicant($student->first_name . ' ' . $student->last_name,
                                                    $student->student_id,
                                                    $student->dept,
                                                    $student->program_desc,
                                                    ));

        Member::where('member_type', $member_old->member_type)
              ->where('member_id', $member_old->member_id)
              ->where('user_id', $member_old->user_id)->delete();              

        $member_new = new Member;
        $member_new->user_id = $user_id;
        $member_new->member_type = 'student';
        $member_new->member_id = $student_id;
                
        $member_new->save();
                   
        $values = ['department' => $student->department, 
                    'program' => $student->program_id, 
                    'level' => $student->level, 
                    'semester' => $student->semester, 
                  ];

        $subjects = Subject::allWhere($values, true);
        $totalBalance = 0;
        $studentBalance = Balance::find($student->balance_id);

        $subjectsToBeTakenLength = count($subjects);                  

        for($i=0; $i < $subjectsToBeTakenLength; $i++){

            $subjectToTake = new SubjectTaken;

            $subject = Subject::find($subjects[$i]->id);   
    
            $subjectToTake->student_id = $student_id;
            $subjectToTake->subject_id = $subject->id;
            
            if($student->department == 0)
                $totalBalance+= Setting::first()->shs_price_per_unit * $subject->units;
            else 
                $totalBalance+= Setting::first()->college_price_per_unit * $subject->units; 

            if($i == $subjectsToBeTakenLength - 1 ) {                
                $studentBalance->amount = $totalBalance;
                $studentBalance->save();
            }

            $subjectToTake->rating = 4.5;    
            $subjectToTake->from_year = Setting::first()->from_year;  
            $subjectToTake->to_year = Setting::first()->to_year; 
            $subjectToTake->semester = Setting::first()->semester;  
            
            $subjectToTake->save();

        }

        
        return redirect()->route('adminView')->with('active', 'applicants');

    }


    public function viewPaymentRequests(){

        $admin = Admin::find(auth()->user()->member->member_id);

        return view('admin.payment_requests')->with('admin', $admin);

    }

    public function approvePaymentRequest(Request $request){    

       $paymentrequest = PaymentRequest::find($request->input('payment_id'));

       $paymentrequest->admin_id = auth()->user()->member->member_id;

       $paymentrequest->save();

       return  redirect()->route('viewPaymentRequests')->with('status', 'Payment Request Approved!');

    }

    public function updateSchedule(Request $request){

        if($request->method() != 'POST'){
            redirect()->back();
        }

        return $request->all();

    }
   

}
