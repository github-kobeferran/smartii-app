<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Setting;
use App\Models\Invoice;
use App\Models\PaymentRequest;
use App\Models\Admin;


class PaymentRequestsController extends Controller
{

    public function view(){

        $admin = Admin::find(auth()->user()->member->member_id);

        return view('admin.payment_requests')->with('admin', $admin);

    }

    public function create(){

        if(PaymentRequest::where('student_id', auth()->user()->member->member_id)->whereNull('admin_id')->exists())
            return redirect()->back()->with('info', 'You still have a pending payment request. Please wait for that transaction to be finished.');                    
            
        if(Student::find(auth()->user()->member->member_id)->balance->amount < 1)
            return redirect()->back()->with('info', 'Balance is empty.');

        $student = Student::find(auth()->user()->member->member_id);
        
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

        $validator = Validator::make($request->all(), [            
            'trans_id' => 'required', 
            'payment_mode' => 'required|in:gcash,bank', 
            'amount' => 'required|gte:50|lte:' .  $student->balance->amount,                        
        ],[
            'trans_id.required' => 'The TRANSACTION ID is required.'
        ]);
    
        if ($validator->fails()){
            return redirect('/student/createpayment/')->withErrors($validator);
        }                
                 
        $payment_request = new PaymentRequest;

        $payment_request->student_id = $student->id;
        $payment_request->trans_id = $request->input('trans_id');
        $payment_request->payment_mode = $request->input('payment_mode');
        $payment_request->amount = $request->input('amount');

        if($request->hasFile('image')){

            $this->validate($request, [
                'image' => 'file|mimes:jpeg,jpg,png|max:3000'
            ]); 

            $imagewithExt = $request->file('image')->getClientOriginalName();
            $imageName = pathinfo($imagewithExt, PATHINFO_FILENAME);
            $imageExt = $request->file('image')->getClientOriginalExtension();
            $imageToStore = $imageName.'_'.time().'.'.$imageExt;
            $imagepath = $request->file('image')->storeAs('public/images/students/payment_requests', $imageToStore);

            $payment_request->image = $imageToStore;

        }

        if($request->has('desc'))
            $payment_request->desc = $request->input('desc');
    
        $payment_request->save();


         $year =  date("y");        
        $payment_request_ID = 'R'. $year . sprintf('%08d', $payment_request->id); 
        $payment_request->request_id =  $payment_request_ID;

        $payment_request->save();

        return redirect()->route('studentBalance')->with('success', 'Payment Request Submitted!');
  
    }

    public function approve(Request $request){  
        
        if($request->method() != "POST")
            return redirect()->back();

        $settings = Setting::first();
        $student = Student::find($request->input('stud_id'));
        $admin = Admin::find(auth()->user()->member->member_id);

        $student->dept = $student->department;    
        $student->program_desc = $student->program_id;
        $student->level_desc = $student->level;          
        
        $paymentrequest = PaymentRequest::find($request->input('payment_id')); 
        $paymentrequest->admin_id = $admin->id;
        $paymentrequest->status = 1;

        
        $invoice = new Invoice;           
        
        $invoice->student_id = $student->id;
        $invoice->admin_id = $admin->id;
        $invoice->balance = $student->balance->amount;

        $student->balance->amount -= $request->input('amount');                

        $invoice->payment = $request->input('amount');
        $invoice->payment_received = $request->input('amount');                
        
        $invoice->remaining_bal = $student->balance->amount;

        $year =  date("y");        
        $invoice->save();           
        $invoiceID = $year . '-' . sprintf('%08d', $invoice->id);   
        
        $invoice->invoice_id = $invoiceID;     
        
        $invoice->save();           
        $student->balance->save();                             
        $paymentrequest->save();
 
        return  redirect()->route('payment_request.view')                    
                    ->with('success_with_link', '<a href="' . url('/studentprofile/'. $student->student_id) . '">' . $student->first_name . ' ' . $student->last_name . '\'s </a> Payment Request is Approved. Payment Successful');
 
     }  

    public function reject(Request $request){  
        
        if($request->method() != "POST")
            return redirect()->back();

        $paymentrequest = PaymentRequest::find($request->input('payment_id'));
 
        $paymentrequest->admin_id = auth()->user()->member->member_id;
        $paymentrequest->status = 0;

        if($request->has('reject_cause'))
            $paymentrequest->reject_cause = $request->input('reject_cause');
 
        $paymentrequest->save();
 
        return  redirect()->route('payment_request.view')->with('info', 'Payment Request Rejected.');
 
     }  

    
}
