@extends('layouts.module')

@section('page-title')
    Drop Requests
@endsection

@section('content')

<div class="row">
    <div class="col-lg">

        <h5 class="">Drop Requests History <i class="fa fa-history"></i></h5>

        @if ($requests->count() > 0 )
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="smartii-bg-dark text-white">
                        <tr>
                            <th>Status</th>
                            <th>Requestor</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Class Details</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                        <tr>
                            <td>
                                @switch($request->status)
                                    @case(0)
                                        <p class="text-info my-0">Pending</p>
                                        <div class="text-justify">
                                            <button data-toggle="modal" data-target="#approve-drop-{{$request->id}}" class="btn btn-sm btn-success my-1">Approve</button>
                                        <button data-toggle="modal" data-target="#reject-drop-{{$request->id}}" class="btn btn-sm btn-danger my-1">Reject</button>
                                        </div>
                                        @break
                                    @case(1)
                                        <span class="text-success">Approved</span>
                                        <p class="text-dark">{{$request->admin->name}}</p>
                                        <p style="font-size: .7em">{{$request->updated_at->isoFormat('MMM DD, YYYY hh:mm A')}}</p>
                                        @break
                                    @case(2)
                                        <p class="text-danger">Rejected</p>
                                        <p class="text-dark">{{$request->admin->name}}</p>
                                            @if (!is_null($request->reject_reason))
                                                <p><em>({{$request->reject_reason}})</em></p>
                                            @endif
                                        <p style="font-size: .7em">{{$request->updated_at->isoFormat('MMM DD, YYYY hh:mm A')}}</p>
                                        @break
                                    @default
                                        
                                @endswitch
                            </td>
                            <?php 
                                $subject_taken = \App\Models\SubjectTaken::withTrashed()->find($request->type_id);
                            ?>       
                            <td>                                                    
                                @if ($request->requestor_type == 'student')
                                    <a target="_blank" href="{{url('studentprofile/'. $request->requestor->student_id)}}">{{$request->requestor->first_name}} {{$request->requestor->last_name}}</a> (student)
                                @else
                                    {{$request->requestor->first_name}} {{$request->requestor->last_name}} (instructor)
                                @endif
                            </td>
                            <td><a target="_blank" href="{{url('studentprofile/'. $subject_taken->student->student_id)}}">{{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}</a></td>
                            <td>{{$subject_taken->subject->desc}} ({{$subject_taken->subject->code}})</td>
                            <td>
                                @if (!is_null($subject_taken->class))
                                    {{$subject_taken->class->class_name}} | {{$subject_taken->class->faculty->first_name}} {{$subject_taken->class->faculty->last_name}}
                                @else
                                    -- 
                                @endif
                            </td>
                            <td>{{$request->created_at->isoFormat('MMM DD, YYYY hh:mm A')}}</td>                         
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $requests->links() }}

                @foreach ($requests as $request)
                <div class="modal fade" id="approve-drop-{{$request->id}}" tabindex="-1" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark">
                                <h5 class="modal-title"><span class="text-white">APPROVE DROP REQUEST</span></h5>
                                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                        
                            </div>
                            {!!Form::open(['url' => '/approvedrop'])!!}
                                <?php 
                                    $subject_taken = \App\Models\SubjectTaken::withTrashed()->find($request->type_id);
                                ?>
                                <div class="modal-body text-justify">
                                    <p><b>DROP</b> <em>({{$subject_taken->subject->code}}) - {{$subject_taken->subject->desc}}</em> from</p>
                                    <p><b>[{{$subject_taken->student->student_id}}] {{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}</b> subjects taken?</p>
                                    {{Form::hidden('id', $request->id)}}
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark">Drop {{$subject_taken->student->last_name}} -> {{$subject_taken->subject->code}}</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                                </div>
                            {!!Form::close()!!}
                        </div>
                    </div>
                </div>                

                <div class="modal fade" id="reject-drop-{{$request->id}}" tabindex="-1" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title"><span class="text-white">REJECT DROP REQUEST</span></h5>
                                <button class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                                        
                            </div>
                            {!!Form::open(['url' => '/rejectdrop'])!!}
                                <?php 
                                    $subject_taken = \App\Models\SubjectTaken::withTrashed()->find($request->type_id);
                                ?>
                                <div class="modal-body text-justify">
                                    <p><b>REJECT</b> {{$request->requestor->first_name}} {{$request->requestor->last_name}} DROP REQUEST ? </p> 
                                    <p>Request Details:</p>
                                    <p>Drop <em>({{$subject_taken->subject->code}}) - {{$subject_taken->subject->desc}}</em></p>
                                    <p>of <b>[{{$subject_taken->student->student_id}}] {{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}</b> subjects taken?</p>
                                    <b class="mt-2 float-right">{{Form::label('Reason of reject: ')}}</b>
                                    {{Form::text('reason', '', ['class' => 'form-control', 'placeholder' => 'Enter the reject cause here..'])}}
                                    {{Form::hidden('id', $request->id)}}
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Reject Drop Request</button>
                                    <button type="button" data-dismiss="modal" class="btn btn-light">Cancel</button>
                                </div>
                            {!!Form::close()!!}
                        </div>
                    </div>
                </div>
            @endforeach

            </div>
        @else
            <div class="text-center">
                <em><h4>No Drop Requests yet.</h4></em>
            </div>
        @endif
    </div>
   
</div>

@endsection