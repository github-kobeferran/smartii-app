<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Subject;
use App\Models\SubjectTaken;
use PDF;
use Illuminate\Support\Facades\Validator;

class ProgramsController extends Controller
{
    
    public function viewFromDashboard(){
        return redirect()->route('adminCreate')->with('active', 'program'); 
    }
    
    public function store(Request $request){                   

        $validator = Validator::make($request->all(), [            
            'desc' => 'required|max:100|regex:/^[A-Z]{3,}[ \/-]*$/|size:8', 
            'abbrv' => 'required|max:12|regex:/^[A-Z]{2,}[-]*$/|size:4',                        
        ],[
           'desc.regex' => 'Some program description characters are invalid, allowed characters are only: capital letters from A-Z, spaces, / (forward slash), and - (hyphen). Must also be 3 characters or more.',
           'abbrv.regex' => 'Some program abbreviation characters are invalid, allowed characters are only: capital letters from A-Z and - (hyphen). Must also be 2 characters or more.',
        ]);
    
        if ($validator->fails())
            return redirect()->route('adminCreate')->withErrors($validator)->withInput()->with('active', 'program');
                
        $program = new Program;
        $valid = true;
        $msg = '';
        $status = '';

        $program->department = $request->input('dept');

        if(Program::where('desc', $request->input('desc'))->exists()){
            $valid = false;
            $msg = 'Description ' . $request->input('desc') . ' already exist ';
        } else {
            $program->desc = $request->input('desc');
        }

        if(Program::where('abbrv', $request->input('abbrv'))->exists()){
            $valid = false;
            $msg = 'Abbreviation ' . $request->input('abbrv') . ' already exist';
        } else {
            $program->abbrv = $request->input('abbrv');
        }
        
        if($request->has('is_tesda'))
            $program->is_tesda = 1;

        if($valid){

            if($program->save()){
                $status = 'success';
                $msg = 'Program ' . $request->input('abbrv') . '-' . $request->input('desc') . ' created successfully';
            }
                            
        } else {
            $status = 'error';
        }


        return redirect()->route('adminCreate')->with($status, $msg)->with('active', 'program'); 
        
    }

    public function update(Request $request){

        if($request->method() != 'POST'){
            return redirect()->back();
        }   

        $validator = Validator::make($request->all(), [            
            'edit_desc' => 'required|max:100|regex:/^[A-Za-z ]{3,}[ \/-]*$/', 
            'edit_abbrv' => 'required|max:12|regex:/^[A-Z]{2,}[-]*$/',                        
        ],[
           'edit_desc.regex' => 'IN UPDATE : Some program description characters are invalid, allowed characters are only: capital and small letters from A-Z, spaces, / (forward slash), and - (hyphen). Must also be 3 characters or more.',
           'edit_abbrv.regex' => 'IN UPDATE : Some program abbreviation characters are invalid, allowed characters are only: capital letters from A-Z and - (hyphen). Must also be 2 characters or more.',
        ]);
    
        if ($validator->fails())
            return redirect()->route('adminCreate')->withErrors($validator)->withInput()->with('active', 'program');

        $program = Program::find($request->input('id'));

        if($request->has('dept'))
            $program->department = $request->input('dept');                       
        
        $program->desc = $request->input('edit_desc');
        $program->abbrv = $request->input('edit_abbrv');
        
        if($request->has('is_tesda'))
            $program->is_tesda = 1;
        else
            $program->is_tesda = 0;

        if($program->isDirty('is_tesda')){
            foreach($program->subjects as $subject){
                if($program->is_tesda){
                    $subject->units = 80;
                    $subject->save();
                } else {
                    $subject->units = 3;
                    $subject->save();
                }
            }
        }               

        if ($program->isDirty('department')) {
            if(!$program->department)
                $program->is_tesda = 0;
        }
    
        $program->save();
        return redirect()->route('adminCreate')->with('active', 'program')->with('success', 'Program ' . $program->desc . ' updated');

    }


    public function programCoursesExport($abbrv){

        $program = Program::where('abbrv', $abbrv)->first();
        $subjects = Subject::allWhere([
                                      'department' => $program->department,
                                      'program' => $program->id,
                                      'level' => $program->department ? 12 : 2,
                                      'semester' => 2,
                                    ]); 
        foreach($subjects as $subject){
            $subject->pre_reqs;
        }

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);                                    
        $pdf = PDF::loadView('pdf.program_courses', compact('program', 'subjects'));
        return $pdf->stream(  $program->abbrv . '.pdf');  
    }

    public function delete(Request $request){
        if($request->method() != "POST")        
            return redirect()->back();

        $program = Program::find($request->input('id'));
        $program->delete();

        return redirect()->route('adminCreate')->with('active', 'program')->with('info', 'Program ' . $program->desc . ' deleted');

    }

    
}
