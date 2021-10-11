<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Balance;
use App\Models\Invoice;
use App\Models\SubjectTaken;
use App\Models\Setting;
use App\Mail\WelcomeMember;
use PDF;
use Carbon\Carbon;

class InvoicesController extends Controller
{
    
    public function store(Request $request){
         
        // ->format('g:i a l jS F Y');

        // inititalize Admin
        $admin = Admin::find(auth()->user()->member->member_id);                
                
        $date = Carbon::now();

        // inititalize Student
        $studID = $request->input('stud_id');
        $student = Student::find($studID);        

        $student->dept = $student->department;    
        $student->program_desc = $student->program_id;
        $student->level_desc = $student->level;        

        // get Balance rel
        $balance = Balance::find($student->balance_id);
        

        // get Settings
        $settings = Setting::first();
        
        // get payment input details
        $totalBalance = $request->input('balance');
        $payment = $request->input('amount');        

        $change = 0;
        $remainingBal = 0;

        if($payment > $totalBalance){
            $change = $payment - $totalBalance;
        } else {
            $remainingBal = $totalBalance - $payment;
        }
        
        $invoice = new Invoice;           

        $invoice->student_id = $studID;
        $invoice->admin_id = $admin->id;
        $invoice->balance = $totalBalance;
        $invoice->payment = $payment;
        $invoice->remaining_bal = $remainingBal;

        $balance->amount = $remainingBal;
        $balance->save();

        $invoice->save();

        $year =  date("y");        
        $invoiceID = $year . '-' . sprintf('%08d', $invoice->id);   

        $invoice->invoice_id = $invoiceID;        

        $invoice->save();
        
        if($request->input('print_receipt')){

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);                        
            $data = ['rem_bal' => $remainingBal, 'change' => $change, 'totalBalance' => $totalBalance, 'payment' => $payment];            
            $pdf = PDF::loadView('pdf.receipt', compact('invoice', 'student', 'admin', 'settings', 'data'));
            return $pdf->stream( 'invoice.pdf');  

        } else {
            return redirect()->route('adminPayment')            
            ->with('success', 'Payment Successful');  
        }
         
    }

    public function show($invoice_id){                

        if(Invoice::where('invoice_id', $invoice_id)->doesntExist()){
            return redirect()->back();
        } 

        $user_id = auth()->user()->id;

        $invoice =  Invoice::where('invoice_id', $invoice_id)->first();

        if($user_id != $invoice->student->member->user->id){            
            if(auth()->user()->isAdmin() == false )
                return redirect()->back();
        }      

        $student = $invoice->student;
        $student->dept = $student->department;    
        $student->program_desc = $student->program_id;
        $student->level_desc = $student->level;

        $admin = $invoice->admin;
        $settings = Setting::first();

        $change = 0;

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);                        

        $data = ['rem_bal' => $invoice->remaining_bal, 'change' => ($invoice->payment > $invoice->balance ? $invoice->balance - $invoice->payment : 0), 'totalBalance' => $invoice->balance, 'payment' => $invoice->payment];

        $pdf = PDF::loadView('pdf.receipt', compact('invoice', 'student', 'admin', 'settings', 'data'));
        return $pdf->stream( 'invoice.pdf');  

    }

}
