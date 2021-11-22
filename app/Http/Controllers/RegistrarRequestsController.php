<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrarRequest;
use App\Models\Program;
use App\Models\Student;
use App\Models\SubjectTaken;
use App\Models\StudentClass;

class RegistrarRequestsController extends Controller
{

    public function viewDropRequests(){
        $requests = RegistrarRequest::where('type', 'drop')->orderBy('created_at', 'desc')->paginate(10); 
        return view('admin.drop')->with('requests', $requests);
    }

    public function viewShiftRequests(){
        $requests = RegistrarRequest::where('type', 'shift')->orderBy('created_at', 'desc')->paginate(10); 
        return view('admin.shift')->with('requests', $requests);
    }

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

    public function approveDrop(Request $request){
        if($request->method() != "POST")   
            return redirect()->back();

        $registrar_request = RegistrarRequest::find($request->input('id'));
        $subject_taken = SubjectTaken::find($registrar_request->type_id);

        if(!is_null($subject_taken->class)){
            if($subject_taken->class->subjectsTaken->count() == 1){
                $subject_taken->class->archive = 1;
                $subject_taken->class->save();
            }
        }

        $subject_taken->delete();
        $registrar_request->status = 1;
        $registrar_request->marked_by = auth()->user()->member->member_id;
        $registrar_request->save();

        return redirect()->route('adminDashboard')->with('success',  'Student  ' . $subject_taken->student->first_name . ' ' . $subject_taken->last_name . ' has dropped ' . $subject_taken->subject->desc);
    }

    public function rejectDrop(Request $request){
        if($request->method() != "POST")   
            return redirect()->back();

        $registrar_request = RegistrarRequest::find($request->input('id'));  
        $subject_taken = SubjectTaken::find($registrar_request->type_id);

        if($request->has('reason'))
            $registrar_request->reject_reason = $request->input('reason');

        $registrar_request->status = 2;
        $registrar_request->marked_by = auth()->user()->member->member_id;
        $registrar_request->save();

        return redirect()->route('adminDashboard')->with('info',  'Student  ' . $subject_taken->student->first_name . ' ' . $subject_taken->last_name . ' drop request to ' . $subject_taken->subject->desc . ' has been denied.');
    }

    public function requestDrop(Request $request){
        if($request->method() != "POST")        
            return redirect()->back();        

        if($request->has('id')){
            $subject_taken = SubjectTaken::find($request->input('id'));
        } else {
            $subject_taken = SubjectTaken::where('class_id', $request->input('class_id'))->where('student_id', $request->input('student_id'))->first();
        }                    

        if(!is_null($subject_taken->class)){

            if(auth()->user()->isStudent()) {
                if($subject_taken->student->id != auth()->user()->member->member_id)
                    return redirect()->back()->with('error', 'That action is forbidden.');
            } else {
                if($subject_taken->class->faculty->id != auth()->user()->member->member_id)
                    return redirect()->back()->with('error', 'That action is forbidden.');
            }
        }


        $registrar_request = new RegistrarRequest;

        $registrar_request->type = 'drop';
        $registrar_request->type_id = $subject_taken->id;
        $registrar_request->requestor_type = auth()->user()->user_type;
        $registrar_request->requestor_id = auth()->user()->member->member_id;
        $registrar_request->save();

        return redirect()->back()->with('info', 'Drop Request for '. $subject_taken->subject->desc . ' of '. $subject_taken->student->first_name . ' '  . $subject_taken->student->last_name  . ' has been submitted.');
        
    }


}
