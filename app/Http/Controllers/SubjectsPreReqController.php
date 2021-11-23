<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectsPreReq;
use App\Models\Subject;

class SubjectsPreReqController extends Controller
{

    public function attach(Request $request){
        if($request->method() != 'POST')
            return redirect()->back();
        

        $subject = Subject::find($request->input('subj_id'));   
        $subject->pre_reqs()->attach($request->input('pre_req_id'));

        if($subject->pre_req = 0){
            $subject->pre_req = 1;
            $subject->save();
        }

        return redirect()->route('adminCreate')->with('active', 'subject')
                                                ->with('success', $subject->pre_reqs()->where('id', $request->input('pre_req_id'))->first()->desc .' was added to Pre-Requisites of '. $subject->desc);

    }
 
    public function detach(Request $request){
        if($request->method() != 'POST')
            return redirect()->back();

        // return $request->all();
        
        $subject = Subject::find($request->input('subj_id'));        
        $desc = $subject->pre_reqs->where('id', $request->input('pre_req_id'))->first()->desc;                        
        $subject->pre_reqs()->detach($request->input('pre_req_id'));  
        
        if($subject->pre_reqs->count() < 1){
            $subject->pre_req = 0;
            $subject->save();
        }

        return redirect()->route('adminCreate')->with('active', 'subject')->with('info', $desc .' was removed from Pre-Requisites of '. $subject->desc);

    }
    
}
