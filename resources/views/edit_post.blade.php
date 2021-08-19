@extends('layouts.app')

@section('content')

@include('inc.homenav')

<div class="container m-5">

    {!!Form::open(['url' => '/updatepost', 'files' => true, 'class' => 'border-bottom-0 p-4'])!!}
          
    
        <div class="row no-gutters">

            <div class="col">
                <h5 class="ml-2">
                    <u>Edit Post</u>
                </h5>

            </div>
            <div class="col">
                
                <a href="{{url('/post/' . $post->id)}}" class=" float-right btn btn-light border mx-0"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>  Go back to this post</a>        
            </div>

        </div>


        <hr>

        {{Form::hidden('id', $post->id)}}        

        <div class="container ml-4">  
            
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('inc.messages')

            <span class="float-right">Post Status: {{$post->approved == 0 ? 'unapproved' : 'approved'}}</span>

            <br>

            <div class="form-group">
                {{Form::label('post_title', 'Post Title', ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}
                {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Post Title here..', 'required' => 'required', 'minlength' => '1', 'maxlength' => '50' ])}}
    
            </div>

            <hr>

            @empty($post->post_image)

                <div class="text-center mx-auto">
                    This post has no image.
                </div>

            @else

                <img src="{{url('storage/images/posts/' . $post->post_image)}}" alt="" class="img-thumbnail w-25">
                
            @endempty

            <div class="form-group">
                {{Form::label('post_image', 'Post Image', ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}  
                {{Form::file('image', ['class' => 'form-control-file', 'id' => 'file'])}}
    
            </div>

            <hr>

            <div class="form-group">
                {{Form::label('post_body', 'Post Body' ,  ['style' => 'font-family: \'Roboto Condensed\', sans-serif;'])}}
                {{Form::textarea('body', $post->body, ['id' => 'editor', 'class' => 'form-control', 'placeholder' => 'Post Body here..' , 'required' => 'required', 'minlength' => '50'])}}
    
            </div>

            <button type="submit" class="btn btn-primary float-right ">

                Save Changes

            </button>

        </div>

    {!!Form::close()!!}


</div>

<script>
    CKEDITOR.replace( 'editor' );
</script>
    
@endsection