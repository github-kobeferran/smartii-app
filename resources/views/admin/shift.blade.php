@extends('layouts.module')

@section('page-title')
    Shift Requests
@endsection

@section('content')

<div class="row">
    <div class="col-lg">

        <h5 class="">Shift Requests History <i class="fa fa-history"></i></h5>

        @if (\App\Models\RegistrarRequest::where('type', 'drop')->get()->count() > 0 )
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="smartii-bg-dark text-white">
                        <tr>
                            <th>Status</th>
                            <th>Requestor</th>
                            <th>Current Program</th>
                            <th>Change to</th>
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
                                            <button data-toggle="modal" data-target="#approve-request-{{$request->id}}" class="btn btn-sm btn-success mb-1">Approve</button>
                                            <button data-toggle="modal" data-target="#reject-request-{{$request->id}}" class="btn btn-sm btn-danger">Reject</button>
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
                            <td>                                                    
                                @if ($request->requestor_type == 'student')
                                    <a target="_blank" href="{{url('studentprofile/'. $request->requestor->student_id)}}">{{$request->requestor->first_name}} {{$request->requestor->last_name}} ({{$request->requestor->student_id}})</a> 
                                @endif
                            </td>
                            <td>{{$request->requestor->program->desc}}</td>
                            <td>{{\App\Models\Program::find($request->type_id)->desc}}</td>
                            <td>{{$request->created_at->isoFormat('MMM DD, YYYY hh:mm A')}}</td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $requests->links() }}

                @foreach ($requests as $request)
                    <div class="modal fade" id="approve-request-{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title"><span class="text-white">APPROVE CHANGING PROGRAM OF {{$request->requestor->first_name}} {{$request->requestor->first_name}}</span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                {!!Form::open(['url' => '/approveshift'])!!}
                                    <div class="modal-body text-justify">
                                        <p>Change program of {{$request->requestor->last_name}}, {{$request->requestor->first_name}} [{{$request->requestor->student_id}}]</p>
                                        <p>From <u><b>{{$request->requestor->program->desc}} ({{$request->requestor->program->abbrv}})</b></u> <span class="roboto-font" style="font-size:1.2em;" >to <em><u><b>{{\App\Models\Program::find($request->type_id)->desc}} ({{\App\Models\Program::find($request->type_id)->abbrv}})</b></u></em></span> ?</p>
                                        {{Form::hidden('id', $request->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Yes</button>
                                        <button class="btn btn-light" data-dismiss="modal">Cancel</button>
                                    </div>
                                {!!Form::close()!!}
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="reject-request-{{$request->id}}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title"><span class="text-white">REJECT CHANGING OF PROGRAM OF {{$request->requestor->first_name}} {{$request->requestor->first_name}}</span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                {!!Form::open(['url' => '/rejectshift'])!!}
                                    <div class="modal-body text-justify">
                                        <p>Reject changing program of {{$request->requestor->last_name}}, {{$request->requestor->first_name}} [{{$request->requestor->student_id}}]</p>
                                        <p>From <u><b>{{$request->requestor->program->desc}} ({{$request->requestor->program->abbrv}})</b></u> <span class="roboto-font" style="font-size:1.2em;" >to <em><u><b>{{\App\Models\Program::find($request->type_id)->desc}} ({{\App\Models\Program::find($request->type_id)->abbrv}})</b></u></em></span> ?</p>
                                        {{Form::hidden('id', $request->id)}}
                                        <b class="mt-2 float-right">{{Form::label('Reason of reject: ')}}</b>
                                        {{Form::text('reason', '', ['class' => 'form-control', 'placeholder' => 'Enter the reject cause here..'])}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                        <button class="btn btn-light" data-dismiss="modal">Cancel</button>
                                    </div>
                                {!!Form::close()!!}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @else
            <div class="text-center">
                <em><h4>No Shift Requests yet.</h4></em>
            </div>
        @endif
    </div>
   
</div>

@endsection