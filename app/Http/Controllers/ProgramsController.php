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
            'abbrv' => 'required|max:10',                        
        ]);
    
        if ($validator->fails()){
            return redirect()
                            ->route('adminCreate')
                            ->withErrors($validator)
                            ->withInput()
                            ->with('program', true);
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


        return redirect()->route('adminCreate')->with($status, $msg)->with('program', true); 
        
    }
}