@extends('layouts.pdf')

@section('pdf-title')
    {{$invoice->invoice_id}} Receipt
@endsection

@section('content')
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="{{url('/storage/images/system/logo/smartii.png')}}" style="width: 100%; max-width: 120px" />
                        </td>
                        
                        <td>
                            {{'#'.$invoice->invoice_id}}<br />
                            <?php  $date = \Carbon\Carbon::parse($invoice->created_at)->format('g:i a l jS F Y');  ?>
                            Payment Date: <br />
                            {{$date}} <br />                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            St. Mark Arts and Training Institute Inc.<br />
                            J.P. Rizal St., Camilmil, Calapan City<br />
                            Oriental Mindoro, MIMAROPA
                        </td>

                        <td>
                            {{$admin->name}}<br />
                            {{$admin->admin_id}}<br />
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>        
        <tr class="information" >
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            {{$student->dept}}<br />
                            {{$student->program_desc}}<br />
                            {{$student->level_desc}}
                        </td>

                        <td>
                            {{$student->first_name . ' ' . strtoupper(ucfirst($student->first_name)) . '. ' . $student->last_name }}<br />
                            {{$student->student_id}}<br /> 
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>               
                   
        <tr class="total">
            <td>Remaining Balance: Php {{number_format($data['totalBalance'], 2)}}</td>         
            <td>Payment Amount: Php {{number_format($data['payment'], 2)}}</td>                                 
        </tr>
        
        <tr class="total">
            <td>Payment Received: Php {{number_format($data['payment_received'], 2)}} 
                <br>
                Change: Php {{number_format($data['change'], 2)}}</td>                     
            <td>New Remaining Balance: Php {{number_format($data['rem_bal'], 2)}}</td>                     
        </tr>

       
    </table>   

</div>


<script>
     
     window.onbeforeunload = function(e) {
        return "Refreshing this page will create another invoice with the same amount";
    }
</script>

@endsection
