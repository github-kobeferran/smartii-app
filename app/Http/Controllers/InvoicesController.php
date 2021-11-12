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
use App\Exports\InvoicesExport;
use App\Exports\AdvancedInvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoicesController extends Controller
{
    
    public function store(Request $request){

        if($request->method() != 'POST')
        return redirect()->back();
        
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
        $payment = $request->input('amount_to_pay');        
        $payment_received = $request->input('amount_received');        
        $change = $request->input('change');

        if($payment > $balance->amount){
            return redirect()->route('adminPayment')            
            ->with('error', 'Payment must not be higher than the Student\'s Balance Amount');  
        }
        
        
        $invoice = new Invoice;           
        
        $invoice->student_id = $studID;
        $invoice->admin_id = $admin->id;
        $invoice->balance = $totalBalance;
        $invoice->payment = $payment;
        $invoice->payment_received = $payment_received;

        $remainingBal = $balance->amount - $payment;  

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
            $data = ['rem_bal' => $remainingBal, 'change' => $change, 'totalBalance' => $totalBalance, 'payment' => $payment, 'payment_received' => $payment_received];            
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

        $data = ['rem_bal' => $invoice->remaining_bal , 'change' => ($invoice->payment_received - $invoice->payment), 'totalBalance' => $invoice->balance, 'payment' => $invoice->payment, 'payment_received' => $invoice->payment_received];

        $pdf = PDF::loadView('pdf.receipt', compact('invoice', 'student', 'admin', 'settings', 'data'));
        return $pdf->stream( 'invoice.pdf');  

    }

    public function dailyMontlyYearlyExport(){        
        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester"; 

        return Excel::download(new InvoicesExport, 'SMARTII Invoice Daily, Monthly and Year as of A.Y.'. Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');
    }

    public function advancedExport($month, $year){

        $the_month = "";

        switch($month){
            case 0:
                $the_month = "All Months";
                break;
            case 1:
                $the_month = "January";
                break;
            case 2:
                $the_month = "February";
                break;
            case 3:
                $the_month = "March";
                break;
            case 4:
                $the_month = "April";
                break;
            case 5:
                $the_month = "May";
                break;
            case 6:
                $the_month = "June";
                break;
            case 7:
                $he_month = "July";
                break;
            case 8:
                $the_month = "August";
                break;
            case 9:
                $the_month = "September";
                break;
            case 10:
                $the_month = "October";
                break;
            case 11:
                $the_month = "November";
                break;
            case 12:
                $the_month = "December";
                break; 
        }

        if(Setting::first()->semester == 1)
            $semester = "First Semester";
        else 
            $semester = "Second Semester"; 

    return Excel::download(new AdvancedInvoicesExport($month,$year), 'SMARTII Invoice ' . $the_month . '-'. $year.  ' as of A.Y.'. Setting::first()->from_year . '-' . Setting::first()->to_year . '['. $semester .']'. '.xlsx');
    }

}
