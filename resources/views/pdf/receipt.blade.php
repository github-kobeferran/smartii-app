@extends('layouts.pdf')

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

        <tr class="heading">
            <td>Subjects Taken</td>
            <?php 

            $total = 0;
            
            if($student->department == 0) 
                $price = $settings->shs_price_per_unit;
            else 
                $price = $settings->college_price_per_unit;
            
            ?>
            <td>Price Per Unit/{{$price}}</td>
        </tr>

        @foreach ( $subjectsTaken as $subTaken )

        <tr class="item">
            <td>{{ $subTaken->subj_desc . ' | Units: ' . $subTaken->units  }}</td>

            <td>{{ ($subTaken->units * $price )}}</td>
            <?php $total += $subTaken->units * $price; ?>
        </tr>
            
        @endforeach
                   

        <tr class="total">
            <td></td>

            <td>{{ 'Overall Balance: ' . $data['totalBalance']}}</td>         
        </tr>
        <tr class="total">
            <td></td>

            <td>{{ 'Payment: ' . $data['payment']}}</td>
               
        </tr>
        <tr class="">
            <td></td>

            <td>{{ 'Change: ' . $data['change']}}</td>       
        </tr>
        <tr class="">
            <td></td>

            <td>{{ 'Remaining Balance: ' . $data['rem_bal']}}</td>       
        </tr>
       
    </table>
</div>




@endsection
