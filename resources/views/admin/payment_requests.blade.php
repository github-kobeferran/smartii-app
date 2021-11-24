@extends('layouts.module')

@section('page-title')
    Payment Requests    
@endsection

@section('content')

<div class="row mb-2 ">
    <div class="col text-left">
        <a href="{{url("/admin/payment")}}" class="btn btn-sm smartii-bg-dark text-white "><i class="fa fa-caret-left"></i> Back to Payments</a>

    </div>
</div>

<div class="row mb-2">
    <div class="col-3">
        <h5 class="align-middle">Payment Requests</h5> 
    </div>
    <div class="col pb-2">
        <button id="history-button" type="button" onclick="viewHistoryPanel(document.getElementById('history-button'))" class="btn btn-sm btn-primary">
            View Payment History <i class="fa fa-sort-desc ml-1" aria-hidden="true"></i>
        </button>
    </div>
</div>

<div class="input-group">

</div>

@include('inc.messages')
	    
<div class="tab-content clearfix">            

    <div id="requests-panel" class="row no-gutters vh-100 border-top">
        
        <div class="col-5 border-right">
                              
            <div id="requests-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group mt-2">                               
                
                <h5 class="mx-auto mt-5">Select a Payment Request</h5>
    
            </div>
            
        </div>    
        
    
        <div class="col-sm-7">        
    
            <div id="ripple" class="text-center align-middle d-none">
               
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
    
            </div>
    
       
            <div id="requestDataPanel" class="text-center align-middle">                                                               
    
                @if(\App\Models\PaymentRequest::pendingRequestCount() > 0)
                    <h5 class="mx-auto mt-5">Select a Payment Request</h5>
                @else
                    <h5 class="mx-auto mt-5">No Pending Requests</h5>
                @endif
    
            </div>                       
    
        </div>

    </div>

    <div id="history-panel" class="row d-none">
        
        <div class="col ml-2">

            <h6> History <i class="fa fa-history" aria-hidden="true"></i></h6>

            @if (!is_null(\App\Models\PaymentRequest::first()))

                <div class="table-responsive" style="max-height: 600px; overflow: auto; display:inline-block;">
                    <table class="table table-bordered">
                        <caption>Payment Request Transactions that have been either Approved or Rejected by an Admin</caption>
                        <thead>
                            <tr>
                                <th class="bg-primary">Request ID</th>
                                <th class="bg-primary">Student</th>
                                <th class="bg-info">Transaction ID</th>
                                <th class="bg-info">Mode</th>
                                <th class="bg-info">Amount</th>
                                <th class="bg-info">Image Proof</th>
                                <th class="bg-info">Description</th>
                                <th class="bg-info">Status</th>
                                <th class="bg-primary">Marked by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\PaymentRequest::whereNotNull('status')->get()  as $request)
                                <tr>
                                    <td>{{$request->request_id}}</td>
                                    <?php 
                                        $student = \App\Models\Student::find($request->student_id);
                                    ?>
                                    <td><a target="_blank" href="{{url('/studentprofile/' . $student->student_id)}}">{{$student->first_name}} {{$student->last_name}}</a></td>
                                    <td>{{$request->trans_id}}</td>
                                    <td>{{strtoupper($request->payment_mode)}}</td>
                                    <td>&#8369; {{number_format($request->amount, 2)}}</td>
                                    <td>
                                        @if (!is_null($request->image))
                                            <button type="button" data-toggle="modal" data-target="#image-modal-{{$request->id}}" class="btn btn-sm btn-primary">View Image</button>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if (!is_null($request->desc))
                                            <span class="text-muted">{{$request->desc}}</span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if ($request->status)
                                            <span class="text-success">APPROVED</span>
                                        @else
                                            <span class="text-danger">REJECTED</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($request->status)
                                            {{$request->admin->admin_id}} - {{$request->admin->name}}
                                            ({{\Carbon\Carbon::parse($request->updated_at)->format('M d Y h:i A')}})
                                        @else
                                            {{$request->admin->admin_id}} - {{$request->admin->name}}
                                            ({{\Carbon\Carbon::parse($request->updated_at)->format('M d Y h:i A')}})
                                            <br>
                                            <em class="text-danger">cause: {{!is_null($request->reject_cause) ? $request->reject_cause : 'not specified'}}</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            @else
                
                <div class="mx-auto">
                    <em>No payment request approved/rejected yet.</em>
                </div>

            @endif

        </div>

    </div>
                  
</div>



<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

let history_panel = document.getElementById('history-panel');
let requests_panel = document.getElementById('requests-panel');

function viewHistoryPanel(btn){
    if(history_panel.classList.contains('d-none')){
        history_panel.classList.remove('d-none');
        requests_panel.classList.add('d-none');
        btn.textContent = "Hide Payment History";
        btn.className = 'btn btn-sm btn-secondary';
    } else {
        history_panel.classList.add('d-none');
        requests_panel.classList.remove('d-none');
        btn.textContent = "View Payment History";
        btn.className = 'btn btn-sm btn-primary';
    }
}


function togglePaymentRequest(id){

    let buttons = document.getElementsByClassName('pr-button');
    let btn = document.getElementById('pr-' + id);    
    let requestDataPanel = document.getElementById('requestDataPanel');    

    for(i=0; i<buttons.length; i++){
        buttons[i].classList.remove('active');           
    }  

    btn.classList.add('active');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/paymentrequests/' + id, true);

    document.getElementById('ripple').className="text-center align-middle";
    
    xhr.onload = function() {
        if (this.status == 200) {

            let paymentrequest = JSON.parse(this.responseText);

            output = `<div id="requestDataPanel" class="text-center align-middle >`;
            output = `<h5>Payment Request Details </h5`;
                output +=`<div class="col-sm">`;
                    output +=`<table class="table table-bordered mt-4 ml-1">`; 
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Payment Mode</td>`;
                            output +=`<td>`;   

                                output += ucfirst(paymentrequest.payment_mode);
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;  
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Payment Amount</td>`;
                            output +=`<td> &#8369; `;   

                                output += paymentrequest.amount.toFixed(2);
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;  
                        output +=`<tr>`;   
                            output +=`<td class="bg-primary text-white">Transaction ID</td>`;
                            output +=`<td>`;   

                                output += ucfirst(paymentrequest.trans_id);
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;  
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Student Name</td>`;
                            output +=`<td>`;   

                                output += paymentrequest.stud_name;
                        
                            output += `</td>`;                            
                        output += `</tr>`;
                        output +=`<tr>`;   
                            output +=`<td class="bg-primary text-white">Student ID</td>`;
                            output +=`<td><a target="_blank" href="${APP_URL}/studentprofile/${paymentrequest.stud_id}">${paymentrequest.stud_id}</a></td>`;                                                     
                        output += `</tr>`;
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Department</td>`;
                            output +=`<td>`;   

                                output += paymentrequest.stud_dept;
                        
                            output += `</td>`;                                                                              
                        output += `</tr>`;
                        output +=`<tr>`;   
                            output +=`<td class="bg-primary text-white">Program</td>`;
                            output +=`<td>`;   

                                output += paymentrequest.stud_prog;
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Address</td>`;
                            output +=`<td>`;   

                                output += paymentrequest.stud_address;
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;            
                        if(paymentrequest.desc != null){
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Description</td>`;
                            output +=`<td>`;   

                                output += paymentrequest.desc;
                        
                            output += `</td>`;                                                                               
                        output += `</tr>`;
                        }
                        if(paymentrequest.image != null){
                            output +=`<tr>`;   
                                output +=`<td class="bg-primary text-white">Image</td>`;
                                output +=`<td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment-image">
                                                View Image
                                            </button>`;                                     
                                output += `</td>`;                                                                               
                            output += `</tr>`;
                        }                                                                                 
                        if(paymentrequest.desc != null){
                            output +=`<tr>`;   
                                output +=`<td class="bg-primary text-white">Description</td>
                                          <td>
                                                ${paymentrequest.desc}
                                         </td>
                                      </tr>`;
                        }                                                                                 
                       
                    output += `</table>`;

                    output +=`
                            <button role="button" data-toggle="modal"  data-target="#approve-request-${paymentrequest.id}" class="btn btn-success btn-block rounded-0"> 
                                Approve
                            </button>
                            <button role="button" data-toggle="modal"  data-target="#reject-request-${paymentrequest.id}" class="btn btn-danger btn-block rounded-0"> 
                                Reject
                            </button>

                            <div class="modal fade" id="approve-request-${paymentrequest.id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">APPROVE PAYMENT REQUEST</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        {!! Form::open(['url' => 'admin/approvepaymentrequest/']) !!}
                                    <div class="modal-body">
                                        <div class="text-left text-dark">
                                            <u>Approve</u> ${paymentrequest.stud_name}'s payment request of &#8369; ${paymentrequest.amount.toFixed(2)}
                                        </div>
                                        <br>
                                        <div class="text-left text-dark">
                                            Approving will process a payment and invoice to update ${paymentrequest.stud_name}'s  balance. Continue?
                                        </div>
                                                 
                                        <input type="hidden" name="amount" value="${paymentrequest.amount}"/>
                                        <input type="hidden" name="payment_id" value="${paymentrequest.id}"/>
                                        <input type="hidden" name="stud_id" value="${paymentrequest.student_id}"/>
                 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Yes</button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                    </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        
                            <div class="modal fade" id="reject-request-${paymentrequest.id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h6 class="modal-title text-white" id="exampleModalCenterTitle">REJECT PAYMENT REQUEST</h6>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        {!! Form::open(['url' => 'admin/rejectpaymentrequest/']) !!}
                                    <div class="modal-body">
                                        <div class="text-left text-dark">
                                            <u>Reject</u> ${paymentrequest.stud_name}'s payment request of &#8369; ${paymentrequest.amount.toFixed(2)}
                                        </div>
                                        <br>
                                        <div class="form-group text-left text-dark">
                                            <p>Please indicate why ${paymentrequest.stud_name}'s  request was rejected. (optional)</p>
                                            <input type="text" class="form-control" name="reject_cause" value="" placeholder="Enter the Request Rejection Cause"/>
                                        </div>
                                                 
                                        <input type="hidden" name="payment_id" value="${paymentrequest.id}"/>
                 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        
                         
                    
                    </div>
                    
                </div>
            
            <div class="modal fade" id="payment-image" tabindex="-1" role="dialog" aria-labelledby="payment-imageTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">  
                        <h5 class="modal-title" id="exampleModalLongTitle">Proof of Transaction of ${paymentrequest.stud_name}'s payment via ${paymentrequest.payment_mode.toUpperCase()}</h5>                    
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img class="img-thumbnail" src="{{url('/storage/images/students/payment_requests/`+ paymentrequest.image +`')}}"
                    </div>
                    <div class="modal-footer">
                        
                    </div>
                    </div>
                </div>
            </div>
            
            `;
            
            document.getElementById('ripple').className="text-center align-middle d-none";
            requestDataPanel.innerHTML = output;

        } else {
            output = 'Huh, No Requests';
            requestDataPanel.innerHTML = output;
        }
    }

    xhr.send();

    
}


function viewPaymentRequests(){    

    let requestsList = document.getElementById('requests-list');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/paymentrequests/', true);
    
    xhr.onload = function() {
    if (this.status == 200) {

        let paymentrequests = JSON.parse(this.responseText);

    output = `<div id="requests-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group  mt-2">`;

    for(i in paymentrequests){
    
output +='<button id="pr-'+ paymentrequests[i].id +'" onclick="togglePaymentRequest('+ paymentrequests[i].id +')" class="list-group-item list-group-item-action flex-column align-items-start pr-button">';

    output +='<div class="d-flex w-100 justify-content-between">';
        output +='<h5 class="mb-1">'+ paymentrequests[i].stud_name +'</h5>';
        output += '<small>'+ paymentrequests[i].time_ago +'</small>'; 
        output += '<small>'+ ucfirst(paymentrequests[i].payment_mode) +'</small>'; 
    output += '</div>'; 
        
    output += '<p class="mb-1">'+ paymentrequests[i].stud_dept +'</p>';                 
    output += '<p class="mb-1">'+ paymentrequests[i].stud_prog +'</p>';                 
output += '</button>';
    
    }                                              

    output +='</div>';

        requestsList.innerHTML = output;

    } else {
        output = 'Huh, No Requests';
        requestsList.innerHTML = output;
    }
    }

    xhr.send();

}

window.addEventListener('load', (event) => {         
    
    viewPaymentRequests();

            
});
    
</script>
@endsection