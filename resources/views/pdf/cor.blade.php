@extends('layouts.pdf')

@section('pdf-title')
    COR {{strtoupper($student->first_name) . ' ' . strtoupper($student->last_name)}}
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
                            St. Mark Arts and Training Institute Inc.<br />
                            J.P. Rizal St., Camilmil, Calapan City<br />
                            Oriental Mindoro, MIMAROPA                                                      
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
                            <b style="font-size: 1.7em;">Certificate Of Registration</b>
                        </td>

                        <td style="font-size: ">S.Y. {{$settings->from_year . '-' . $settings->to_year . ' | ' . $settings->sem_desc}} semester</td>
                    </tr>
                </table>
            </td>
        </tr>        
        <tr class="information" >
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            {{$student->dept}} {{$program->is_tesda ? ' | TESDA' : ''}}<br>
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
        
    </table>


    <table cellpadding="0" cellspacing="0">

        <tr class="heading" style="text-align: center !important;">
            <td>Subject Code</td>

            <td>Description</td>
            
            @if ($program->is_tesda)
                <td>No. of Hours</td>
            @else
                <td>No. of Units</td>
            @endif
            
        </tr> 

        @foreach ($subjectsTaken as $subTaken )

            <tr class="item" style="text-align: center !important">
                <td >{{ $subTaken->subject->code}}</td>
                <td >{{ $subTaken->subj_desc}}</td>
                <td>{{ $subTaken->units}}</td>
                
            </tr>
                
        @endforeach

    </table>



</div>
    
@endsection