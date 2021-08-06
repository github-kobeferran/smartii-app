{{-- {{dd($posts)}} --}}

@extends('layouts.app')

@section('content')

@include('inc.homenav')

@empty($all)

    <h5 class="text-center m-2">Nothing to see here..</h5>

@else 

    <div class="d-flex justify-content-between flex-wrap mt-2">

        @foreach ($featured as $post)

        <div class="text-center mx-auto">
            
            <a href="/post/{{$post->id}}">

                <span onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-title">{{$post->title}}</span>

            </a>

            <a href="/post/{{$post->id}}">

                <div onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-card">
                                                        
                    @if (!empty($post->post_image) )                    

                        <img src="{{url('/storage/images/posts/' . $post->post_image)}}" alt="" class="img-thumbnail m-auto">
                                            
                    @endif

                    <span> {!!$post->body!!}</span>
                        
                </div>      

            </a> 
                                                                
        </div>  

        @endforeach

        @foreach ($posts as $post)

        <div class="text-center mx-auto">
            
            <a href="/post/{{$post->id}}">

                <span onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-title">{{$post->title}}</span>

            </a>

            <a href="/post/{{$post->id}}">

                <div onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-card">
                                                        
                    @if (!empty($post->post_image) )                    

                        <img src="{{url('/storage/images/posts/' . $post->post_image)}}" alt="" class="img-thumbnail m-auto">
                                            
                    @endif

                    <span> {!!$post->body!!}</span>
                        
                </div>      

            </a> 
                                                                
        </div>  

        @endforeach

    </div>
<script>

function onHover(el){   

for(let i = 0; i < el.length; i++){
    el[i].style.backgroundColor = "#b7d3bf";   
}

}

function onOut(el){   

for(let i = 0; i < el.length; i++){
    el[i].style.backgroundColor = "";   
}

}

</script>
    
@endempty


    
@endsection