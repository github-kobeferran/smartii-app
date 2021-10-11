@extends('layouts.module')

@section('content')
<div class="container mt-2">

    <a class="btn-back" href="{{route('studentProfile')}}"><i class="fa fa-angle-left" aria-hidden="true"></i>  Profile</a>
    
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

        <div class="col-sm-3 mt-2 mb-4 ">
            <a href="{{url('/student/createpayment/')}}" class="btn btn-primary border">Make Payment Request</a>

            <?php
            $fees = \App\Models\Fee::where(function ($query) use($student) {
                                            $query->where('dept', 2)
                                                ->orWhere('dept', $student->department);                     
                                            })
                                    ->get();
            ?>

            <div class="mt-2">
                <button type="button" data-toggle="modal" data-target="#fees" class="btn btn-sm btn-info text-white">See Fees for this semester</button>
                
            </div>

            <!-- Modal -->
            <div class="modal fade" id="fees" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info ">
                            <h5 class="modal-title" id="exampleModalLongTitle">Fees</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            @if ($fees->count() > 0)
                                <ul class="list-group">
                                    @foreach ($fees as $fee)
                                        <li class="list-group-item">{{ucfirst($fee->desc ). ' | Php '. number_format($fee->amount, 2)}}</li>
                                    @endforeach
                                </ul>
                            @else
                                No fees for this semester
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                

        </div>

    </div>

    @if(count($requests) > 0 )
    <hr>
    <h5>Payment Requests</h5>

    <div class="row">

        <div class="col-sm d-flex justify-content-left">        


            <div class="table-responsive">
                <table class="table table-bordered ">


                    <tr>
                        <td>
                            Payment Request ID
                        </td>
                        <td>
                            Payment Date
                        </td>                        
                        <td>
                            Payment Mode
                        </td>
                        <td>
                            Status
                        </td>
                    </tr>

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
                            @if($request->admin_id != null)
                                Approved
                            @else
                                Pending
                            @endif
                        </td>
                    </tr>

                        
                    @endforeach
                
                    
                <table>
            </div>
            
        </div>
    </div>

    @endif

    @if(count($invoices) > 0)    
    <hr>
    <h5>Payment History  <i class="fa fa-history" aria-hidden="true"></i></h5>
                              
        <div class="row">

            

            <div class="col-sm d-flex justify-content-left">        


                <div class="table-responsive" style="max-height: 500px; overflow: auto; display:inline-block;">
                    <table class="table table-bordered ">

                       <thead>
                            <tr>
                                <td>
                                    Invoice Number
                                </td>
                                <td>
                                    Payment Date
                                </td>                      
                                <td>
                                    Payment Amount
                                </td>                             
                            </tr>

                       </thead>
    
                        <tbody >

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
