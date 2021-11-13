@extends('layouts.module')


@section('content')

<div class="container">

    <div class="row">
        <a class="btn-back" href="{{url()->previous()}}">   <i class="fa fa-angle-left" aria-hidden="true"></i>   </a>

    </div>    
    
    @if($graduated != null && $graduated == true )
    
    <div class="row">
        
        <div class="col-sm mx-auto">
            
            <img class="img-fluid text-center" src="{{url('/storage/images/system/icons/graduate.jpg')}}" alt="">               
            
            <h1>CONGRATULATIONS!!!</h1>
            
        </div>
        
    </div>
    
    @else
        
    <div class="row mt-3">       
        
        <div class="col-sm mx-auto ">
            
            <h5>Enrolling to {{$level}} - {{$semester}}</h5>
            <p>{{$student->program_desc}}</p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('inc.messages')
            
            <table class="table table-bordered">
                <caption>Subjects to be taken in {{$level}} - {{$semester}}</caption>
                <caption>For failed subjects, you make take them in the following semesters by submitting a request in registrar</caption>
                <thead>
                    <tr>
                        <th  class="bg-info">Subject</th>
                        <th  class="bg-info">Pre Requisite Subject(s) Taken</th>
                        <th  class="bg-info">Eligible</th>                    
                    </tr>                    
                </thead>
                <tbody>
                    
                    <?php 
                    
                        $counter = 0; 
                        $eligbleSubjs = []; 

                    ?>
                    

                    @foreach ($subjectsToTake as $subject)
        
                        <?php $eligibility = true; ?>

                        <tr>
                            <td>{{$subject->desc}}</td>

                            @if (is_array($lastSemStatus[$counter]))
                                    
                                <td>                            
                                    
                                    @for ($i = 0; $i < count($lastSemStatus[$counter]); $i++)


                                        @if (is_array($lastSemStatus[$counter][$i]))
                                            <u>
                                            @for ($j = 0; $j < count($lastSemStatus[$counter][$i]); $j++)                                            

                                                @if (is_object($lastSemStatus[$counter][$i][$j]))

                                                    {{$lastSemStatus[$counter][$i][$j]->desc}}

                                                @else
                                                    
                                                    @switch($lastSemStatus[$counter][$i][$j])
                                                        @case(0)
                                                            <span class="float-right ">{{'Failed'}}</span> 
                                                            <?php $eligibility = false; ?>
                                                            @break
                                                        @case(1)
                                                            <span class="float-right ">{{'Passed'}}</span> 
                                                            @break
                                                        @case(2)
                                                            <span class="float-right ">{{'INC'}}</span> 
                                                            <?php $eligibility = false; ?>
                                                            @break                                               
                                                        @case(3)
                                                            <span class="float-right ">{{'Pending'}}</span>                                                             
                                                            <?php $eligibility = false; ?>
                                                            @break                                               
                                                        @case(4)
                                                            <span class="float-right ">{{'Not Taken'}}</span>                                                             
                                                            <?php $eligibility = false; ?>
                                                            @break                                               
                                                            
                                                    @endswitch

                                                @endif           

                                                
                                            @endfor    
                                            </u>
                                            <br>
                                         
                                        @else

                                            None
                                            @break

                                        @endif

                                    @endfor

                                    
                                </td>
                                
                            @else
                                
                            <td>

                                

                            </td>
                                
            
                            @endif    
                            
                            <td>
                                @if ($eligibility)
                                <?php array_push($eligbleSubjs, 1); ?>
                                    Yes
                                @else
                                <?php array_push($eligbleSubjs, 0); ?>
                                    No
                                @endif

                            </td>
                                                
                            <?php $counter++; ?>
                        </tr>
                    @endforeach
                  
                </tbody>
                
            </table>

           

            {!! Form::open(['url' => 'studentenroll']) !!}
    
                {{ Form::hidden('student_id', $student->id)}}

                @for ($i = 0; $i < count($subjectsToTake); $i++)

                        {{ Form::hidden('subjects[]', $subjectsToTake[$i]->id) }}
                        {{ Form::hidden('eligibility[]', $eligbleSubjs[$i]) }}
                    
                @endfor

                <button type="submit" class="btn btn-success shadow btn-block">ENROLL</button>

            {!! Form::close() !!}
            
            

        </div>  

    </div>

    @endif

</div>

@endsection