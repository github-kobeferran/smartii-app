<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Program;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Balance;


class AdminsController extends Controller
{
    //
    
    public function index(){
        return view('admin.dashboard');
    }

    public function adminCreate(){
        return view('admin.create')->with('student', true);
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

    public function adminSubjects(){
        return view('admin.subjects');
    }

    public function showTable($table){
        switch($table){
            case 'admins':

                $admins = Admin::all();
                return $admins->toJson();
            break;        
            case 'students':

                $students = Student::all();

                $programs =[];
                $count = 0;


                foreach($students as $student){
                    $programs[$count] = Program::find($student->program_id);
                    $count++;
                }


                $results = ['students' => $students, 'programs' => $programs];
                    
                return $results;
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
                
                $student->setProgramDescAttribute(Program::find($student->program_id)->desc);
                $student->setBalanceAmountAttribute(Balance::find($student->balance_id)->amount);
                    
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
        }    
        
        
    }

}
