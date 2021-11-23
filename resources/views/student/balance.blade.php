@extends('layouts.module')


@section('page-title')
    Balance
@endsection

@section('content')
<div class="container mt-2">
    <div class="row text-left ml-2 mb-1">
        <a class="btn-back" href="{{route('studentProfile')}}"><i class="fa fa-angle-left" aria-hidden="true"></i>  Profile</a>            
    </div>
    
    @include('inc.messages')
    
    <div class="row">                    
        
        <div class="col-sm d-flex justify-content-left">        

            <table class="table mt-4">
                <caption>{{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name) . '\'s Balance Details' }}</caption>
                <tr>

                    <td class="border bg-warning">
                        Remaining Balance

                    </td>

                    <td class="border text-center">
                       Php {{$student->balance_amount}}
                    </td>

                </tr>
                <tr>
                    <td class="border bg-warning">
                        Last Payment
                    </td>

                    <td class="border text-center">
                        @if(count($invoices) > 0)
                              
                            {{\Carbon\Carbon::parse($invoices->last()->created_at)->format('M d Y h:i A') }}

                        @else 

                            {{'No payments made yet'}}

                        @endif
                    </td>

                </tr>

            </table>
        
        </div>

        <div class="col-sm-3 mt-2 mb-4">
            @if (\App\Models\PaymentRequest::where('student_id', $student->id)->whereNull('admin_id')->exists())
                <button class="btn btn-primary disabled">
                    Make a Payment Request <br>
                    <em>(disabled still have a pending request)</em>
                </button>
            @elseif($student->balance->amount < 1)
                <button class="btn btn-primary disabled">
                    Make a Payment Request <br>
                    <em>(disabled, Balance is empty)</em>
                </button>
            @else
                <div class="mt-3 text-right">
                    <a  href="{{url('/student/createpayment/')}}" class="btn btn-primary border" >Make a Payment Request</a>                
                </div>
            @endif

            <?php                                    
                $fees = \App\Models\Fee::getMergedFees($student->department, $student->program_id, $student->level, $student->semester);
            ?>

            <div class="mt-2 text-right">
                <button type="button" data-toggle="modal" data-target="#feesanddiscounts" class="btn btn-sm btn-info text-white">Fees and Discounts</button>
                
            </div>

            <!-- Modal -->
            <div class="modal fade" id="feesanddiscounts" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-danger">FEES</span> & <span class="text-warning">Discounts</span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <h4 class="badge badge-pill badge-danger">Fees</h4>
                                    @if ($fees->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($fees as $fee)
                                                <li class="list-group-item">{{'Php '. number_format($fee->amount, 2) . ' |  ' . ucfirst($fee->desc )}}</li>
                                            @endforeach                                    
                                        </ul>
        
                                    @else
                                        <em class="text-muted">No fees for this semester</em>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4 class="badge badge-pill badge-warning">Discounts</h4>
                                    @if ($student->discounts->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($student->discounts as $stud_disc)
                                                <li class="list-group-item">{{$stud_disc->discount->description}} ({{$stud_disc->discount->percentage}}%)</li>
                                            @endforeach                                    
                                        </ul>
        
                                        @else
                                            <em class="text-muted">No discounts attached to you.</em>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col text-right">
                                    <span class="text-muted mt-2">for inquiries, please <a href="{{url('contactus')}}">contact</a> the site administrator</span>

                                </div>

                            </div>
                    </div>
                </div>
            </div>

                

        </div>

    </div>

    @if(count($requests) > 0 )
    <hr>

    <div class="row">
        <div class="col-sm ">        

            <h5>Payment Requests</h5>

            <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table class="table table-bordered table-striped">
                    <thead class="text-white" >
                        <tr>
                            <th style="background-color: rgb(0, 110, 255);">Payment Request ID</th>
                            <th style="background-color: rgb(0, 110, 255);">Sent at</th>                        
                            <th style="background-color: rgb(0, 110, 255);">Payment Mode</th>
                            <th style="background-color: rgb(0, 110, 255);">Status</th>
                            <th style="background-color: rgb(0, 110, 255);">Marked by</th>                            
                        </tr>
                    </thead>

                    <tbody >
                        @foreach ($requests as $request)
                            <tr>
                                <td>
                                  #{{$request->request_id}}
                                </td>
                                <td>
                                    {{\Carbon\Carbon::parse($request->created_at)->format('M d Y h:i A') }}                        </td>                        
                                <td>
                                    {{$request->payment_mode}}
                                </td>
                                <td>
                                    @if(!is_null($request->status))
                                        @if ($request->status)
                                            <span class="text-success">Approved</span>
                                        @else
                                            <span class="bg-dark text-white">Rejected</span>
                                        @endif
                                    @else
                                        <span class="text-info">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!is_null($request->status))
                                        @if ($request->status)
                                            {{$request->admin->admin_id}} - {{$request->admin->name}}
                                            ({{\Carbon\Carbon::parse($request->updated_at)->format('M d Y h:i A')}})
                                        @else
                                            {{$request->admin->admin_id}} - {{$request->admin->name}}
                                            ({{\Carbon\Carbon::parse($request->updated_at)->format('M d Y h:i A')}})
                                            <br>
                                            <em>cause: {{!is_null($request->reject_cause) ? $request->reject_cause : 'not specified'}}</em>
                                        @endif
                                    @else
                                        <span class="text-info"> -- </span>
                                    @endif                                    
                                </td>
                            </tr>                            
                        @endforeach                
                    </tbody>
                </table>
            </div>
           
        </div>
       
    </div>

    @endif

    @if(count($invoices) > 0)   

    <hr>
    
    <div class="row">
        
        <div class="col-sm">        

            <h5>Payment History  <i class="fa fa-history" aria-hidden="true"></i></h5>

            <div class="table-responsive"  style="max-height: 500px; overflow: auto; display:inline-block;">
                <table class="table table-bordered ">

                    <thead class="text-white">
                        <tr>
                            <th style="background-color: rgb(1, 42, 95);">Invoice Number</th>
                            <th style="background-color: rgb(1, 42, 95);">Payment Date</th>
                            <th style="background-color: rgb(1, 42, 95);">Payment Amount</th>                                                     
                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($invoices as $invoice)
                            <tr>
    
                                <td>
                                    <a href="{{url('/invoice/' . $invoice->invoice_id)}}" target="_blank">{{$invoice->invoice_id}}</a>
                                </td>
                                <td>
                                    <a>{{\Carbon\Carbon::parse($invoice->created_at)->format('M d Y h:i A') }}</a>
                                </td>                                   
                                <td>
                                    Php  {{number_format( $invoice->payment, 2) }}
                                </td>                                
                            </tr>
                        
                        @endforeach
                    </tbody>
                    
                </table>
            </div>

        </div>

    </div>

    @else 

    @endif

</div>
@endsection
