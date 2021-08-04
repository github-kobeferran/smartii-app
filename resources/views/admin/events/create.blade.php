@extends('layouts.module')

@section('content')

    <h5>Create a School Event</h5>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @include('inc.messages')

    <div class="container">
        
        {!!Form::open(['url' => '/events/store', 'class' =>'border'])!!}

            <div class="form-group p-2">

                <label for="title">Event Title</label>

                {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'ex. Buwan ng Wika Celebration'])}}

            </div>

            <div class="form-group">

                <div class="form-check">

                    <label for="title">From</label>
    
                    {{Form::date('from', \Carbon\Carbon::now(), ['class' => 'ml-2', 'id' => 'dob'] )}}
    
                </div>

                <div class="form-check">

                    <label for="title">Until</label>
    
                    {{Form::date('until', \Carbon\Carbon::now(), ['class' => 'ml-2', 'id' => 'dob'] )}}
    
                </div>

            </div>

            

            <button type="submit" class="btn-sm btn-success float-right mt-2">

                Add Event
                
            </button>

            

        {!!Form::close()!!}


    </div>
    
    <br>
    <br>

    <div>
        <a href="/events" class="float-right mr-2">>>> see School Events</a>
    </div>
    
@endsection