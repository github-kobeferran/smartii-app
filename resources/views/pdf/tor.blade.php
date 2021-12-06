@extends('layouts.pdf')

@section('pdf-title')
    TOR of {{strtoupper($student->last_name)}} - {{$student->student_id}}
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

<div class="program-box" >
    <table cellpadding="0" cellspacing="0" style="border-left: .5px solid #f0cb54; !important; border-right: .5px solid #044716; !important;">
        <tr class="">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="{{url('/storage/images/system/logo/smartii.png')}}" style="width: 100%; max-width: 50px" />
                        </td>
                        
                        <td style="font-size: 1.7em !important;">
                            Office of the Registrar <br />
                            St. Mark Arts and Training Institute Inc.<br />
                            J.P. Rizal St., Camilmil, Calapan City<br />
                            Oriental Mindoro, MIMAROPA                                                      
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            
                        </td>
                        
                        <td style="font-size: 3em !important;">
                            OFFICIAL TRANSCRIPT OF RECORDS
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br>

    <table>
        <tr class="">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="">
                            <h1 style="margin-left: 10px;">PERSONAL DATA</h1>
                            <h2 style="margin-left: 50px;">Name: {{strtoupper($student->last_name)}}, {{strtoupper($student->first_name)}} {{ $student->middle_name ? strtoupper($student->middle_name) : ''}}</h2>
                            <h2 style="margin-left: 50px;">Date of Birth: {{\Carbon\Carbon::parse($student->dob)->isoFormat('DD MMMM YYYY') }}</h2>    
                            <h2 style="margin-left: 50px;">Address: {{$student->present_address}}</h2>
                            @isset($student->nationality)
                                <h2 style="margin-left: 50px;">Nationality: {{$student->nationality}}</h2>        
                            @endisset

                            <br>

                            <h2 style="margin-left: 50px;">Department: {{$student->department == 0 ? 'Senior High School' : 'College'}}</h2>
                            <h2 style="margin-left: 50px;">Student ID: {{$student->student_id}}</h2>
                            <h2 style="margin-left: 50px;">Program: {{$student->program->abbrv}}-{{$student->program->desc}}</h2>                                                
                            <h2 style="margin-left: 50px;">Level: {{$student->level_desc}}
                        </td>

                        <td>

                        </td>
                        
                        <td >
                            <h1 style="margin-left: 10px;">ENTRANCE DATA</h1>
                            <h2 style="margin-left: 50px;">Year Admitted: {{\Carbon\Carbon::parse($student->created_at)->year }}</h2>    
                            <h2 style="margin-left: 50px;">Category: @if ($student->level != 3 || $student->level != 13 ) NOT GRADUATED @else GRADUATED @endif </h2>    
                            <h2 style="margin-left: 50px;">Last School Attended: @isset($student->last_school) {{$student->last_school}} @endif</h2>    
                            <h2 style="margin-left: 50px;">Last School Year Attended: @isset($student->last_school_year) {{$student->last_school_year}} @endif</h2>    
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  
    <hr style="max-width: 75% !important; background: rgb(172, 172, 172) !important; color: rgb(182, 182, 182) !important;"> 
    <br>

    <h1 style="font-family: font-family: Tahoma, sans-serif;">Academic Record</h1>
    
    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: left !important;">
            <td>Term and School Year</td>
            <td>Subject Code</td>
            <td>Descriptive Title</td>
            <td>Final Grade</td>
            @if ($student->program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            <td>Remarks</td>
        </tr> 
        
        <?php $from_year = $student->subject_taken->groupBy(['from_year', 'to_year', 'semester']); ?>

        @foreach ($from_year as $to_year)
            @foreach ($to_year as $semester)
                @foreach ($semester as $subjects_taken)
                    <?php 
                        $subjects_taken = $subjects_taken->filter(function ($value, $key) {
                            return !is_null($value);
                        }); 
                    ?>
                    @foreach ($subjects_taken as $subject_taken)
                        @if ($loop->first)
                            <tr class="item" style="text-align: left !important">
                                <td><span style="font-size: 1em; font-style: bold;">{{$subject_taken->from_year}} - {{$subject_taken->to_year}} {{$subject_taken->semester == 1 ? 'First Semester' : 'Second Semester'}}</span></td>  
                                <td>{{$subject_taken->subject->code}}</td>
                                <td>{{$subject_taken->subject->desc}}</td>
                                <td>
                                    @switch($subject_taken->rating)
                                        @case(3.5)
                                            4
                                            @break
                                        @case(4)
                                            4
                                            @break
                                        @case(4.5)
                                            4
                                            @break
                                        @case(5)
                                            5
                                            @break
                                        @default
                                            {{$subject_taken->rating}}
                                    @endswitch
                                </td>
                                <td>{{$subject_taken->subject->units}}</td>
                                <td>
                                    @switch($subject_taken->rating)
                                        @case($subject_taken->rating >= 1 && $subject_taken->rating <= 3)
                                            C
                                            @break
                                        @case(3.5)
                                            INC
                                            @break
                                        @case(4)
                                            INC
                                            @break
                                        @case(4.5)
                                            INC
                                            @break
                                        @case(5)
                                            NC
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @else
                            <tr class="item" style="text-align: left !important">
                                <td></td>
                                <td>{{$subject_taken->subject->code}}</td>
                                <td>{{$subject_taken->subject->desc}}</td>
                                <td>
                                    @switch($subject_taken->rating)
                                        @case(3.5)
                                            4
                                            @break
                                        @case(4)
                                            4
                                            @break
                                        @case(4.5)
                                            4
                                            @break
                                        @case(5)
                                            5
                                            @break
                                        @default
                                            {{$subject_taken->rating}}
                                    @endswitch
                                </td>
                                <td>{{$subject_taken->subject->units}}</td>
                                <td>
                                    @switch($subject_taken->rating)
                                        @case($subject_taken->rating >= 1 && $subject_taken->rating <= 3)
                                            C
                                            @break
                                        @case(3.5)
                                            INC
                                            @break
                                        @case(4)
                                            INC
                                            @break
                                        @case(4.5)
                                            INC
                                            @break
                                        @case(5)
                                            NC
                                            @break
                                    @endswitch
                                </td>
                            </tr>
                        @endif   
                    @endforeach
                @endforeach
            @endforeach
        @endforeach            
    
    </table>

    <div style="border: 1px solid rgb(143, 142, 142); margin-top: 25px;">
        <h3 style="margin-left: 10px;">An areasure or alteration of entry invalidates this documents.</h3>
    </div>

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table style="width: 25% !important; margin-right: 0px !important;" >
                    <thead>
                        <tr class="heading" style="text-align: left !important;">
                            <th style="border .5px black !important;">Rating</th>
                            <th style="border .5px black !important;">Percentage Weight Equivalent</th>
                            <th style="border .5px black !important;">Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item" style="text-align: left !important">
                            <td>1.00</td>
                            <td>99-100</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>1.25</td>
                            <td>96-98</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>1.50</td>
                            <td>93-95</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>1.75</td>
                            <td>90-92</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>2.00</td>
                            <td>87-89</td>
                            <td>C</td>
                        </tr>                     
                    </tbody>
                </table>
                <h3 style="margin-left: 25px;">INTERPRETATION</h3>
                <h4 style="margin-top: 0px !important; margin-bottom: 0px !important;">C - COMPETENT</h4>
                <h4 style="margin-top: 0px !important; margin-bottom: 0px !important;">NC - NOT YET COMPETENT</h4>
                <h4 style="margin-top: 0px !important; margin-bottom: 0px !important;">INC - INCOMPLETE</h4>
            </td>
            <td>
                <table style="width: 25% !important; margin-right: 0px !important;">
                    <thead>
                        <tr class="heading" style="text-align: left !important;">
                            <th style="border .5px black !important;">Rating</th>
                            <th style="border .5px black !important;">Percentage Weight Equivalent</th>
                            <th style="border .5px black !important;">Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item" style="text-align: left !important">
                            <td>2.25</td>
                            <td>84-86</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>2.50</td>
                            <td>81-83</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>2.75</td>
                            <td>78-80</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>3.00</td>
                            <td>75-77</td>
                            <td>C</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>4.00</td>
                            <td>75 and below</td>
                            <td>INC</td>
                        </tr>
                        <tr class="item" style="text-align: left !important">
                            <td>5.00</td>
                            <td>75 and below</td>
                            <td>NC</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 25% !important;">
                <em><u>Prepared by: </u></em>
                <br>
                <br>
                <h3>{{\App\Models\Admin::find(auth()->user()->member->member_id)->name}}</h3>
                Record Officer 
                <br>
                <br>
                <em><u>Certified True and correct:</u></em>
                <br>
                <br>
                <h3>Levy G. Eguiron</h3>
                Registrar
            </td>
            <td style="text-align: right !important;">
                Date Issued: {{\Carbon\Carbon::now()->isoFormat('MMMM DD, YYYY')}}
                <br>
                <div style="text-align: center">
                    <h3>NOT VALID WITHOUT SMARTII SEAL</h3>
                </div>
                <br>            
                No. {{sprintf('%06d', $student->id)}}
            </td> 
        </tr>
    </table>
    

</div>
    
@endsection