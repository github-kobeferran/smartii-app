@extends('layouts.module')

@section('page-title')
    Payments
@endsection

@section('content')
    <div class="row">
        <div class="col-1 mr-3">
            <h5 class="mb-3">Payment </h5>
        </div>
        <div class="col ">
            <button type="button" data-toggle="modal" data-target="#invoices-export-modal" class="btn btn-sm btn-success">Export Invoices to Excel</button>
        </div>

        <div class="modal fade" id="invoices-export-modal" tabindex="-1" role="dialog" aria-labelledby="invoices-export-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="invoices-export-modal-title">INVOICES EXPORT </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>                            
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <a href="{{url('invoices/export')}}" class="btn btn-sm btn-success rounded-0">Current Day, Month and Year Invoices <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                            </div>
                            <div class="col">
                                <h6><i class="fa fa-caret-right"></i> Advanced</h6>

                                <div class="form-group">
                                    <label for="select-month-export">Month</label>
                                    {{Form::select('month', [
                                        0 => 'All Months',
                                        1 => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December',
                                    ], 0, [
                                        'id' => 'select-month-export',
                                        'class' => 'form-control-sm'
                                        ]
                                    )}}  
                                </div> 
                                <div class="form-group">
                                <?php 
                                    $invoices = \App\Models\Invoice::all();
                                    $invoices = $invoices->map(function ($invoice) {
                                        return \Carbon\Carbon::parse($invoice->created_at)->isoFormat('YYYY');
                                    });                                  
                                ?>
                                <label for="select-year-export">Year</label>
                                <select name="year" id="select-year-export">
                                    @foreach ($years as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                    @endforeach
                                </select>
                                </div>                     
                                <button type="button" onclick="generateInvoicesExport()" class="btn btn-sm btn-block btn-primary">Generate <i class="fa fa-file-excel-o" aria-hidden="true"></i></button>                                
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>                
        </div>
    </div>

    
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif

    @include('inc.messages')

    <div class="form-group has-search">
        <span class="fa fa-search form-control-feedback"></span>
        <input  id="student-search" type="text" class="form-control" placeholder="Search by Student ID or Name">
    </div>    
    
    <div class="table-responsive border shadow mb-5" style="max-height: 500px; overflow: auto; display:inline-block;">
        <table class="table table-striped border" >
            <thead class="">
                <tr class="">
                    <th scope="col" colspan="2" class="border-right bg-light text-center align-middle">Action</th>
                    <th scope="col" class="border-right bg-light" >Student ID</th>
                    <th scope="col" class="border-right bg-light">Name</th>
                    <th scope="col" class="border-right bg-light">Department and Program</th>           
                    <th scope="col" class="rounded bg-primary text-white">Balance</th>           
                </tr>
            </thead>
            <tbody id="students-table">                
                @if (!is_null(\App\Models\Student::first()))

                    @foreach (\App\Models\Student::orderBy('created_at', 'desc')->limit(100)->get() as $student)
                        <tr>
                            <td class="border-right"><button type="button"  onclick="selectForPayment('{{$student->id}}')" class="btn btn-info text-white ">Payment</button></td>
                            <td class="border-right"><button type="button"  onclick="showInvoicesTable('{{$student->id}}')" class="btn btn-warning text-secondary">Invoices</button></td>
                            <td class="border-right"><a href="{{url('/studentprofile/' . $student->student_id)}}"> {{$student->student_id}}</a></td>
                            <td class="border-right">{{$student->last_name . ', ' . $student->first_name . ' ' . (!is_null($student->middle_name) ? strtoupper(substr($student->middle_name, 0, 1)) . '.' : '') }}</td> 
                            <?php $student->level_desc = $student->level; ?>
                            <td class="border-right">{{($student->department? 'College' : ' SHS') . ' | ' . $student->program->desc . ' | ' . $student->level_desc }}</td>
                            <td class="border-left">&#8369; <b>{{number_format($student->balance->amount, 2)}}</b></td>
                        </tr>
                    @endforeach

                @else

                    <h5 class="mx-auto">No Students in the System.</h5>
                    
                @endif

            </tbody>
            
            <div class="loader-parent">
                <div class="dual-ring" style=""></div>
            </div>  
        </table>           
    </div>

<div class="container d-none" id="payment-panel">
    <div class="row no-gutters">
        {!! Form::open(['url' => 'admin/create/invoice', 'id' => 'paymentForm', 'target'=>"_blank"]) !!}
            <div class="col"><p class="h3">Payment for:</p></div>
            <div class="col d-flex d-flex justify-content-end "> 
                <div class="custom-control custom-switch text-right mx-1">
                    <input name="print_receipt" type="checkbox" class="custom-control-input" id="printSwitch" checked>
                    <label class="custom-control-label" for="printSwitch">
                        <strong>Generate Receipt</strong>
                    </label>                
                </div>

                <div class="text-right mx-1 mb-3 mr-0">
                    <button type="button" id="toggleDiscountButton" class="btn btn-light border">
                        Show Discounts                     
                    </button>
                </div>

            </div>
    </div>

    <div class="row">
        <div class="col">
            
            <div class="card shadow border-primary mb-2">
                <h4 id="stud-details" class="card-header"></h4>

                <div class="card-body">
                    <h4 id="stud-balance" class="card-title"></h4>            

                    <input id="stud-hidden" name="stud_id" type="hidden" class="">
                    <input id="stud-hidden-balance" name="balance" type="hidden" class="">
                    
                    <div>
                        <label class="py-0 my-0" for="">Amount to Pay</label>
                        <input id="payment-input" name="amount_to_pay" min="50" type="number" step="any" class="form-control form-control-lg text-right mb-2" placeholder="Input Payment Amount" required>    
                    </div>

                    <div>
                        <label class="py-0 my-0" for="">Amount Received</label>
                        <input id="payment-received-input" name="amount_received" style="font-family: 'Source Code Pro', monospace; font-size: 2em;" min="50" type="number" step="any" class="form-control form-control-lg text-right mb-2" placeholder="Input Received Payment Amount" required>                            
                    </div>
                                    

                    <div class="row no-gutters">
                        <div class="col-3">
                            <button type="submit" class="btn btn-success mr-2">Enter Payment <i class="fa fa-check text-white" aria-hidden="true"></i></button>
                        </div>
                        
                        <div class="col-3">
                            <button type="button" onclick="cancelPayment()" class="btn btn-warning">Cancel <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></button>
                        </div>

                        <div class="col text-right">                    
                            <h4 id="change-output" class="d-none" >Change: </h4>                                        
                        </div>                                        
                    </div>

                    {{Form::hidden('change', 0, ['id' => 'change-hidden'])}}

                </div>
            </div>
        </div>

        {!! Form::close() !!}


        <div class="col d-none" id="discounts-panel">

            <div class="table-responsive">

                <div class="row">
                    <div class="col text-left mb-0">
                        <h5>Discounts Table</h5>
                    </div>
                    <div class="col text-right">
                        <button type="button" data-toggle="modal" data-target="#create-discount" class="btn btn-outline-success rounded-0 text-dark mb-2">Add a Discount</button>
                    </div>                    
                </div>

                <div class="modal fade" id="create-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                        <h6 class="modal-title" id="exampleModalLongTitle">Create a Discount</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        {{Form::open(['url' => '/storediscount'])}}
                            <div class="modal-body">                                                                                                

                                <div class="form-group">
                                    <label for="">Discount Description</label>
                                    {{Form::text('description', '', ['class' => 'form-control rounded-0', 'required' => 'required', 'maxlength' => '100'])}}
                                </div>
                                <div class="form-group">
                                    <label for="">Discount Percentage</label>
                                    {{Form::number('percentage', 0.01, ['step' => 'any', 'min' => '0.01', 'max' => '100', 'class' => 'form-control rounded-0 w-25', 'required' => 'required'])}}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        {{Form::close()}}
                    </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped text-center shadow">
                    <thead>
                        <tr class="bg-success">
                            <td>Description</td>
                            <td>Percent</td>
                            <td>Student Count</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody >                        
                        @empty(\App\Models\Discount::all())

                            <div class="text-center">
                                <em>No Discounts yet. Add one</em>
                            </div>

                        @else

                            @foreach (\App\Models\Discount::all() as $discount)
                                
                                <tr>
                                    <td>{{$discount->description}}</td>
                                    <td>{{number_format($discount->percentage, 1)}} %</td>
                                    <td><span class="badge badge-info text-white" type="button" data-toggle="modal" data-target="#student-list-{{$discount->id}}">{{$discount->students->count()}}</span></td>                                    
                                    <td >
                                        <button type="button" data-toggle="modal" data-target="#edit-discount-{{$discount->id}}" class="btn btn-primary my-1">Edit</button>
                                        <button type="button" data-toggle="modal" data-target="#delete-discount-{{$discount->id}}" class="btn btn-danger my-1">Delete</button>                                        
                                    </td>

                                    <div class="modal fade" id="student-list-{{$discount->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                            <h6 class="modal-title" id="exampleModalLongTitle">Students with {{$discount->description}} attachment</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>

                                            <div class="modal-body p-1">                                                
                                                <ul class="list-group">
                                                    @if ($discount->students->count() > 0)
                                                        @foreach ($discount->students as $student_rel)                                                            
                                                            <li class="list-group-item">

                                                                <div class="row border my-2 mx-auto py-2 ">

                                                                    <div class="col border-right">
        
                                                                        <span class="text-left " >
                                                                            <a href="{{url('/studentprofile/' . $student_rel->student->student_id)}}">{{$student_rel->student->student_id}}</a>
                                                                        </span>
                                                                        
                                                                    </div>
                                                                    <div class="col text-left border-right">
                                                                        
                                                                        <span >
                                                                            {{ucfirst($student_rel->student->first_name) . ' ' . ucfirst($student_rel->student->last_name)}}
                                                                        </span>
        
                                                                    </div>
                                                                    <div class="col text-center">
                                                                        
                                                                        <span >
                                                                            {{$student_rel->student->program->abbrv}}
                                                                        </span>
        
                                                                    </div>
        
                                                                    
                                                                </div>  
                                                                
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <p class="mx-auto">No students attached to this discount</p>
                                                    @endif
                                                </ul>
                                            </div>
                                        
                                        </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="edit-discount-{{$discount->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                            <h6 class="modal-title" id="exampleModalLongTitle">Edit {{$discount->description}}</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            {{Form::open(['url' => '/updatediscount'])}}
                                                <div class="modal-body">
                                                    {{Form::hidden('id', $discount->id)}}

                                                    <div class="bg-warning p-1 rounded">
                                                        <h5>WARNING</h5>
                                                        This will <b>NOT</b> update attached student's balances. <br>
                                                        But will <b>UPDATE</b> future student who wil be applied to this discount
                                                    </div>

                                                    <hr>

                                                    <div class="form-group">
                                                        <label for="">Discount Description</label>
                                                        {{Form::text('description', $discount->description, ['class' => 'form-control rounded-0', 'required' => 'required', 'maxlength' => '100'])}}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Discount Percentage</label>
                                                        {{Form::number('percentage', $discount->percentage, ['step' => 'any', 'min' => '0.01', 'max' => '100', 'class' => 'form-control rounded-0 w-25', 'required' => 'required'])}}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            {{Form::close()}}
                                        </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="delete-discount-{{$discount->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title" id="exampleModalLongTitle">Delete {{$discount->description}}</h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            {{Form::open(['url' => '/deletediscount'])}}
                                                <div class="modal-body">
                                                    {{Form::hidden('id', $discount->id)}}
                                                    <div class="bg-warning p-1 rounded">
                                                        <h5>WARNING</h5>
                                                        This will <b>NOT</b> update student's balances. <br>
                                                        But <b>WILL</b> delete any student-discount attatchments related to it. <br>
                                                        <br>
                                                        You have to remove it to each student one by one if you want to update student balance.
                                                    </div>
                                                    <b>Do you want to continue to delete this discount? </b>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Yes</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            {{Form::close()}}
                                        </div>
                                        </div>
                                    </div>

                                </tr>

                            @endforeach

                        @endempty
                    </tbody>                                        
                </table>
            </div>

        </div>

    </div>


</div>

<div id="invoices-table" class="d-none">
    
    <h5>Kobe Ferran's Invoices</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Payment Date</th>
                <th>Payment Amount</th>            
            </tr>

        </thead>

        <tbody>
            <tr>
         
            </tr>

        </tbody>

    </table>

</div>


<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

let paymentPanel = document.getElementById('payment-panel');
let paymentForm = document.getElementById('paymentForm');

let paymentInput = document.getElementById('payment-input');;
let paymentReceivedInput = document.getElementById('payment-received-input');
let changeHiddenInput = document.getElementById('change-hidden');

let balanceOutput = document.getElementById('stud-balance');
let balance_amount = 0;

let changeOutput = document.getElementById('change-output');

let change = 0;
let printSwitch = document.getElementById('printSwitch'); 
let toggleDiscountButton = document.getElementById('toggleDiscountButton');
let discountsPanel = document.getElementById('discounts-panel');
let studentSearchInput = document.getElementById('student-search');

studentSearchInput.addEventListener('keyup', studentSearch);

    
paymentInput.addEventListener("keypress", function (evt) {
    if (evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
});

paymentInput.addEventListener('input', () => {
    paymentReceivedInput.setAttribute('min', paymentInput.value);
    calculateChange();
});

paymentInput.addEventListener('keyup', () => {
        paymentReceivedInput.setAttribute('min', paymentInput.value);
        calculateChange();
    });

paymentReceivedInput.addEventListener("keypress", function (evt) {
    if (evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
}); 

paymentReceivedInput.addEventListener('input', calculateChange);  
paymentReceivedInput.addEventListener('keyup', calculateChange); 

printSwitch.addEventListener('click', () => {  
    if(printSwitch.checked)
        paymentForm.target = "_blank";
    else
        paymentForm.target = "";
});

toggleDiscountButton.addEventListener('click', () => {

    if(toggleDiscountButton.classList.contains('active')){        
        toggleDiscountButton.classList.remove('active');
        toggleDiscountButton.textContent = "Show Discounts";            
        discountsPanel.classList.add('d-none');
    } else {
        toggleDiscountButton.classList.add('active');
        toggleDiscountButton.textContent = "Hide Discounts";
        discountsPanel.classList.remove('d-none');
        
    }      
});

function studentSearch(){

    txt = studentSearchInput.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/search/students/' + txt, true);

    document.getElementsByClassName('dual-ring')[0].style.display = 'inline-block';

    xhr.onload = function() {

    

    if (this.status == 200) {

       let students = JSON.parse(this.responseText);

            output = '<tbody id="students-table">';


            for (let i in students) {

                let level = "";
                let department = "";
                
                if(students[i].department == 0){

                    if(students[i].level == 1){
                        level = "Grade 11";
                    } else if(students[i].level == 2) {
                        level = "Grade 12";
                    }
                    
                    department = "SHS";
                } else{

                    if(students[i].level == 11){
                        level = "First Year";
                    } else if(students[i].level == 12) {
                        level = "Second Year";
                    }

                    department ="College";
                    
                }
                output += '<tr>' +

                    '<td class="border-right"><button type="button"  onclick="selectForPayment(' +students[i].id + ')" class="btn btn-info text-white border">Payment</button></td>' + 
                    '<td class="border-right"><button type="button"  onclick="showInvoicesTable(' +students[i].id + ')" class="btn btn-warning text-secondary">Invoices</button></td>' + 
                    '<td>' + students[i].student_id + '</td>' +
                    '<td>' + students[i].last_name + ', ' +students[i].first_name + ', ' + students[i].middle_name.charAt(0).toUpperCase() + '</td>' +
                    '<td>' + department + ' | ' + students[i].program_desc + ' | ' + level + '</td>' +                    
                    '<td class="border-left"><b>&#8369; ' + students[i].balance_amount + '</b></td>' +

                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

        document.getElementsByClassName('dual-ring')[0].style.display = 'none';
        document.getElementById('students-table').innerHTML = output;


    } else if (this.status == 404) {
        let output = 'not found';
        document.getElementById('students-table').innerHTML = output;
    }
}

xhr.send();


}

function selectForPayment(id){    

    paymentPanel.classList.remove('d-none');
    let invoicesTable = document.getElementById('invoices-table');

    invoicesTable.className = "d-none";

    let studDetails = document.getElementById('stud-details');
    let studBalance = document.getElementById('stud-balance');
    let studHidden = document.getElementById('stud-hidden');
    let studHiddenBalance = document.getElementById('stud-hidden-balance');
    
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/students/' + id, true);

    xhr.onload = function() {    

        if (this.status == 200) {

            let student = JSON.parse(this.responseText);

            studDetails.textContent = student.student_id + " - " + student.first_name +
            " " + student.middle_name.charAt(0).toUpperCase() + ". " + student.last_name +
            " [" + student.program_desc + "]";
            studBalance.innerHTML = `<h4 id="stud-balance" class="card-title text-right py-2">
                                        <strong class="border-bottom">Total Balance : &#8369; ` +
                            student.balance_amount.toFixed(2) + `</strong>
                </h4>`; 

            if(student.balance_amount < 1){
                paymentInput.required = false;
            } else {
                paymentInput.required = true;
            }

            studHidden.value = student.id;
            studHiddenBalance.value = student.balance_amount;
            paymentInput.setAttribute('max', student.balance_amount);
            paymentReceivedInput.setAttribute('max', student.balance_amount);

            balance_amount = student.balance_amount;                       

            paymentPanel.scrollIntoView({behavior: 'smooth'});
                       
        } 
    }

    xhr.send();    


}

function cancelPayment(){

    paymentPanel.classList.add('d-none');

    document.getElementById('stud-hidden').value = "";
    paymentInput.value = "";
    change = 0;
    changeOutput.classList.add('d-none');

    

}

function calculateChange(){      

    if(paymentReceivedInput.value > 0)  {

        let payment_amount = Number(paymentInput.value);
        let payment_received_amount = Number(paymentReceivedInput.value);           

        if(payment_received_amount >= payment_amount){

            let remainingBal = 0;
            let change = 0;

            if(balance_amount > 0){
                change = payment_received_amount - payment_amount;
                remainingBal = balance_amount - payment_amount;
            } 

            changeOutput.classList.remove('d-none');

            if(change >= 0){
                changeOutput.innerHTML = `<h4 id="change-output" class="mr-2 border border-dark rounded-0 p-2" >Change: &#8369; `+ change.toFixed(2) +` </h4>`;
            }      

            changeHiddenInput.value = change;  

        } else {       
            changeOutput.classList.remove('d-none');     
            changeOutput.innerHTML = `<h6 id="change-output" class="mr-2 text-danger" >Amount Received must be greater than or equal to Amount to Pay</h6>`;
        }

    } else {
        changeOutput.classList.add('d-none'); 
    }

}


function showInvoicesTable(id){    

    paymentForm.style.display = "none";

    let invoicesTable = document.getElementById('invoices-table');

    invoicesTable.className = "";

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/invoices/student_id/' + id, true);

    xhr.onload = function() {    

    if (this.status == 200) {

        

        let invoices = JSON.parse(this.responseText);
        console.log(invoices);

        let output = '<div id="invoices-table" class="">';

        if(typeof invoices[0] !== 'undefined'){

            output+='<h5>'+ invoices[0].stud_name +'\'s Invoices</h5>';

            output+='<table class="table table-bordered">';
            output+='<thead>';
                output+='<tr>';
                    output+='<th>Invoice ID</th>';
                    output+='<th>Payment Date</th>';
                    output+='<th>Payment Amount</th>';
                output+='</tr>';
            output+='</thead>';
            output+='<tbody>';

                for(let i in invoices){

                    output+='<tr>';
                        output+='<td><a href="' + APP_URL + '/invoice/' + invoices[i].invoice_id +'" target="_blank">#'+ invoices[i].invoice_id +'</a></td>';
                        output+='<td>'+ invoices[i].formatted_date +'</td>';
                        output+='<td>&#8369; '+ invoices[i].payment.toFixed(2) +'</td>';
                    output+='<tr>';
                }
                
            output+='</tbody>';
            output+='</table>';

            output+='</div>';
            
        }else{
            output+= 'No Invoices for this Student yet.';
        }        

        invoicesTable.innerHTML = output;
                    
    } 
}

xhr.send();    


}

function generateInvoicesExport(){
    window.location.href = APP_URL + `/advancedinvoices/export/${document.getElementById('select-month-export').value}/${document.getElementById('select-year-export').value}`
}

</script>

@endsection