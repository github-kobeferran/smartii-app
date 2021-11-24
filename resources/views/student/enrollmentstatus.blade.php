@extends('layouts.module')


@section('page-title')
    Enrollment Status
@endsection

@section('content')

<div class="container">

    <div class="row my-2">
        <div class="col mt-2">
            <a class="btn-back" href="{{url('/studentprofile')}}">   <i class="fa fa-angle-left" aria-hidden="true"></i> Profile</a>
        </div>
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
                        <th  class="bg-info formal-font">Subject</th>
                        <th  class="bg-info formal-font">Pre Requisite Subject(s) Taken</th>
                        <th  class="bg-info text-center formal-font">Eligible</th>                    
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
                            <td class="smartii-bg-lighter"><h5 class="roboto-font">{{$subject->desc}}</h5></td>

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
                                                            <span class="float-right text-danger">{{'Failed'}}</span> 
                                                            <?php $eligibility = false; ?>
                                                            @break
                                                        @case(1)
                                                            <span class="float-right text-success">{{'Passed'}}</span> 
                                                            @break
                                                        @case(2)
                                                            <span class="float-right text-info">{{'INC'}}</span> 
                                                            <?php $eligibility = false; ?>
                                                            @break                                               
                                                        @case(3)
                                                            <span class="float-right text-info">{{'Pending'}}</span>                                                             
                                                            <?php $eligibility = false; ?>
                                                            @break                                               
                                                        @case(4)
                                                            <span class="float-right text-secondary">{{'Not Taken'}}</span>                                                             
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
                            
                            <td class="text-center">
                                @if ($eligibility)
                                <?php array_push($eligbleSubjs, 1); ?>
                                    <i class="fa fa-check text-success" style="font-size: 2em; text-shadow: rgb(164, 255, 164) 1px 0 10px;" aria-hidden="true"></i>
                                @else
                                <?php array_push($eligbleSubjs, 0); ?>
                                    <i class="fa fa-times text-danger" style="font-size: 2em; text-shadow: rgb(170, 170, 170)  1px 0 10px;" aria-hidden="true"></i>
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

                <?php $eligble_subjs_collection = collect($eligbleSubjs); ?>
                
                @if ($eligble_subjs_collection->contains('1'))                    
                    <button type="button" data-toggle="modal" data-target="#confirm-enroll" class="btn btn-success btn-block shadow">ENROLL</button>
                @else
                    <button type="button" data-toggle="modal" data-target="#" class="btn btn-success btn-block shadow" disabled>ENROLL (disabled, no eligble subjects) </button>
                @endif

                <div class="modal fade" id="confirm-enroll" tabindex="-1" role="dialog"  aria-labelledby="confirm-enroll-title" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered" role="document">
                        
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title"><span class="text-white">CONFIRM ENROLLMENT?</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
                                    <span class="text-white" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-justify">
                                <div class="" style="font-size: 1.2em;">
                                    <p>Proceed to Process Your <b>Enrollment</b> to A.Y. {{\App\Models\Setting::first()->from_year}} - {{\App\Models\Setting::first()->to_year}} | {{\App\Models\Setting::first()->semester == 1 ? 'First Semester' : 'Second Semester'}} ?</p>                                                                        
                                </div>
                                <div class="">
                                    <p class="mb-0">By clicking <u><b class="text-success" style="font-size: 1.2em;">yes</b></u>:</p> 
                                    <p class="ml-2 my-0"> <i class="fa fa-caret-right"></i> you will be enrolled to your elligble subjects of <b>{{$student->program->desc}}</b> <em>intented for</em> <b>{{$level}} - {{$semester}}</b> </p>
                                    <p class="ml-2 mt-0"> <i class="fa fa-caret-right"></i> your <b>balance</b> will be updated depending on {{($student->program->is_tesda ? '' : $student->program->department) ? 'College price per unit which is Php ' . number_format(\App\Models\Setting::first()->col_price_per_unit,2) . ' and ' : 'SHS price per unit which is Php ' . number_format(\App\Models\Setting::first()->shs_price_per_unit, 2) . ' and '}} 
                                    ORGANIZATIONAL, {{$student->department ? " COL, " : " SHS, "}} and {{$student->program->desc}}'s <b role="button" data-toggle="modal" data-target="#feesanddiscounts"  >fees</b>.
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p>This action can't be <b>undone</b>.</p>
                                </div>
                            </div>    
                            <div class="modal-footer text-left">
                                <button class="btn btn btn-success">YES</button>
                                <button class="btn btn btn-secondary" data-dismiss="modal">NO</button>
                            </div>                        

                        </div>

                    </div>

                </div>

                {{-- <button type="submit" class="btn btn-success shadow btn-block">ENROLL</button> --}}

            {!! Form::close() !!}


                <?php                                    
                    $if_enrolled_stud = $student;
                    $if_enrolled_stud->level = $level_val;
                    $if_enrolled_stud->semester = $semester_val;

                    $fees = \App\Models\Fee::getMergedFees($if_enrolled_stud->department, $if_enrolled_stud->program_id, $if_enrolled_stud->level, $if_enrolled_stud->semester);
                ?>                

                <!-- Modal -->
                <div class="modal fade" id="feesanddiscounts" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header smartii-bg-dark">
                                <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">FEES</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <h4 class="badge badge-pill badge-danger">Fees for {{$student->program->abbrv}} {{$level}}/{{$semester}}</h4>
                                        @if ($fees->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($fees as $fee)
                                                    <li class="list-group-item">{{'Php '. number_format($fee->amount, 2) . ' |  ' . ucfirst($fee->desc )}}</li>
                                                @endforeach                                    
                                            </ul>
                                        @else
                                            <em class="text-muted">No fees for this semester</em>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            

        </div>  

    </div>

    @endif

</div>

@endsection