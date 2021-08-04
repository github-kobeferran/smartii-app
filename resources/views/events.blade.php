@extends('layouts.app')


@section('content')

    @include('inc.homenav')

    <div class="row mx-auto mt-2 justify-content-center">

        <h4 style="font-family: 'Roboto Condensed', sans-serif; color: #044716;" class="text-center m-2">SCHOOL EVENTS</h4> 

        @empty(auth()->user()->member)                        

        @else                        

            @if (auth()->user()->member->member_type == 'admin')
            
                <a href="/events/create" class="btn-sm btn-primary m-2"><i class="fa fa-plus" aria-hidden="true"></i> Add an Event</a>

            @else
                

            @endif


        @endempty

    </div>

    @empty(\App\Models\Event::first())
        
        <div class="row mx-5 mt-5 justify-content-center " style="text-shadow: 1px 1px 2px pink;">
            No events yet
        </div>

    @else

    @empty(auth()->user()->member)                        

        @else                        

            @if (auth()->user()->member->member_type == 'admin')
            
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            
            @include('inc.messages')

            @else
                

            @endif


        @endempty

    <div class="row mx-5 mt-2 justify-content-center">

        <table class="table table-bordered">

            <thead> 

                <tr>
                    <th >Month</th>
                    <th >Event</th>
                    <th >Date</th>
                    @empty(auth()->user()->member)                        

                    @else                        
            
                        @if (auth()->user()->member->member_type == 'admin')
                        
                            <th>Action</th>
            
                        @else
                            
            
                        @endif
            
            
                    @endempty

                </tr>

            </thead>

            <tbody>

                <?php $cur_month = 0; ?>

                @foreach (\App\Models\Event::orderBy('from', 'asc')->get() as $event)

                    <tr>

                        @if (\Carbon\Carbon::parse($event->from)->month != $cur_month)

                            <?php $cur_month = \Carbon\Carbon::parse($event->from)->month; ?>

                            <th scope="row">{{\Carbon\Carbon::parse($event->from)->isoFormat('MMMM')}}</th>
                            
                        @else

                        <th scope="row"></th>

                        @endif
                        
                        <td>{{$event->title}}</td>
                        <td>
                            {{\Carbon\Carbon::parse($event->from)->isoFormat('D, MMM OY')}}
                            @if ($event->from != $event->until)
                                - {{\Carbon\Carbon::parse($event->until)->isoFormat('D, MMM OY')}} 
                            @endif                

                        </td>

                        @empty(auth()->user()->member)                        

                        @else                        
                
                            @if (auth()->user()->member->member_type == 'admin')
                            
                                <td>

                                    <button type="button" class="btn-sm btn-info text-white m-2y" data-toggle="modal" data-target="#editEvent-{{$event->id}}">
                                       Edit
                                    </button>        

                                    <button type="button" class="btn-sm btn-danger text-white m-2y" data-toggle="modal" data-target="#deleteEvent-{{$event->id}}">
                                        Delete
                                    </button>                                    
                                
                                </td>

                                <div class="modal fade" id="editEvent-{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Edit Event {{$event->title}}</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        {!!Form::open(['url' => '/events/update'])!!}
                                        <div class="modal-body">
                                            {{Form::hidden('id', $event->id)}}

                                            <div class="form-group">
                                                <label for="title">Event Title</label>
                                                {{Form::text('title', $event->title, ['class' => 'form-control'])}}
                                            </div>

                                            <div class="form-group">
                                                
                                                    <label for="title">From</label>
                                                    {{Form::date('from', $event->from, ['class' => 'form-control mb-2'])}}
    

                                                    <label for="title">Until</label>
                                                    {{Form::date('until', $event->until, ['class' => 'form-control'])}}

                                            </div>
                                          
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-primary text-white m-2">Update</a>
                                        </div>
                                        {!!Form::close()!!}
                                      </div>
                                    </div>
                                  </div>

                                <div class="modal fade" id="deleteEvent-{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="exampleModalLabel">Delete Event " {{$event->title}} ? "</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <a href="/events/delete/{{$event->id}}" class="btn btn-danger text-white m-2">Delete</a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                
                            @else
                                
                
                            @endif
                
                
                        @endempty

                    </tr>
                    
                @endforeach

            </tbody>
                
        </table>

    </div>

    @endif

@endsection
