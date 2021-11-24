<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SubjectsPreReq;
use Illuminate\Support\Facades\Validator;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $status = '';
        $msg = '';
        $subjectID = 0;


        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:subjects,code|max:12|regex:/^[A-Z]{3,}[0-9-]*$/', 
            'desc' => 'required|unique:subjects,desc|max:100|regex:/^[A-Za-z]{4,}[1-9 -]*$/', 
            'dept' => 'required', 
            'level' => 'required', 
            'prog' => 'required',
            'sem' => 'required',
            'units' => 'exclude_if:is_tesda,1|required|numeric|between:3,12',
            'units' => 'exclude_if:is_tesda,0|required|numeric|between:10,500',
        ], [
            'code.unique' => 'The Subject Code has already been taken.',
            'code.regex' => 'Some characters in the subject code are invalid, allowed characters are only: capital letters from A-Z, numbers from 0-9 and - (hyphen). Must also be 3 characters or more.',

            'desc.unique' => 'The Subject Description has already been taken.',
            'desc.regex' => 'Some characters in the subject description are invalid, allowed characters are only: capital and small letters from A-Z, numbers from 1-9, spaces, and - (hyphen). Must also be 4 characters or more.',
        ]);

        if ($validator->fails())
            return redirect()->route('adminCreate')->withErrors($validator)->withInput()->with('active','subject');

        $subject = new Subject;

        $subject->code = $request->input('code');
        $subject->desc = $request->input('desc');
        $subject->dept = $request->input('dept');
        $subject->level = $request->input('level');
        $subject->program_id = $request->input('prog');
        $subject->semester = $request->input('sem');
        $subject->units = $request->input('units');

        if($request->input('preReqs') > 0 )
            $subject->pre_req = 1;
        else 
            $subject->pre_req = 0;

        $subject->save();
        $subjectID = $subject->id;

        $preReqs = $request->input('preReqs');
        
        if($preReqs > 0 ){                       
            $noproblems = true;
            
            foreach($preReqs as $prereq){
                $subjectPreReq = new SubjectsPreReq;
                
                $subjectPreReq->subject_id = $subjectID;
                $subjectPreReq->subject_pre_req_id = $prereq;

                if($subjectPreReq->save()){

                }else {
                    $noproblems = false;
                }

            }

            if($noproblems){
                $status = 'success';
                $msg = 'Subject Created Successfully';
            } else {
                $status = 'warning';
                $msg = 'Subject Pre-Requisites are not added, try again.';
            }                


        } else {
            $subject->save();
            $status = 'success';
            $msg = 'Subject Created Successfully';
        }

        return redirect()->route('adminCreate')->with($status, $msg)->with('active', 'subject');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->method() != 'POST'){
            return redirect()->back();
        }                
        
        if(Subject::where('desc', $request->input('desc'))
            ->where('id', '!=', $request->input('subject_id'))
            ->exists())
            return redirect()->route('adminCreate')->withInput()->with('active','subject')->with('warning', 'That subject description is already taken by other subject');

        $validator = Validator::make($request->all(), [
            'edit_code' => 'required|unique:subjects,code,' . $request->input('subject_id') . '|max:12|regex:/^[A-Z]{3,}[0-9-]*$/',
            'edit_desc' => 'required|unique:subjects,desc,' . $request->input('subject_id') . '|max:100|regex:/^[A-Za-z]{4,}[1-9 -]*$/', 
            'edit_dept' => 'required', 
            'edit_level' => 'required', 
            'edit_prog' => 'required',
            'edit_semester' => 'required',
            'edit_units' => 'exclude_if:edit_is_tesda,1|required|numeric|between:3,12',
            'edit_units' => 'exclude_if:edit_is_tesda,0|required|numeric|between:10,500',
        ],[
            'edit_code.unique' => 'In updating: The Subject Code has already been taken.',
            'edit_code.max' => 'In updating: The Subject Code must not be greater than 12 characters.',
            'edit_code.regex' => 'In updating: Some characters in the subject code are invalid, allowed characters are only: capital letters from A-Z, numbers from 0-9 and - (hyphen). Must also be 3 characters or more.',

            'edit_desc.unique' => 'In updating: The Subject Description has already been taken.',
            'edit_desc.regex' => 'In updating: Some characters in the subject description are invalid, allowed characters are only: capital and small letters from A-Z, numbers from 1-9, spaces, and - (hyphen). Must also be 4 characters or more.', 
        ]);

        if ($validator->fails()) 
            return redirect()->route('adminCreate')->withErrors($validator)->withInput()->with('active','subject');
              
        
        
        $subject = Subject::find($request->input('subject_id'));

        $subject->code = $request->input('edit_code');     
        $oldDesc = $subject->desc;
        $subject->desc = $request->input('edit_desc');
        $subject->program_id = $request->input('edit_prog');
        $subject->level = $request->input('edit_level');
        $subject->dept = $request->input('edit_dept');
        $subject->semester = $request->input('edit_semester');
        $subject->units = $request->input('edit_units');      

        $subject->save();

        return redirect()->route('adminCreate')->with('active', 'subject')->with('success', $oldDesc. ' is successfully Updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {             
        foreach(Subject::all() as $subject){
            foreach($subject->pre_reqs as $pre_req){
                if($pre_req->id == $id)
                    return redirect()->route('adminCreate')->with('active', 'subject')->with('error', 'Can\'t delete a Pre-Requisite');
            }
        }
                
        Subject::find($id)->delete();

        return redirect()->route('adminCreate')
        ->with('active', 'subject')
        ->with('info', 'Subject Deleted');

    }

    public function disable(Request $request)
    {             
        if($request->method() != "POST")
            return redirect()->back();

        $subject = Subject::find($request->input('id'));                     

        foreach(Subject::all() as $s){
            foreach($s->pre_reqs as $pre_req){
                if($pre_req->id == $subject->id){
                    return redirect()->route('adminCreate')->with('active', 'subject')->with('error', 'Can\'t disable a Pre-Requisite Subject.');
                }
            }
        }          

        $subject->delete();

        return redirect()->route('adminCreate')->with('active', 'subject')->with('warning', $subject->desc . 'has been Disabled');

    }

    public function restore(Request $request){

        if($request->method() != "POST")
            return redirect()->back();

        $subject = Subject::withTrashed()->find($request->input('id'));   
        $subject->restore();
        return redirect()->route('adminCreate')->with('active', 'subject')->with('info', $subject->desc . ' has been Restored.');

    }

}
