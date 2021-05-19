<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Program;
use App\Models\Subject;



class AdminsController extends Controller
{
    //
    
    public function index(){
        return view('admin.dashboard');
    }

    public function adminCreate(){
        return view('admin.create');
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
            default:
            redirect('/home');
        }
        
    }


    public function showTableBy($table, $by, $value){
        if($by == ''){

            showTable($table);

        } else {

            switch($table){

                case 'programs':
                    $programs = Program::where($by, $value)->get();

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

                default:
                redirect('/home');
            }
        
    }

    public function search($table, $text = ''){
        switch($table){
            case 'admins':
                
                if($text == ''){
                    $admins = Admin::all();
                }else{
                    $admins = Admin::query()
                    ->where('name', 'LIKE',  $text . "%")
                    ->orWhere('email', 'LIKE',  $text . "%")
                    ->orWhere('position', 'LIKE', $text . "%")
                    ->get();
                }                               
                                
                return $admins->toJson();

            break;
        }    
        
        
    }

}
