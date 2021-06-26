<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Setting;
use App\Models\PaymentRequest;


class PaymentRequestsController extends Controller
{


    public function create(){

        $stud_id = auth()->user()->member->member_id;        

        $student = Student::where('id',$stud_id)->first();
        
        $student->balance_amount = $student->balance_id;

        $settings = Setting::first();


        return view('student.payment')                                                                  
                        ->with('setting' , $settings)
                        ->with('student' , $student);   

    }

    public function store(Request $request){

        if($request->method() != 'POST'){
            redirect()->back();
        }
        
        $student = Student::find($request->input('stud_id'));

         
        
        $payment_request = new PaymentRequest;

        $payment_request->student_id = $student->id;
        $payment_request->trans_id = $request->input('trans_id');
        $payment_request->payment_mode = $request->input('payment_mode');



        if($request->hasFile('image')){

            $this->validate($request, [
                'image' => 'file|mimes:jpeg,jpg,png|max:2000'
            ]); 

            
            $imagewithExt = $request->file('image')->getClientOriginalName();
            $imageName = pathinfo($imagewithExt, PATHINFO_FILENAME);
            $imageExt = $request->file('image')->getClientOriginalExtension();
            $imageToStore = $imageName.'_'.time().'.'.$imageExt;
            $imagepath = $request->file('image')->storeAs('public/images/students/payment_requests', $imageToStore);

            $payment_request->image = $imageToStore;

        }



        if($request->has('desc'))
            $payment_request->desc;
        
        $payment_request->save();


         $year =  date("y");        
        $payment_request_ID = 'R'. $year . sprintf('%08d', $payment_request->id); 
        $payment_request->request_id =  $payment_request_ID;

        $payment_request->save();

        return redirect()->route('createPaymentRequest')->with('status', 'Payment Request Submitted!');

  
    }

    
}
