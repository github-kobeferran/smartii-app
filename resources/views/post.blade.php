

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

    @if (auth()->user()->user_type == 'admin')

        <span data-toggle="tooltip" title="{{$post->approved == 0 ? 'approve' : 'unapprove'}}" class="float-right">Status: <a href="{{url('/togglepoststatus/' . $post->id)}}"> {{$post->approved == 0 ? 'Unapproved' : 'Approved'}}</a></span>
        <br>
        <span data-toggle="tooltip" title="{{$post->featured == 0 ? 'Feature this' : 'Unfeature'}}" class="float-right"><a href="{{url('/featurepost/' . $post->id)}}"> 

            @if ($post->featured == 0)
                <i class="fa fa-star-o text-warning" style=" -webkit-text-stroke: 2px black; /* width and color */" aria-hidden="true"></i>                
            @else
                <i class="fa fa-star text-warning" style=" -webkit-text-stroke: 2px black; /* width and color */" aria-hidden="true"></i>                
            @endif            
                    </a>  {{$post->featured == 0 ? 'Unfeatured' : 'Featured'}}</span>
        <br>
        
    @endif        

   @empty(auth()->user()->member)

   @else

   @if (auth()->user()->member->member_type == $post->member_type)

        @if (auth()->user()->member->member_id == $post->member_id)

            <span class="float-right bg-info"><a style="color: white !important;" href="{{url('/editpost/' . auth()->user()->email . '/' . $post->id)}}">Edit this post</a></span>

            <br>
                        
        @endif
    
    @endif
       
   @endempty
    
    @if (!empty($post->post_image) )         
        
        <div class="m-1 border">
            
            <img src="{{url('storage/images/posts/' . $post->post_image)}}" alt="" class="img-fluid d-block mx-auto">
        </div>
    
    @endif


    <p class="float-right mr-2 mb-2" style="font-size: .8em; color:gray">{{\Carbon\Carbon::parse($post->created_at)->isoFormat('Do MMM OY hh:mm A') }}</p>

    <br>
    <br>

    <div class="text-justify">

        <p style="font-family: 'Times New Roman', serif;">{!!$post->body!!}</p>
            
    </div>

</div>


    
@endsection