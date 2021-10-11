@extends('layouts.module')

@section('content')
<div class="container mt-2">

    <a class="btn-back" href="{{url()->previous()}}">   <i class="fa fa-angle-left" aria-hidden="true"></i>   </a>
    <div class="row mt-2">
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
                              
                                    @if(is_array($currentSubjectsSchedule[$i]))
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->day_name}} 
                                            <br>                                                                                        

                                        @endfor
                                        

                                    @else

                                            {{$currentSubjectsSchedule[$i]->day_name}}                                    

                                    

                                    @endif

                                    
                                @endempty                                                               
                                
                            </td>


                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(is_array($currentSubjectsSchedule[$i]))
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->formatted_start . ' - ' . $currentSubjectsSchedule[$i][$j]->formatted_until}}   
                                            <br>   
                                        @endfor
                                        

                                    @else

                                    {{$currentSubjectsSchedule[$i]->formatted_start . ' - ' . $currentSubjectsSchedule[$i]->formatted_until}}   

                                    

                                    @endif

                                    
                                @endempty

                                
                            </td>


                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(is_array($currentSubjectsSchedule[$i]))
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->room_name}}
                                            <br>   
                                        @endfor
                                        

                                    @else

                                    {{$currentSubjectsSchedule[$i]->room_name}}   


                                    

                                    @endif

                                    
                                @endempty
                                
                            </td>

                            
                            <td>
                                @empty($currentSubjectsSchedule[$i])

                                {{ 'N/A' }}

                                @else
                              
                                    @if(is_array($currentSubjectsSchedule[$i]))
                                        
                                        
                                    @for ($j = 0; $j < count($currentSubjectsSchedule[$i]); $j++)                                        

                                            {{$currentSubjectsSchedule[$i][$j]->faculty_name}}
                                            <br>   
                                        @endfor
                                        

                                    @else

                                    {{$currentSubjectsSchedule[$i]->faculty_name}}   


                                    

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

    @if (\App\Models\SubjectTaken::enrolledSubjectsbyStudent($student->id)->count() > 0)

        <div class="row my-1">
            <div class="col text-right ">
                <a href="{{url('/cor/'. $student->student_id)}}" target="_blank">View Certificate of Registration</a>
            </div>
        </div>
        
    @endif

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

<script>
    
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>
@endsection
