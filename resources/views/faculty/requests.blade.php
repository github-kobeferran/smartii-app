@extends('layouts.module')

@section('page-title')
    My Requests
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg">
                @if ($requests->count() > 0)

                    <div class="text-center mt-3">
                        <h5>Requests to Registrar <i class="fa fa-history"></i></h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped border-0" style="max-height: 700px; overflow: auto; display:inline-block;">
                            <thead>
                                <tr>
                                    <th class="green-bg-light">Type</th>
                                    <th class="green-bg-light">Student</th>
                                    <th class="green-bg-light">Subject/Rating</th>
                                    <th class="green-bg-light">Status</th>
                                    <th class="green-bg-light">Requested at</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requests as $request)
                                    <?php $subject_taken = \App\Models\SubjectTaken::find($request->type_id); ?>
                                    <tr>
                                        <td>
                                            @if ($request->type == 'drop')
                                                Drop Request
                                            @else 
                                                Change Grade Request
                                            @endif
                                        </td>
                                        <td><a href="{{url('studentprofile/' . $subject_taken->student->student_id)}}">{{$subject_taken->student->first_name}} {{$subject_taken->student->last_name}}</a></td>
                                        <td>
                                            @if ($request->type == 'drop')
                                                {{$subject_taken->subject->code}} - {{$subject_taken->subject->desc}}
                                            @else
                                                {{$subject_taken->subject->code}} - {{$subject_taken->subject->desc}} / change to: {{$request->rating}}
                                            @endif
                                        </td>
                                        <td>
                                            @switch($request->status)
                                                @case(0)
                                                        <span class="text-info">Pending</span>
                                                    @break
                                                @case(1)
                                                        <span class="text-success">Approved by Admin {{$request->admin->name}} at {{$request->updated_at->isoFormat('MMM DD, YYYY')}}</span>
                                                    @break
                                                @case(2)
                                                        <span class="text-danger">Reject by Admin {{$request->admin->name}} @isset($request->reject_reason) "{{$request->reject_reason}}" @endisset</span>    
                                                    @break
                                                @default
                                                    
                                            @endswitch
                                        </td>
                                        <td>{{$request->created_at->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else 
                    
                    <div class="text-center mt-3">
                        <h5>No Requests yet. </h5>
                    </div>
                    
                @endif
            </div>
        </div>
    </div>
@endsection