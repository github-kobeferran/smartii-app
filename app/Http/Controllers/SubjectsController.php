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
            'code' => 'required|max:12|regex:/^[\s\w-]*$/', 
            'desc' => 'required|max:100|regex:/^[\s\w-]*$/', 
            'dept' => 'required', 
            'level' => 'required', 
            'prog' => 'required',
            'sem' => 'required',
            'units' => 'required|numeric|between:3,12',
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminCreate')
                         ->withErrors($validator)
                         ->withInput()
                         ->with('active','subject');
        }

        $subject = new Subject;

        if(Subject::where('code', $request->input('code'))->exists() || 
           Subject::where('desc', $request->input('desc'))->exists()){
            return redirect()->route('adminCreate')
                             ->with('error', 'Code or Description already exist')
                             ->with('active', 'subject');
                                        
        } else {
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

            if($subject->save())
                $subjectID = $subject->id;
            else {

                return redirect()->route('adminCreate')
                                 ->with('error', 'There is a problem saving, please try again.')
                                 ->with('active', 'subject');

            } 
            
        }

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

        
        

        return redirect()->route('adminCreate')
                         ->with($status, $msg)
                         ->with('active', 'subject');
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

        $subject = Subject::find($request->input('subject_id'));

        $subject->code = $request->input('code');
        $oldDesc = $subject->desc;
        $subject->desc = $request->input('desc');
        $subject->level = $request->input('level');
        $subject->dept = $request->input('dept');
        $subject->units = $request->input('units');

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
        //
    }
}
