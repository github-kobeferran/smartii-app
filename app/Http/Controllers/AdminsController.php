<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Faculty;
use App\Models\Setting;
use App\Models\Balance;
use App\Models\User;
use App\Models\Member;
use App\Models\Room;
use App\Mail\WelcomeMember;
use PDF;



class AdminsController extends Controller
{    
    
    public function index(){
        return view('admin.dashboard');
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

                    // $programs = Program::where($by, $value)->get();  

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
                break;
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

    public function search($table, $text = ''){
        switch($table){     

            case 'admins':  

                if($text == ''){

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

                if($text == ''){
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
        }    
        
        
    }


    

}
