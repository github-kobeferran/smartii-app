@extends('layouts.app')

@section('content')

@include('inc.homenav')

<div class="container m-5">

    {!!Form::open(['url' => '/uploadpost', 'files' => true, 'class' => 'border-bottom-0 p-4'])!!}

        <h5 class="ml-2">
            Create Post
        </h5>

        <hr>

        <div class="container ml-4">  
            
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('inc.messages')

            <div class="form-group">
                {{Form::label('post_title', 'Post Title', ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}
                {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Post Title here..', 'required' => 'required', 'minlength' => '1', 'maxlength' => '50' ])}}
    
            </div>

            <hr>

            <div class="form-group">
                {{Form::label('post_image', 'Post Image', ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}  (optional)
                {{Form::file('image', ['class' => 'form-control-file', 'id' => 'file'])}}
    
            </div>

            <hr>

            <div class="form-group">
                {{Form::label('post_body', 'Post Body' ,  ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}
                {{Form::textarea('body', '', ['id' => 'editor', 'class' => 'form-control', 'placeholder' => 'Post Body here..' , 'required' => 'required', 'minlength' => '25'])}}
    
            </div>

            <button type="submit" class="btn btn-success float-right ">

                Submit

            </button>

        </div>

    {!!Form::close()!!}


</div>


    
@endsection