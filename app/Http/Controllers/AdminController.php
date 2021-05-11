<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class AdminController extends Controller
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

}
