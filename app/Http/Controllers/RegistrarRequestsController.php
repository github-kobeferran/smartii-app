<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrarRequest;
use App\Models\Program;
use App\Models\Student;

class RegistrarRequestsController extends Controller
{
    
    public function storeShift(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        $registrar_request = new RegistrarRequest;

        $registrar_request->type = 'shift';
        $registrar_request->type_id = $request->input('program');
        $registrar_request->requestor_type = 'student';
        $registrar_request->requestor_id = $request->input('id');

        $registrar_request->save();

        return redirect('/studentprofile/' . Student::find($request->input('id'))->student_id )->with('info', 'Request in Shifting to ' . Program::find($registrar_request->type_id)->desc . ' is submitted.');

    }

    public function approveShift(Request $request){
        if($request->method() != "POST")
            return redirect()->back();
    
        $registrar_request = RegistrarRequest::find($request->input('id'));        
        $student = $registrar_request->requestor;

        $student->program_id = $registrar_request->type_id;                

        $registrar_request->status = 1;
        $registrar_request->marked_by = auth()->user()->member->member_id;
        $student->save();
        $registrar_request->save();

        return redirect()->route('adminDashboard')->with('success', $student->first_name . ' ' . $student->last_name . ' Change of Program is applied. ');

    }

    public function rejectShift(Request $request){
        if($request->method() != "POST")
            return redirect()->back();
    
        $registrar_request = RegistrarRequest::find($request->input('id'));  
        $student = $registrar_request->requestor;      

        if($request->has('reason'))
            $registrar_request->reject_reason = $request->input('reason');

        $registrar_request->status = 2;
        $registrar_request->marked_by = auth()->user()->member->member_id;
        $registrar_request->save();

        return redirect()->route('adminDashboard')->with('info', $student->first_name . ' ' . $student->last_name . ' Change of Program is denied. ');
    }


}
