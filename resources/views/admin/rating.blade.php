@extends('layouts.module')

@section('page-title')
    Change of Grade Requests
@endsection

@section('content')

<div class="row">
    <div class="col-lg">
        <h5 class="">Change Grade Requests History <i class="fa fa-history"></i></h5>
        
        @if ($requests->count() > 0)
            <div class="table-responsive" >
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="smartii-bg-dark text-white">Status</th>
                            <th class="smartii-bg-dark text-white">Requestor (all are faculty members)</th>
                            <th class="smartii-bg-dark text-white">Student</th>
                            <th class="smartii-bg-dark text-white">Subject/Current Rating</th>                        
                            <th class="smartii-bg-dark text-white">Change to</th>                        
                            <th class="smartii-bg-dark text-white">Class Name</th>
                            <th class="smartii-bg-dark text-white">Created at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <?php $subject_taken = \App\Models\SubjectTaken::find($request->type_id); ?> 
                            <tr>
                                <td>
                                    @switch($request->status)
                                        @case(0)
                                            <span class="text-info">
                                                Pending 
                                                <div class="input-group">
                                                    <button data-toggle="modal" data-target="#approve-{{$request->id}}" class="badge badge-pill badge-success">Approve</button>
                                                    <button data-toggle="modal" data-target="#deny-{{$request->id}}" class="badge badge-pill badge-danger">Deny</button>
                                                </div>
                                            </span>
                                            @break
                                        @case(1)
                                            <span class="text-success">Approved by {{$request->admin->admin_id}} - {{$request->admin->name}}</span>
                                            @break
                                        @case(2)
                                            <span class="text-danger">Denied by {{$request->admin->admin_id}} - {{$request->admin->name}}</span>
                                            @break
                                        @default
                                            
                                    @endswitch
                                </td>
                                <td>{{\App\Models\Faculty::where('id', $request->requestor_id)->first()->faculty_id }} - {{\App\Models\Faculty::where('id', $request->requestor_id)->first()->first_name }} {{\App\Models\Faculty::where('id', $request->requestor_id)->first()->last_name}}</td>
                                <td><a href="{{url($subject_taken->student->student_id)}}">{{$subject_taken->student->first_name}} - {{$subject_taken->student->last_name}}</a></td>
                                <td>
                                    @if ($request->status != 0)
                                        {{$subject_taken->subject->code}} - {{$subject_taken->subject->desc}} 
                                    @else 
                                        {{$subject_taken->subject->code}} - {{$subject_taken->subject->desc}} / {{$subject_taken->rating}}
                                    @endif
                                </td>
                                <td>{{$request->rating}}</td>
                                <td>{{$subject_taken->class->class_name}}</td>
                                <td>{{$request->created_at->isoFormat("MMM DD, YYYY hh:mm A")}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $requests->links() }}

            @foreach ($requests as $request)
            <div class="modal fade" id="approve-{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header smartii-bg-dark">
                            <h5 class="modal-title" id="exampleModalCenterTitle"><span class="text-white">Change of Grade Approval</span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {!!Form::open(['url' => '/approveratingupdate']) !!}
                            <?php $subject_taken = \App\Models\SubjectTaken::find($request->type_id); ?>
                            <div class="modal-body text-justify">
                                {{Form::hidden('id', $request->id)}}
                                {{Form::hidden('rating', $request->rating)}}
                                <p>Approve changing of {{$subject_taken->student->student_id}} - {{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}'s</p>
                                <p>{{$subject_taken->subject->code}} - {{$subject_taken->subject->desc}} grade <b>from {{$subject_taken->rating}}</b> <b class="text-primary">to {{$request->rating}}</b> <b>?</b></p>
                            </div>
                            <div class="modal-footer py-0">
                                <button type="submit" class="btn btn-success">Yes</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            </div>
                        {!!Form::close() !!}
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deny-{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header red-bg-light">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Reject {{$request->requestor->faculty_id}} - {{$request->requestor->first_name}} {{$request->requestor->last_name}}'s request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        {!!Form::open(['url' => '/rejectratingupdate']) !!}
                            <?php $subject_taken = \App\Models\SubjectTaken::find($request->type_id); ?>
                            <div class="modal-body text-justify">
                                {{Form::hidden('id', $request->id)}}     
                                <b>{{Form::label('Reason of reject: ')}}</b>                                                                                   
                                <p>Reject this update rating request?</p>
                                {{Form::text('reason', '', ['class' => 'form-control', 'placeholder' => 'Enter the reject cause here..'])}}
                            </div>
                            <div class="modal-footer py-0">
                                <button type="submit" class="btn btn-danger">Yes</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            </div>
                        {!!Form::close() !!}
                    </div>
                </div>
            </div>
            @endforeach

        @else
            <div class="text-center mt-2">
                <em>No Change Grade Requests yet.</em>
            </div>
        @endif
    </div>
</div>

@endsection