@extends('layouts.pdf')

@section('pdf-title')
    {{$program->abbrv}} Program outline
@endsection

@section('content')  
    <style> 
        .program-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: .5px solid #eee;
            box-shadow: 0 0 5px rgba(146, 142, 142, 0.15);
            font-size: 8px;
            line-height: 12px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: rgb(87, 87, 87);
        }

        .program-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .program-box table td {
            padding: 2px;
            vertical-align: top;
        }

        .program-box table tr td:nth-child(2) {
            text-align: right;
        }

        .program-box table tr.top table td {
            padding-bottom: 10px;
        }

        .program-box table tr.top table td.title {
            font-size: 25px;
            line-height: 25px;
            color: #333;
        }

        .program-box table tr.information table td {
            padding-bottom: 20px;
        }

        .program-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .program-box table tr.details td {
            padding-bottom: 10px;
        }

        .program-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .program-box table tr.item.last td {
            border-bottom: none;
        }

        .program-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .program-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .program-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .program-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .program-box.rtl table {
            text-align: right;
        }

        .program-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>

<?php
    $unit_desc = $program->is_tesda ? 'Hours' : 'Units';

    $first_sem_subjs = $subjects->filter(function($subject){
        if(($subject->level == 1 || $subject->level == 11) && ($subject->semester == 1))
            return $subject;
    });

    $second_sem_subjs = $subjects->filter(function($subject){
        if(($subject->level == 1 || $subject->level == 11) && ($subject->semester == 2))
            return $subject;
    });
    $third_sem_subjs = $subjects->filter(function($subject){
        if(($subject->level == 2 || $subject->level == 12) && ($subject->semester == 1))
            return $subject;
    });
    $fourth_sem_subjs = $subjects->filter(function($subject){
        if(($subject->level == 2 || $subject->level == 12) && ($subject->semester == 2))
            return $subject;
    });

    $total_units_hours = 0;

    foreach ($subjects as $subject) {
        $total_units_hours+= $subject->units;
    }
        
?>     

<div class="program-box">
    <h2 class="heading" text-align: center !important;>Republic of the Philippines</h2>
    <h1>ST. MARK ARTS AND TRAINING INSTITUTE</h1>
    <h3 >J.P Rizal St., Camilmil, Calapan City, Oriental Mindoro</h3>    
    <u><h1 class="heading" text-align: center !important;>{{$program->desc}} - {{$program->abbrv}}</h1></u>
    <h2>
        @if ($program->department)
            COLLEGE DEPARTMENT - {{$program->is_tesda ? 'TESDA PROGRAM': ''}}
        @else($program->deparment == 0)
            SENIOR HIGH SCHOOL DEPARTMENT        
        @endif
    </h2>    
    <h3>Total {{$unit_desc}}: {{$total_units_hours}}</h3>

    <h3> {{$program->department ? 'First Year' : 'Grade 11'}} First Semester</h3>
    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: left !important;">
            <td>Subject Code</td>
    
            <td>Description</td>
            
            @if ($program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            <td>Pre Requisite(s)</td>
            
        </tr> 
    
        @foreach ($first_sem_subjs as $subject)
            <tr class="item" style="text-align: left !important">
                <td>{{$subject->code}}</td>
                <td>{{$subject->desc}}</td>
                <td>{{$subject->units}}</td>
                <td>
                    @foreach ($subject->pre_reqs as $pre_req)
                        @if ($loop->last)
                            {{$pre_req->code}}
                        @else
                            {{$pre_req->code}}, 
                        @endif
                    @endforeach    
                </td>
            </tr>
        @endforeach  
    
    </table>

    <h3> {{$program->department ? 'First Year' : 'Grade 11'}} Second Semester</h3>
    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: left !important;">
            <td>Subject Code</td>
    
            <td>Description</td>
            
            @if ($program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            <td>Pre Requisite(s)</td>
            
        </tr> 
    
        @foreach ($second_sem_subjs as $subject)
            <tr class="item" style="text-align: left !important">
                <td>{{$subject->code}}</td>
                <td>{{$subject->desc}}</td>
                <td>{{$subject->units}}</td>
                <td>
                    @foreach ($subject->pre_reqs as $pre_req)
                        @if ($loop->last)
                            {{$pre_req->code}}
                        @else
                            {{$pre_req->code}}, 
                        @endif
                    @endforeach    
                </td>
            </tr>
        @endforeach  
    
    </table>

    <h3> {{$program->department ? 'Second Year' : 'Grade 12'}} First Semester</h3>
    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: left !important;">
            <td>Subject Code</td>
    
            <td>Description</td>
            
            @if ($program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            <td>Pre Requisite(s)</td>
            
        </tr> 
    
        @foreach ($third_sem_subjs as $subject)
            <tr class="item" style="text-align: left !important">
                <td>{{$subject->code}}</td>
                <td>{{$subject->desc}}</td>
                <td>{{$subject->units}}</td>
                <td>
                    @foreach ($subject->pre_reqs as $pre_req)
                        @if ($loop->last)
                            {{$pre_req->code}}
                        @else
                            {{$pre_req->code}}, 
                        @endif
                    @endforeach    
                </td>
            </tr>
        @endforeach  
    
    </table>

    <h3> {{$program->department ? 'Second Year' : 'Grade 12'}} Second Semester</h3>
    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: left !important;">
            <td>Subject Code</td>
    
            <td>Description</td>
            
            @if ($program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            <td>Pre Requisite(s)</td>
            
        </tr> 
    
        @foreach ($fourth_sem_subjs as $subject)
            <tr class="item" style="text-align: left !important">
                <td>{{$subject->code}}</td>
                <td>{{$subject->desc}}</td>
                <td>{{$subject->units}}</td>
                <td>
                    @foreach ($subject->pre_reqs as $pre_req)
                        @if ($loop->last)
                            {{$pre_req->code}}
                        @else
                            {{$pre_req->code}}, 
                        @endif
                    @endforeach    
                </td>
            </tr>
        @endforeach  
    
    </table>


</div>




@endsection