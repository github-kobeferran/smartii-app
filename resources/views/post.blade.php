

@extends('layouts.app')

@section('content')

@include('inc.homenav')

<div class="container">

    <hr class="w-50 mx-auto">

    <div class="text-center">
        <h4 class="m-2 " style="text-shadow: 1px 1px 2px pink; font-family: 'Times New Roman', serif; color: #044716;">{{$post->title}}</h4>
    </div>

    <hr class="w-25 mx-auto">

    <div class="text-center mb-4">
        
        <p class="mb-0" style="font-family: 'Times New Roman', serif;">by</p>
        <p class="" style="font-family: 'Times New Roman', serif;">{{$post->author_name}}</p>

    </div>
    
    @if (!empty($post->post_image) )         
        
        <div class="m-1 border">
            
            <img src="{{url('storage/images/posts/' . $post->post_image)}}" alt="" class="img-fluid">
        </div>
    
    @endif


    <p class="float-right mr-2 mb-4" style="font-size: .8em; color:gray">{{\Carbon\Carbon::parse($post->created_at)->isoFormat('OY-MM-DD hh:mm:ss A') }}</p>

    <br>


        

    <div class="text-justify">

        <p style="font-family: 'Times New Roman', serif;">{!!$post->body!!}</p>
            
    </div>

</div>


    
@endsection