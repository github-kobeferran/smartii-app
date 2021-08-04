@extends('layouts.app')

@section('content')

  @include('inc.homenav')


   

    

    

    <div  style="" id="carouselExampleIndicators" data-interval="7000" class="carousel slide carousel-fade" style="width=100%;" data-ride="carousel">

        @empty(\Illuminate\Support\Facades\DB::select('select * from homepage_images'))

        @else         
            
            <?php $images = \Illuminate\Support\Facades\DB::select('select * from homepage_images order by created_at asc'); ?>
                        
            <ol class="carousel-indicators">

                @for ($i = 0; $i < count($images); $i++)
                
                    @if ($i == 0)

                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active"></li>

                    @else

                        <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" ></li>
                        
                    @endif

                @endfor

            </ol>                                                                                   

                    {{-- <a role="button" data-toggle="modal" data-target="#showImage">
                        
                        <img src="{{url('/storage/images/system/homepage_images/' . $item->image)}}" alt="" class="img-fluid my-2 w-50">

                    </a>   
                        --}}
                                                                                     
        @endempty                            

        <div class="carousel-inner">


            @for ($i = 0; $i < count($images); $i++)
                            
                @if ($i == 0)

                    <div class="carousel-item active">

                        <img  src="{{url('/storage/images/system/homepage_images/' . $images[$i]->image)}}" class="img-fluid w-100 d-block mx-auto" alt="" >
                    
                    </div>

                @else 

                    <div class="carousel-item">

                        <img  src="{{url('/storage/images/system/homepage_images/' . $images[$i]->image)}}" class="img-fluid w-100 d-block mx-auto" alt="" >
                
                    </div>
                    
                @endif

            @endfor           
            
        </div>
        
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    </div> 

    <div class="d-flex justify-content-center flex-wrap">

        <div class="text-center">
            
            @empty(!\App\Models\Announcement::all())
                                              
                @foreach ( \App\Models\Announcement::all() as $announcement)
        
                  
                        <div id="announce-{{$announcement->id}}" class="shadow bg-warning w-100 text-left mt-2 mb-2" style="min-width: 21rem;">
                            <u><h4 class="card-header " style="text-shadow: 1px 1px 2px #fdfdba; font-family: 'Roboto Condensed', sans-serif; color: #044716;"> {{ strtoupper("Announcement")  }} <span class="float-right ml-2" role="button" onclick="closeAnnounceMent({{$announcement->id}})"> <i class="fa fa-window-close text-danger" aria-hidden="true"></i></span> </h4></u>
                            <div class="card-body ">
                            <h3 class="card-title" style="font-family: 'Roboto Condensed', sans-serif; text-shadow: 1px 1px 2px #fdfdba;">{{$announcement->title}}</h3>
                            <p class="card-text">{{$announcement->content}}</p>
                            <p class="float-right text-secondary">{{\Carbon\Carbon::parse($announcement->created_at)->diffForHumans()  }} </p>
                            </div>
                        </div>           
                    
                @endforeach
                    
            @endempty

        </div>
        
        <div class="text-center">

            <u><h4 class="m-2" style="text-shadow: 1px 1px 2px pink; font-family: 'Roboto Condensed', sans-serif; color: #044716;">{{\Carbon\Carbon::now()->isoFormat('dddd, Do MMMM OY')}}</h4></u>

            @empty(\App\Models\Event::where('from', \Carbon\Carbon::now()->toDateString())->first())

                <div class="row mx-5 my-5 justify-content-center " style="text-shadow: 1px 1px 2px pink;">
                    No events today
                </div>

            @else

                <ul class="list-group-flush text-left">

                @foreach (\App\Models\Event::where('from', \Carbon\Carbon::now()->toDateString())->orderBy('from', 'asc')->get() as $event)

                    
                        <li class="list-group-item">
                            > {{$event->title}}

                            @if ($event->from != $event->until)
                                (until {{\Carbon\Carbon::parse($event->until)->isoFormat('D, MMM OY')}}) 
                            @endif    

                        </li>                       
                    

                @endforeach

                </ul>
                
            @endempty

            @empty(\App\Models\Event::first())

            @else

            <div>
                <a href="/events" class="float-right mr-2">>>> see School Events</a>
            </div>

            @endempty


            
            
            

        </div>

        

    </div>
    
<hr class="footer-line-1">

    <div class="row text-center mt-3 m-0 p-0">

        <div class="col border-right ">

            
            
        </div>
        <div class="col " style=" font-family: 'Cinzel', serif;">

            &copy;Capstone Project
            Myca Ponce, Kobe Ferran
            <br>
            Junior BSIT Students
            <hr>
            Mindoro State University
            <br>
            Calapan Campus
            
            
            
        </div>
        
        <div class="col border-left ">

            
            
        </div>
      

    </div>

    

    


<script>

function closeAnnounceMent(id){

    let banner = document.getElementById('announce-' + id);

    banner.classList.add('d-none');

}

</script>
    

@endsection
