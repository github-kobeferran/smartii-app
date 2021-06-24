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


                <div class="table-responsive">
                    <table class="table table-bordered ">

                        <tr>
                            <td>
                                Invoice Number
                            </td>
                            <td>
                                Payment Date
                            </td>
                            <td>
                                Balance
                            </td>
                            <td>
                                Payment Amount
                            </td>
                            <td>
                                Remaining Balance
                            </td>
                        </tr>
    
                        @foreach ($invoices as $invoice)
    
                        <tr>

                            <td>
                                {{$invoice->invoice_id}}
                            </td>
                            <td>
                                <a>{{\Carbon\Carbon::parse($invoice->created_at)->format('M d Y h:i A') }}</a>
                            </td>
                            <td>
                                Php  {{ $invoice->balance }}
                            </td>
                            <td>
                                Php  {{ $invoice->payment }}
                            </td>
                            <td>
                                Php  {{ $invoice->remaining_bal }}
                            </td>
                         
                        </tr>
                            
                        @endforeach
                        
                    </table>
                </div>

            </div>

        </div>

    @else 

        

    @endif

   

</div>
@endsection
