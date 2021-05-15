<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Program;



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

                    return $programs;
                break;        


                default:
                redirect('/home');
            }
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
