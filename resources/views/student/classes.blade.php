@extends('layouts.module')

@section('content')
<div class="container mt-2">

    <a class="btn-back" href="{{url()->previous()}}">   <i class="fa fa-arrow-left" aria-hidden="true"></i>   </a>
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
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(count($currentSubjectsSchedule[$i]) > 1)
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->day_name}} 
                                            <br>                                                                                        

                                        @endfor
                                        

                                    @elseif(count($currentSubjectsSchedule[$i])  == 1 )

                                            {{$currentSubjectsSchedule[$i]->day_name}}

                                    @else

                                    

                                    @endif

                                    
                                @endempty                                                               
                                
                            </td>


                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(count($currentSubjectsSchedule[$i]) > 1)
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->formatted_start . ' - ' . $currentSubjectsSchedule[$i][$j]->formatted_until}}   
                                            <br>   
                                        @endfor
                                        

                                    @elseif(count($currentSubjectsSchedule[$i])  == 1 )

                                    {{$currentSubjectsSchedule[$i]->formatted_start . ' - ' . $currentSubjectsSchedule[$i]->formatted_until}}   

                                    @else

                                    

                                    @endif

                                    
                                @endempty

                                
                            </td>


                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(count($currentSubjectsSchedule[$i]) > 1)
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->room_name}}
                                            <br>   
                                        @endfor
                                        

                                    @elseif(count($currentSubjectsSchedule[$i])  == 1 )

                                    {{$currentSubjectsSchedule[$i]->room_name}}   

                                    @else

                                    

                                    @endif

                                    
                                @endempty
                                
                            </td>

                            
                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(count($currentSubjectsSchedule[$i]) > 1)
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->faculty_name}}
                                            <br>   
                                        @endfor
                                        

                                    @elseif(count($currentSubjectsSchedule[$i])  == 1 )

                                    {{$currentSubjectsSchedule[$i]->faculty_name}}   

                                    @else

                                    

                                    @endif

                                    
                                @endempty
                            </td>
                                                                                  
                        </tr>
                        @endfor
                      </tbody>
                </table>

            </div>

        </div>
        
    </div>    

    <div class="row">

        <div class="col-sm ">

            <h5>All {{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name) . '\'s' }} Taken Subjects</h5>

        </div>

    </div>

    <div class="row">

        <?php $the_from_year = $settings->from_year; ?>
                
        <div class="table-responsive">
            
            <table class="table table-bordered">

                <thead>
                    <tr>
                      <th scope="col">Subject</th>
                      <th scope="col">Units</th>
                      <th scope="col">Rating</th>
                      
                    
                    </tr>
                  </thead>
                  <tbody>                    
                    
                    @for ($i=0; $i<count($allSubjectsTaken); $i++)

                        <tr>

                            <td>
                                
                                {{$allSubjects[$i]->desc}}

                            </td>
                            <td>
                                
                                {{$allSubjects[$i]->units}}

                            </td>
                            <td>

                                @if($allSubjectsTaken[$i]->rating <= 3)
                                
                                    {{$allSubjectsTaken[$i]->rating}}

                                @elseif($allSubjectsTaken[$i]->rating > 4.5)

                                    {{ 'Failed' }}

                                @elseif($allSubjectsTaken[$i]->rating == 4)

                                    {{ 'INC'}}
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
@endsection
