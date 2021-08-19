{{-- {{dd($posts)}} --}}

@extends('layouts.app')

@section('content')

@include('inc.homenav')

<div class="text-center">
    <h4 class="m-2 " style="text-shadow: 1px 1px 2px pink; font-family: 'Times New Roman', serif; color: #044716;">ARTICLES <span style="font-size: .6em;">AND</span> SCHOOL NEWS</h4>
</div>
<hr class="w-50">

@if (auth()->user()->user_type == 'admin')


    
  @if($unapproved->count() < 1)

    

  @else   
  

  <div class="custom-control custom-switch text-right mr-5 my-3 ">
        <input type="checkbox" class="custom-control-input" id="customSwitch1">
        <label class="custom-control-label" for="customSwitch1"><b>View Unapproved</b></label>
    </div>

   <div id="unapproved-panel" class="d-none">

        <div id="unapproved-panel" class="d-flex justify-content-between flex-wrap mt-2">

            @foreach ($unapproved as $post)

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

   </div>
        
  @endif

@endif

@empty($all)

    <h5 class="text-center m-2">Nothing to see here..</h5>

@else 

   

   <div id="post-panel">

        
    <div class="d-flex justify-content-between flex-wrap mt-2">

        @foreach ($featured as $post)

        <div class="text-center mx-auto">
            
            <a href="/post/{{$post->id}}">

                <span onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-title">{{$post->title}} <i class="fa fa-star text-warning" style=" -webkit-text-stroke: 2px black; /* width and color */" aria-hidden="true"></i></span>

            </a>

            <a href="/post/{{$post->id}}">

                <div onmouseout="onOut(document.getElementsByClassName('article-{{$post->id}}'))" onmouseover="onHover(document.getElementsByClassName('article-{{$post->id}}'))" class="article-{{$post->id}} article-card">
                                                        
                    @if (!empty($post->post_image) )                    

                        <img src="{{url('/storage/images/posts/' . $post->post_image)}}" alt="" class="img-thumbnail m-auto">
                                            
                    @endif

                    @if (strpos($post->body, '</a>') !== false)                    
                        Click to view article content
                    @else
                        <span> {!! $post->body !!}</span>
                    @endif
                        
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

                    @if (strpos($post->body, '</a>') !== false)                    
                        Click to view article content
                    @else
                        <span> {!! $post->body !!}</span>
                    @endif
                        
                </div>      

            </a> 
                                                                
        </div>  

        @endforeach

    </div>
    
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

    let customSwitch1 = document.getElementById('customSwitch1');
    let postPanel = document.getElementById('post-panel');
    let unapprovedPanel = document.getElementById('unapproved-panel');

    customSwitch1.addEventListener('change', () => {

        if(postPanel.classList.contains('d-none')){

            postPanel.classList.remove('d-none');
            unapprovedPanel.classList.add('d-none');

        }else {

            postPanel.classList.add('d-none');
            unapprovedPanel.classList.remove('d-none');

        }


    });



    </script>
        
@endempty


    
@endsection