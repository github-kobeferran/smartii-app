<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
use App\Models\Announcement;
use App\Models\Fee;
use App\Models\StudentClass;
use App\Models\Discount;
use App\Mail\WelcomeMember;
use Carbon\Carbon;
use PDF;



class AdminsController extends Controller
{    
    
    public function index(){            

        if(!empty(Setting::first())){

            $setting = Setting::first();

            return view('admin.dashboard')
            ->with('setting', Setting::first())
            ->with('applicantCount', Applicant::where('approved', 0)->count())
            ->with('studentCount', Student::all()->count())
            ->with('classCount', StudentClass::where('archive', 0)->count())
            ->with('maleCount', Student::where('gender', 'male')->count())
            ->with('femaleCount', Student::where('gender', 'female')->count())
            ->with('genderNullCount', Student::whereNull('gender')->count())
            ->with('passedStudents', SubjectTaken::where('rating', '<=', 3)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('failedStudents', SubjectTaken::where('rating', '>', 4.5)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('defferedStudents', SubjectTaken::where('rating', '=', 4)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('programsOffered', Program::where('id','!=', 3)->where('id','!=', 4)->count())
            ->with('announcements', Announcement::all());
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

            return view('admin.dashboard')
            ->with('setting', $setting)
            ->with('applicantCount', Applicant::where('approved', 0)->count())
            ->with('studentCount', Student::all()->count())
            ->with('classCount', StudentClass::where('archive', 0)->count())
            ->with('maleCount', Student::where('gender', 'male')->count())
            ->with('femaleCount', Student::where('gender', 'female')->count())
            ->with('genderNullCount', Student::whereNull('gender')->count())
            ->with('passedStudents', SubjectTaken::where('rating', '<=', 3)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('failedStudents', SubjectTaken::where('rating', '>', 4.5)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('defferedStudents', SubjectTaken::where('rating', '=', 4)->where('from_year', $setting->from_year)->where('semester', $setting->semester)->count())
            ->with('programsOffered', Program::all()->count())
            ->with('announcements', Announcement::all());
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

    
    public function store(Request $request){

        $status ='';
        $msg = '';
        $id = 0;

        $before_date = Carbon::now()->subYears(15);       
        $after_date = new Carbon('1903-01-01');

        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:100', 
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

                $faculties = Faculty::all();        

                foreach($faculties as $faculty ){
                    $faculty->specialty = $faculty->id;
                }

                return $faculties->toJson();
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
            case 'allsubjects':

                return Subject::orderBy('created_at', 'desc')->get();
                
            break;
            case 'rejectedapplicants':

                $applicants = Applicant::onlyTrashed()->get();

                foreach($applicants as $applicant){
                    $applicant->days_ago = 1;
                    $applicant->prog_desc= 1;
                    $applicant->dept_desc= 1;
                }

                return $applicants;

                
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

                $applicant = Applicant::withTrashed()->find($id);  
                
                           
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
                $schedule->formatted_until = $schedule->until;
                $schedule->day_name = $schedule->day;
                $schedule->room_name = $schedule->id;
                $schedule->studentClass;                                
                $schedule->faculty_name = $schedule->id;                                
                

                return $schedule;
            break;   
            case 'subjects':

                $subject = Subject::find($id);

                $subject->program_desc = $subject->id;
                $subject->level_desc = $subject->level;
                $subject->semester_desc = $subject->semester;

                $subject->pre_reqs;
                $subject->program;

                return $subject;

            break;        
            case 'programs':

                $program = Program::find($id); 

                $program->dept_desc = $program->department;

                return $program;

            break;        
            case 'fees':

                $fee = Fee::find($id);                 

                return $fee;

            break;        
            case 'discounts':

                return Discount::find($id);

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
                    
                    $students = Student::where($by, $value)->limit(100)->orderBy('created_at', 'desc')->get();

                    foreach($students as $student){
                        $student->age = $student->id;
                        $student->level_desc = $student->level;
                    }

                    return $students->toJson();

                break;
                case 'applicants':
                    
                    $applicants = Applicant::where($by, $value)
                                           ->where('approved', 0)
                                           ->orderBy('created_at', 'desc')
                                           ->get();

                    foreach($applicants as $applicant){
                        $applicant->dept_desc = $applicant->id;
                        $applicant->prog_desc = $applicant->id;
                        $applicant->days_ago = $applicant->id;
                    }

                    return $applicants->toJson();

                break;
                case 'invoices':
                    
                    $invoices = Invoice::where($by, $value)->orderBy('created_at', 'desc')->get();

                    foreach($invoices as $invoice){
                        $invoice->stud_name = $invoice->student_id;
                        $invoice->formatted_date = $invoice->created_at;
                    }
               
                    return $invoices->toJson();

                break;
                case 'subjects':
                    return Subject::where($by,$value)->orderBy('desc', 'asc')->get();
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
                                    $thirdColumn, $thirdValue, $fourthColumn, $fourthValue, $all = null){                                   
                               
            switch($table){
                case 'subjects':                    
                    $values = [$firstColumn => $firstValue,
                               $secondColumn => $secondValue,
                               $thirdColumn => $thirdValue,
                               $fourthColumn => $fourthValue];

                    if(is_null($all))
                        $subjects = Subject::allWhere($values, true);
                    else
                        $subjects = Subject::allWhere($values, false);
                    
                    $programs = [];
                    $pre_reqs = [];                                        
                    
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
                    $students = Student::orderBy('created_at', 'desc')->get();

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

            case 'faculty':

                $faculties = Faculty::query()                        
                ->where('last_name', 'LIKE', '%' . $text . "%")
                ->orWhere('first_name', 'LIKE', '%' . $text . "%")
                ->orWhere('middle_name', 'LIKE', '%' . $text . "%")
                ->orWhere('faculty_id', 'LIKE', '%' . $text . "%")
                ->get();  

                $faculties = Faculty::all();        

                foreach($faculties as $faculty ){
                    $faculty->specialty = $faculty->id;
                }
                
                return $faculties;
               

            break;

            case 'rooms':

                return Room::query()                        
                ->where('name', 'LIKE', '%' . $text . "%")->get();                                  
               
            break;

            case 'subjects':

                if($dept == 0){

                   if($text == 'SearchInputIsEmpty'){

                       return Subject::where('dept', 0)
                            ->orderBy('desc', 'asc')->get();
                       
                   } else {

                        $subjects = Subject::query()                        
                        ->where('desc', 'LIKE', '%' . $text . "%")
                        ->where('dept', 0)
                        ->orderBy('desc', 'asc')->get();

                        $othersubjs = Subject::where('dept', 0)->get()->diff($subjects);
                        
                        foreach($othersubjs as $item){
                            if($item->program()
                                    ->where(function($query) use($text){
                                        $query->where('desc', 'LIKE', '%' . $text . "%")
                                        ->orWhere('abbrv', 'LIKE', '%' . $text . "%");
                                    })  
                                    ->where('id', 0)
                                    ->exists())
                                $subjects->push($item);
                        }   

                    return $subjects;
                   }

                } else if ($dept == 1){

                    if($text == 'SearchInputIsEmpty'){

                        return Subject::where('dept', 1)
                            ->orderBy('desc', 'asc')->get();
                        
                    } else {
                        $subjects = Subject::query()                        
                        ->where('desc', 'LIKE', '%' . $text . "%")
                        ->where('dept', 1)
                        ->orderBy('desc', 'asc')->get();

                        $othersubjs = Subject::where('dept', 1)->get()->diff($subjects);                        
                        
                        foreach($othersubjs as $item){
                            if($item->program()                                    
                                    ->where(function($query) use($text){
                                        $query->where('desc', 'LIKE', '%' . $text . "%")
                                              ->orWhere('abbrv', 'LIKE', '%' . $text . "%");
                                    })                                                                       
                                    ->exists())
                                $subjects->push($item);
                        }   

                        return $subjects;

                    }

                } else {
                    
                    return Subject::where('dept', 0)
                    ->orderBy('desc', 'asc')->get();
                }

            break;

        }    
        
        
    }

    public function searchBy($table, $by, $value, $text = null){   

            switch($table){

                case 'students':

                    if(!is_null($text)){
                        
                        $students = Student::where($by, $value)
                                    ->where(function($query) use($text) {
                                        $query->where('last_name', 'LIKE', '%' . $text . "%")
                                        ->orWhere('first_name', 'LIKE', '%' . $text . "%")
                                        ->orWhere('middle_name', 'LIKE', '%' . $text . "%")
                                        ->orWhere('student_id', 'LIKE', '%' . $text . "%");
    
                                    })
                                    ->get();
    
                        foreach($students as $student){
    
                            $student->age = $student->id;
                            $student->level_desc = $student->level;
    
                        }
    
                        return $students;

                    } else {

                        return $this->showTableBy($table, $by, $value, true);

                    }




                break;

                case 'programs':

                    if(!is_null($text)){
                        return Program::where($by, $value)
                            ->where('id', '!=', ($value == 0 ? '3' : '4'))
                            ->where(function($query) use($text) {
                                $query->where('desc', 'LIKE', '%' . $text . "%")
                                ->orWhere('abbrv', 'LIKE', '%' . $text . "%");
                            })
                            ->get();
                    } else {
                        return Program::where($by, $value)
                            ->where('id', '!=', ($value == 0 ? '3' : '4'))                            
                            ->get();
                    }

                   

                break;
            }

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

    public function enrollToSubject(Request $request){

        if($request->method() != 'POST'){
            return redirect()->back();
        }   

        if(!Subject::where('code', $request->input('subject_code'))->exists()){
            return redirect()->back()->with('error', 'Subject does not exist');
        }

        $subject = Subject::where('code', $request->input('subject_code'))->first();

        $student = Student::find($request->input('student_id'));

        if($student->department == 0){

            if(($student->program_id != $subject->program_id) && ($subject->program_id != 3)){
                return redirect()->back()->with('error', 'Subject invalid');
            }

        } else {

            if(($student->program_id != $subject->program_id) && ($subject->program_id != 4)){
                return redirect()->back()->with('error', 'Subject invalid');
            }

        }

        if(SubjectTaken::where('subject_id', $subject->id)
                        ->where('student_id', $student->id)
                        ->where(function($query) {
                            $query->where('rating', 4.5)
                                  ->orWhere('rating', 3.5);
                        })                       
                        ->exists()
          ){
            return redirect()->back()->with('warning', 'Subject is currently taken or had been already passed by this student');
        }
        
        
        foreach($subject->pre_reqs as $pre_req){

            if(!SubjectTaken::where('student_id', $student->id)
                        ->where('subject_id', $pre_req->id)
                        ->where('rating', '<=', 3)->exists()){

                            return redirect()->back()->with('error', 'Subject ' . $pre_req->code . ' is not passed by this student');

                        }

        }

        $settings = Setting::first();

        $subjectToTake = new SubjectTaken; 

        $subjectToTake->student_id = $student->id;
        $subjectToTake->subject_id = $subject->id;
        $subjectToTake->rating = 4.5;
        $subjectToTake->from_year = $settings->from_year;
        $subjectToTake->to_year = $settings->to_year;
        $subjectToTake->semester = $settings->semester;      

        $subjectToTake->save();

        return redirect('studentprofile/' . $student->student_id)->with('success', 'Enrolled to ' . $subject->desc);


    }

    public function homepageImageStore(Request $request){

        if($request->method() != 'POST'){
            return redirect()->back();
        }   

        

        $validator = Validator::make($request->all(), [
            'image' => 'image|max:10000',                                              
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminDashboard')
                         ->withErrors($validator)
                         ->withInput();                         
        }

        
        // get filename with the extension
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        // get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        //Filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // upload image
        $path = $request->file('image')->storeAs('public/images/system/homepage_images/', $fileNameToStore);
        


        DB::insert('insert into homepage_images (image) values (?)', [$fileNameToStore]);

        return redirect()->route('adminDashboard')->with('success', 'Image Added');


    }

    public function homepageImageDelete($id){

        $images = DB::select('select * from homepage_images where id = ?', [$id]);
      
        foreach($images as $image){                        
            Storage::disk('public')->delete('images/system/homepage_images/' . $image->image);
        }
        

        DB::delete('delete from homepage_images where id = ?', [$id]);

        

        return redirect()->route('adminDashboard')->with('info', 'Image Deleted');
    }

    public function homepageImageUpdate(Request $request){
        
        if($request->method() != 'POST'){
            return redirect()->back();
        }           

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:10000',                                              
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminDashboard')
                         ->withErrors($validator)
                         ->withInput();                         
        }

          // get filename with the extension
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        // get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        //Filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // upload image
        $path = $request->file('image')->storeAs('public/images/system/homepage_images/', $fileNameToStore);
          
        DB::update ('update homepage_images set image = ? where id = ?', [$fileNameToStore, $request->input('id')]);

        return redirect()->route('adminDashboard')->with('info', 'Image Updated');
    }

}
