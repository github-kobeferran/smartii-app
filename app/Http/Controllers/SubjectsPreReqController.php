<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectsPreReq;
use App\Models\Subject;

class SubjectsPreReqController extends Controller
{
 
    public function updatePreReq(Request $request){

        
        if($request->method() != 'POST'){
            return redirect()->back();
        }                
        

        if(!Subject::where('code', $request->input('subj_code'))->exists() ){
            return redirect()->back()->with('error', 'Subject with ' . $request->input('subj_code') . ' code does\'nt exist');
        }

        $new_pre_req = Subject::where('code', $request->input('subj_code'))->first();    

        $subject_pre_req = SubjectsPreReq::where('subject_id', $request->input('subj_id'))
                                        ->where('subject_pre_req_id',  $request->input('pre_req_id'));

        $subject_pre_req->subject_pre_req_id = $new_pre_req->id;

        $subject_pre_req->save();

        return redirect()->route('adminCreate')->with('active', 'subject')->with('success','Pre-Requisite Successfully Updated');



    }
    
}
