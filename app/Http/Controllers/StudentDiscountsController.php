<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentDiscounts;
use App\Models\Student;
use App\Models\Discount;

class StudentDiscountsController extends Controller
{
    public function attachToStudent(Request $request){
        if($request->method() != "POST")
            return redirect()->back();

        $student = Student::find($request->input('stud_id'));
        $total_percentage = $request->input('total_percentage');

        foreach($request->input('discount') as $id){
            $discount = Discount::find($id);   

            $new_total = $total_percentage + $discount->percentage;

            if($new_total > 100)   
                return redirect('/studentprofile/' . $student->student_id)->with('error', 'Discount Percentage exceeds 100%')->with('active', 'student');
            else 
                $total_percentage += $new_total; 
        }

        foreach($request->input('discount') as $id){
            $discount = Discount::find($id);                        

            $student_discount = new StudentDiscounts;
            $student_discount->student_id = $student->id;
            $student_discount->discount_id = $discount->id;            

            $student->balance->amount -= $student->tuition_without_discount * ($discount->percentage / 100);
            $student->balance->save();
            $student_discount->save();                        
        }

        return redirect('/studentprofile/' . $student->student_id)->with('success', 'Discounts have been added')->with('active', 'student');

    }

    public function detachFromStudent(Request $request){

        if($request->method() != "POST")
            return redirect()->back();

        $student = Student::find($request->input('stud_id'));
        $discount = Discount::find($request->input('disc_id'));

        $student_discount = StudentDiscounts::where('student_id', $student->id)
                                            ->where('discount_id', $discount->id)
                                            ->first();

        $discount_amount = $student->tuition_without_discount * ($discount->percentage / 100); 

        $student->balance->amount += $discount_amount;

        $student->balance->save();
        $student_discount->delete();

        return redirect('/studentprofile/' . $student->student_id)->with('info', 'Discount Detached and Balance updated');

    }
}
