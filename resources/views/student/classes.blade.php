@extends('layouts.module')

@section('content')
<div class="container mt-2">

    <div class="row">
        
        <div class="col-sm d-flex justify-content-center">

            <h5>{{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name) . '\'s' }} Classes</h5>

        </div>
    </div>

    <div class="row">

        <div class="col-sm d-flex justify-content-center">                              
            <div class="table-responsive">            
                <table class="table table-bordered table-striped ">
                    <caption>Your Classes in {{$settings->sem_desc}} Semester A.Y. {{$settings->from_year}} - {{$settings->to_year}}</caption>
                    <caption>For N/A details please wait for the Registrar Office schedule's release</caption>
                    <thead>
                        <tr>
                          <th class="class-table-header" scope="col">Subject</th>
                          <th class="class-table-header" scope="col">Day</th>
                          <th class="class-table-header" scope="col">Time</th>                          
                          <th class="class-table-header" scope="col">Room</th>
                          <th class="class-table-header" scope="col">Instructor</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                       @for ($i = 0; $i < count($currentSubjects); $i++)
                        <tr>
                            <th scope="row">{{ $currentSubjects[$i]->desc }}</th>
                            <td>
                                @if(!empty($currentSubjectsSchedule[$i]))                                    

                                    {{$currentSubjectsSchedule[$i]->day_name}}
                                @else

                                    {{ 'N/A' }}

                                @endif
                                
                            </td>
                            <td>
                                @if( !empty($currentSubjectsSchedule[$i]) )
                                    {{$currentSubjectsSchedule[$i]->formatted_start . ' - ' . $currentSubjectsSchedule[$i]->formatted_until}}
                                @else
                                    {{ 'N/A' }}
                                @endif
                                
                            </td>
                            <td>
                                @if( !empty($currentSubjectsSchedule[$i]) )
                                    {{$currentSubjectsSchedule[$i]->room_name}}
                                @else
                                    {{ 'N/A' }}
                                @endif
                                
                            </td>
                            <td>
                                @if( !empty($currentSubjectsSchedule[$i]) )
                                    {{$currentSubjectsSchedule[$i]->faculty_name}}
                                @else
                                    {{ 'N/A' }}
                                @endif
                                
                            </td>
                                                                                  
                        </tr>
                        @endfor
                      </tbody>
                </table>

            </div>

        </div>
        
    </div>    

    <div class="row">



    </div>

</div>
@endsection
