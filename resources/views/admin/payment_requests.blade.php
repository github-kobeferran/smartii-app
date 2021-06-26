@extends('layouts.module')


@section('content')

<h5>Payment Requests</h5>

	    
<div class="tab-content clearfix">            

    <div class="row no-gutters vh-100">
        
        <div class="col-5 border-right">
                              
            <div id="requests-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group mt-2">                               
                
                <h5 class="mx-auto mt-5">Select a Payment Request</h5>
    
            </div>
            
        </div>
    
        
    
        <div class="col-sm-7">        
    
            <div id="ripple" class="text-center align-middle d-none">
    
                <div class="lds-ripple">
                    <div></div>
                    <div></div>
                </div>
    
            </div>
    
       
            <div id="requestDataPanel" class="text-center align-middle">                                                               
    
                <h5 class="mx-auto mt-5">Select a Payment Request</h5>
    
            </div>                       
    
        </div>
    
                  
</div>

@endsection

<script>


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
                    output +=`<table class="table table-bordered mt-4">`; 
                        output +=`<tr>`;   
                            output +=`<td class="bg-info text-white">Payment Mode</td>`;
                            output +=`<td>`;   

                                output += ucfirst(paymentrequest.payment_mode);
                        
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
                            output +=`<td>`;   

                                output += paymentrequest.stud_id;
                        
                            output += `</td>`;                                                     
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
                                output +=`<td class="bg-info text-white">Image</td>`;
                                output +=`<td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment-image">
                                                View Image
                                            </button>`;                                     
                                output += `</td>`;                                                                               
                            output += `</tr>`;
                        }                                                                                 
                       
                    output += `</table>`;

                    output +=`{!! Form::open(['url' => 'admin/approvepaymentrequest/']) !!}`;                    
                                                 
                            output +=`<input type="hidden" name="payment_id" value="`+ paymentrequest.id +`"/>`;                               
                            output +=`<button type="submit" class="btn btn-success btn-block" >Payment Done</button>`;

                        output +=`{!! Form::close() !!}`;
                    
                    output += `</div>`;
                    
            output += `</div>
            
            <div class="modal fade" id="payment-image" tabindex="-1" role="dialog" aria-labelledby="payment-imageTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">                      
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
