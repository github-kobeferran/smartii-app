<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Discount;
use App\Models\StudentsDiscount;

class DiscountsController extends Controller
{
    public function store(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        if(Discount::where('description', $request->input('description'))->exists())
            return redirect()->route('adminPayment')->with('warning', 'Discount Updation Failed. That Discount description is already taken by other discount.');
      

        $validator = Validator::make($request->all(), [
            'description' => 'required|max:100',
            'percentage' => 'required|lte:100|gte:0.01',
        ]);

        if($validator->fails())        
            return redirect()->route('adminPayment')->withErrors($validator);

        $discount = new Discount;

        $discount->description = $request->input('description');
        $discount->percentage = $request->input('percentage');

        $discount->save();

        return redirect()->route('adminPayment')->with('success', 'Discount Created Successfully');
        
    }

    public function update(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        if(Discount::where('description', $request->input('description'))->where('id', '!=', $request->input('id'))->exists() )
            return redirect()->route('adminPayment')->with('warning', 'Discount Updation Failed. That Discount description is already taken by other discount.');

        $discount = Discount::find($request->input('id'));        

        $oldname = $discount->description;
        $discount->description = $request->input('description');
        $discount->percentage = $request->input('percentage');

        $discount->save();

        return redirect()->route('adminPayment')->with('success', 'Discount Updation of discount ' . $oldname . ' is successful');

        
    }

    public function delete(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        $discount = Discount::find($request->input('id'));        

        foreach($discount->students as $discount_rel){
            $discount_rel->delete();
        }
        
        $discount->delete();

        return redirect()->route('adminPayment')->with('info', 'Discount ' . $discount->description . ' and it\'s attatchments deleted.');
    }
}
