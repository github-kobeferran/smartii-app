<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Fee;


class FeesController extends Controller
{
    public function store(Request  $request){        

        if($request->method() != 'POST')
            return redirect()->back();        

        if(Fee::where('desc', $request->input("desc"))->exists() )
            return redirect()->route('adminSettings')->with('warning', "Fee Description already exists, you can be specific in naming for other departments like: \"SHS miscellaneous\"");


        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:25', 
            'amount' => 'required|lte:50000|gte:50,|numeric', 
            'dept' => 'required',                   
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminSettings')
                         ->withErrors($validator)
                         ->withInput();                         
        }
    

        $fee = new Fee;

        $fee->desc = $request->input("desc");
        $fee->amount = $request->input("amount");
        $fee->dept = $request->input("dept");
        $fee->level = $request->input("level");
        $fee->sem = $request->input("sem");

        $fee->save();
        return redirect()->route('adminSettings')->with('success', "Fee: ". $fee->desc ." Added");

    }
    
    public function update(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();  

        if(Fee::where('desc', $request->input("desc"))->where('desc', '!=', $request->input('olddesc'))->exists() )
            return redirect()->route('adminSettings')->with('warning', "Fee Description already exists, you can be specific in naming for other departments like: \"SHS miscellaneous\"");
        
        $validator = Validator::make($request->all(), [
            'desc' => 'required|regex:/^[a-z ,.\w\'-]*$/|max:25', 
            'amount' => 'required|lte:50000|gte:50,|numeric', 
            'dept' => 'required',                   
        ]);

        if ($validator->fails()) {
            return redirect()->route('adminSettings')
                            ->withErrors($validator)
                            ->withInput();                         
        }

        $fee = Fee::find($request->input('id'));

        $fee->desc = $request->input("desc");
        $fee->amount = $request->input("amount");
        $fee->dept = $request->input("dept");

        $fee->save();
        return redirect()->route('adminSettings')->with('success', "Fee: ". $fee->desc ." Added");


    }

    public function delete(Request $request){

        if($request->method() != 'POST')
            return redirect()->back();  

        $fee = Fee::find($request->input('id'));
        $desc = $fee->desc;

        $fee->delete();

        return redirect()->route('adminSettings')->with('info', "Fee: ". $desc ." Deleted");

    }

}
