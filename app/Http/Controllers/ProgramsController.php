<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Facades\Validator;

class ProgramsController extends Controller
{

    public function store(Request $request){               

        $validator = Validator::make($request->all(), [            
            'desc' => 'required|max:100', 
            'abbrv' => 'required|max:12',                        
        ]);
    
        if ($validator->fails()){
            return redirect()
                            ->route('adminCreate')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('active', 'program');
        }        
                
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

        $program = Program::find($request->input('id'));

        $program->department = $request->input('dept');
        $program->desc = $request->input('desc');
        $program->abbrv = $request->input('abbrv');

        $program->save();

        return redirect()->route('adminCreate')->with('active', 'program')->with('success', 'Program updated');

    }

    public function viewFromDashboard(){

        return redirect()->route('adminCreate')->with('active', 'program'); 

    }
}
