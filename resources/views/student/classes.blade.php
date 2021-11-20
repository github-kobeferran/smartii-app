@extends('layouts.module')

@section('page-title')
    My Classes
@endsection

@section('content')
<div class="container mt-2">

    <div class="row pt-3">
        <div class="col">
            <a class="btn-back" href="{{url('/studentprofile/'. $student->student_id)}}"><i class="fa fa-angle-left" aria-hidden="true"></i> Profile</a>
        </div>
    </div>

    @include('inc.messages')
    
    @if ($student->subjectsTakenThisSemester()->count() > 0)
        <div class="row">
            <div class="col-sm text-right">
                <h5>My Classes</h5>
            </div>
        </div>

        <div class="row">

            <div class="col-sm d-flex justify-content-center">                              
                <div class="table-responsive">            
                    <table class="table table-bordered table-striped ">
                        <caption>{{ucfirst($student->first_name) . ' ' . ucfirst($student->last_name) . '\'s' }} classes in {{$settings->sem_desc}} Semester A.Y. {{$settings->from_year}} - {{$settings->to_year}}</caption>
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
                            @foreach ($student->subjectsTakenThisSemester() as $subject_taken)
                                <tr>
                                    <th>
                                        {{$subject_taken->subject->desc}}
                                        @if (\App\Models\RegistrarRequest::where('type', 'drop')->where('type_id', $subject_taken->id)->count() < 1)
                                            <span data-toggle="modal" data-target="#drop-{{$subject_taken->id}}" style="font-size: .6em; border: 1px solid black;" role=button class="badge badge-danger float-right">Drop </span>
                                        @else 
                                            <?php 
                                                $request = \App\Models\RegistrarRequest::where('type', 'drop')->where('type_id', $subject_taken->id)->first();                                                
                                            ?>
                                            @switch($request->status)
                                                @case(0)                                                    
                                                    <span style="font-size: .8em;" class="text-info float-right">pending for drop approval 
                                                    @if ($request->requestor_type == 'faculty')
                                                        made by your instructor
                                                    @endif    
                                                    
                                                    </span>
                                                    @break
                                                @case(2)
                                                    <span style="font-size: .8em;" class="text-secondary float-right">drop request rejected <em>{{!is_null($request->reject_reason) ? '(' . $request->reject_reason . ')' : ''}}</em></span>
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        @endif
                                    </th>
                                    <td class="text-right">
                                        @if (!is_null($subject_taken->class))
                                            @foreach ($subject_taken->class->schedules as $sched)
                                                {{$sched->day_name}} <br>
                                            @endforeach
                                        @else
                                           --
                                        @endif                                        
                                    </td>
                                    <td class="text-right">
                                        @if (!is_null($subject_taken->class))
                                            @foreach ($subject_taken->class->schedules as $sched)
                                                {{$sched->formatted_start}} - {{$sched->formatted_until}}<br>
                                            @endforeach
                                        @else
                                            --
                                        @endif
                                      
                                    </td class="text-right">
                                    <td class="text-right"> 
                                        @if (!is_null($subject_taken->class))
                                            @foreach ($subject_taken->class->schedules as $sched)
                                                {{$sched->room_name}}<br>
                                            @endforeach
                                        @else
                                            --
                                        @endif
                                      
                                    </td>
                                    <td class="text-right">
                                        @if (!is_null($subject_taken->class))
                                            {{$subject_taken->class->faculty->first_name}} {{$subject_taken->class->faculty->last_name}}
                                        @else
                                            --
                                        @endif
                                        
                                    </td>                                   
                                </tr>
                                
                            @endforeach
                        </tbody>
                    </table>

                    @foreach ($student->subjectsTakenThisSemester() as $subject_taken)                        

                        <div class="modal fade" id="drop-{{$subject_taken->id}}" aria-hidden="true" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title"><span class="text-white">DROP {{$subject_taken->subject->desc}}</span></h5>
                                        <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    {!!Form::open(['url' => '/requestdrop'])!!}
                                        <div class="modal-body text-justify">
                                            <p>Dropping <b>[{{$subject_taken->subject->code}}] - {{$subject_taken->subject->desc}}</b>.</p>
                                            <p>Clicking <em>'Yes'</em> will send a subject-drop request to the registrar.</p>
                                            <p>Are you sure you want to <b>DROP</b> this subject?</p>
                                            {{Form::hidden('id', $subject_taken->id)}}
                                        </div>
                                        <div class="modal-footer mt-0">
                                            <button type="submit" class="btn btn-danger text-white">Yes</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                                        </div>
                                    {!!Form::close()!!}
                                </div>
                                
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
            
        </div>    
    @else
        <div class="row">
            <div class="col text-center my-5">
                <em>You have no current active classes.</em>
            </div>
        </div>
    @endif    

    @if ($student->subjectsTakenThisSemester()->count() > 0)

        <div class="row my-1">
            <div class="col text-right ">
                <a href="{{url('/cor/'. $student->student_id)}}" target="_blank">{{$settings->from_year}}-{{$settings->from_year}} {{$settings->semester > 1 ? '2nd Sem' : '1st Sem'}} COR [PDF]<i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
            </div>
        </div>
        
    @endif      

    @if ($student->subject_taken->count() > 0)
        <div class="row">
            <div class="col-sm text-right" >
                <h6 style="font-family: 'Cinzel', serif; color: #05551b;">{{strtoupper($student->first_name). ' ' . strtoupper($student->last_name)}} SUBJECT TAKEN HISTORY <i class="fa fa-history"></i></h6>            
            </div>
        </div>
        <div class="row">
            <?php $the_from_year = $settings->from_year; ?>
            <div class="table-responsive" style="max-height: 650px; overflow: auto; display:inline-block;">
                <table class="table table-bordered">
                    <thead >
                        <tr>
                            <th class="bg-success">Semester Details</th>
                            <th class="bg-success">Subject</th>
                            <th class="bg-success">{{$student->program->is_tesda ? 'Hours' : 'Units'}}</th>
                            <th class="bg-success">Rating</th>
                        </tr>
                    </thead>
                    <tbody>                    
                        @foreach ($student->subject_taken->sortDesc() as $subject_taken)
                            <tr 
                                @if (!is_null($subject_taken->class) && !$subject_taken->trashed())
                                    class="subject-taken-row" 
                                    role="button" data-toggle="modal" data-target="#class-modal-{{$subject_taken->class->id}}
                                @endif
                            ">
                                <td>{{$subject_taken->from_year}} - {{$subject_taken->to_year}} {{$subject_taken->semester == 1 ? '1st Sem' : '2nd Sem'}}</td>
                                <td>
                                    {{$subject_taken->subject->desc}}
                                    @if ($subject_taken->trashed())                                        
                                        <?php 
                                        $request = \App\Models\RegistrarRequest::where('type_id', $subject_taken->id)->first()
                                        ?>
                                        <span class="float-right">
                                            DROPPED {{$request->requestor_type == 'faculty'? 'BY INSTRUCTOR' : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{$subject_taken->subject->units > 12 ? $subject_taken->subject->units . ' hrs' : $subject_taken->subject->units}}</td>
                                <td>
                                    @if($subject_taken->rating <= 3)
                                        {{$subject_taken->rating}}
                                    @elseif($subject_taken->rating > 4.5)
                                        <span class="text-danger">FAILED</span>
                                    @elseif($subject_taken->rating == 4)
                                        <span class="text-primary">INC</span>
                                    @else
                                        --
                                    @endif
                                </td>                          

                              

                            </tr>          

                            @if (!is_null($subject_taken->class) && !$subject_taken->trashed())
                                <div class="modal fade" id="class-modal-{{$subject_taken->class->id}}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-dark">{{$subject_taken->subject->desc}} - {{$subject_taken->class->name}} {{$subject_taken->from_year}} - {{$subject_taken->to_year}} {{$subject_taken->semester == 1 ? '1st Sem' : '2nd Sem'}}</span></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h6><b><em>Instructor: {{$subject_taken->class->faculty->first_name}} {{$subject_taken->class->faculty->last_name}}</em></b></h6>
                                                    @foreach ($subject_taken->class->schedules as $sched)
                                                        <?php 
                                                            $sched->formatted_start = $sched->start_time;
                                                            $sched->formatted_until = $sched->until;
                                                            $sched->day_name = $sched->day;
                                                            $sched->room_name = $sched->id;
                                                        ?>
                                                        {{$sched->day_name}} <br>
                                                        {{$sched->formatted_start}} - {{$sched->formatted_until}} 
                                                    @endforeach
                                                    <br>
                                                    <h6>Students in this class: {{$subject_taken->class->class_name}}</h6>
                                                </div>
                                                <ul class="list-group w-50 mx-auto text-center border">
                                                    @foreach ($subject_taken->class->subjectsTaken as $subject_taken)

                                                        <li class="list-group-item border-bottom">
                                                            <span ><a href="{{url('/studentprofile/' .$subject_taken->student->student_id)}}">{{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}</a></span>                                                        
                                                        </li>
                                                    @endforeach
                                                </ul>                                           
                                            </div>                                   
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<script>
    
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>
@endsection
