@extends('layouts.module')

@section('page-title')
    Payments
@endsection

@section('content')
    <h5 class="mb-3">Payment</h5>

    
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
    
    <div class ="table-responsive border shadow mb-5" style="max-height: 500px; overflow: auto; display:inline-block;">
        <table class="table table-striped border" >
            <thead style="">
                <tr>
                    <th scope="col" colspan="2" class="border-right bg-light text-center align-middle">Action</th>
                    <th scope="col" class="border-right bg-light" >Student ID</th>
                    <th scope="col" class="border-right bg-light">Name</th>
                    <th scope="col" class="border-right bg-light">Department and Program</th>           
                    <th scope="col" class="rounded bg-primary text-white">Balance</th>           
                </tr>
            </thead>
            <tbody id="students-table">
    
            </tbody>
            
            <div class="loader-parent">
                <div class="dual-ring" style=""></div>
            </div>  
        </table>           
    </div>


{!! Form::open(['url' => 'admin/create/invoice', 'id' => 'paymentForm',  'target'=>"_blank"]) !!}

    <div class="row no-gutters">
        <div class="col"><p class="h3">Payment for:</p></div>
        <div class="col "> 
            <div class="custom-control custom-switch text-right">
                <input name="print_receipt" type="checkbox" class="custom-control-input" id="printSwitch" checked>
                <label class="custom-control-label" for="printSwitch">
                    <strong>Generate Receipt</strong>
                </label>
            </div>
        </div>
    </div>


    <div class="card shadow border-primary">
        <h4 id="stud-details" class="card-header"></h4>

        <div class="card-body">
            <h4 id="stud-balance" class="card-title"></h4>
            <hr class="">

            <input id="stud-hidden" name="stud_id" type="hidden" class="">
            <input id="stud-hidden-balance" name="balance" type="hidden" class="">
            <input id="payment-input" name="amount" min="50" type="number" step="any" class="form-control form-control-lg text-right mb-2" placeholder="Input Payment Amount" required>
            
            <hr>

            <div class="row no-gutters">

                <div class="col-3">
                    <button type="submit" class="btn btn-success mr-2">Enter Payment <i class="fa fa-check text-white" aria-hidden="true"></i></button>
                </div>
                
                <div class="col-3">
                    <button type="button" onclick="cancelPayment()" class="btn btn-warning">Cancel <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></button>
                </div>

                <div class="col text-right">
                    <h4 id="change-output" class="" >Change: </h4>
                    
                </div>                        
            </div>
        </div>
    </div>

{!! Form::close() !!}
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
                {{-- <td><a href="">21-00000213</a></td>
                <td>Jun 18 2021 1:54 PM</td>
                <td>Php 2000</td>       --}}
            </tr>

        </tbody>

    </table>

</div>
@endsection

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

let paymentForm;
let paymentInput;
let balanceOutput;
let balance_amount = 0;
let changeOutput;
let change = 0;
let printSwitch;

window.addEventListener('load', (event) => {         
        

    studentsAjax();
    document.getElementById('student-search').addEventListener('keyup', studentSearch);
   
    paymentForm = document.getElementById('paymentForm');
    paymentForm.style.display = "none";

    paymentInput = document.getElementById('payment-input');  
    paymentInput.addEventListener('input', calculateChange);  

    balanceOutput = document.getElementById('stud-balance');
    changeOutput = document.getElementById('change-output');
    changeOutput.style.display = "none";

    printSwitch = document.getElementById('printSwitch');

    printSwitch.addEventListener('click', () => {  

        if(printSwitch.checked)
            paymentForm.target = "_blank";
        else
            paymentForm.target = "";

    });

}); 




function studentsAjax() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/students', true);
    

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

                    '<td class="border-right"><button type="button"  onclick="selectForPayment(' +students[i].id + ')" class="btn btn-info text-white ">Payment</button></td>' + 
                    '<td class="border-right"><button type="button"  onclick="showInvoicesTable(' +students[i].id + ')" class="btn btn-warning text-secondary">Invoices</button></td>' + 
                    '<td class="border-right"><a href="/studentprofile/'+ students[i].student_id + '">' + students[i].student_id + '</a></td>' +
                    '<td class="border-right">' + students[i].last_name + ', ' + students[i].first_name + ' ' + students[i].middle_name.charAt(0).toUpperCase() + '.' + '</td>' +
                    '<td class="border-right">' + department + ' | ' + students[i].program_desc + ' | ' + level + '</td>' +                    
                    '<td class="border-left">&#8369;  <b>' + students[i].balance_amount + '</b></td>' +

                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

            document.getElementById('students-table').innerHTML = output;

        } else {
            let output = 'loading...';
            document.getElementById('students-table').innerHTML = output;
        }
    }

    xhr.send();
}



function studentSearch(){

    txt = document.querySelector('#student-search').value;

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

                    '<td class="border-right"><button type="button"  onclick="selectForPayment(' +students[i].id + ')" class="btn btn-info text-white border">Select</button></td>' + 
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

    paymentForm.style.display = "inline-block";
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
            studBalance.innerHTML = `<h4 id="stud-balance" class="card-title float-right "><strong>Total Balance : &#8369; ` +
                student.balance_amount + `</strong></h4>`; 

            if(student.balance_amount < 1){
                paymentInput.required = false;
            } else {
                paymentInput.required = true;
            }

            studHidden.value = student.id;
            studHiddenBalance.value = student.balance_amount;

            balance_amount = student.balance_amount;
                       
        } 
    }

    xhr.send();    


}

function cancelPayment(){

    paymentForm.style.display = "none";

    document.getElementById('stud-hidden').value = "";
    paymentInput.value = "";
    change = 0;
    changeOutput.style.display = "none";

}

function calculateChange(){    

    if(paymentInput.value > 0 ){

        let payment_amount = paymentInput.value;
        let remainingBal = 0;

        if(balance_amount > 0 ){
            change = payment_amount - balance_amount;
            remainingBal = balance_amount - payment_amount;
        }

        if(change >= 0){
            changeOutput.style.display = "block";        
            changeOutput.innerHTML = `<h4 id="change-output" class="" >Change: &#8369; `+ change.toFixed(2) +` </h4>`;
        }else{
            changeOutput.style.display = "block";        
            changeOutput.innerHTML = `<h4 id="change-output" class="" >Remaining Balance: &#8369; `+ remainingBal.toFixed(2) +` </h4>`;           
        }            

    } else {

        changeOutput.style.display = "none";

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



</script>

