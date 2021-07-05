@extends('layouts.app')

@section('content')

  @include('inc.homenav')


    @empty(!\App\Models\Announcement::all())

    

    <div class="row vw-100 justify-content-center mx-auto">

        

        @foreach ( \App\Models\Announcement::all() as $announcement)

          
                <div id="announce-{{$announcement->id}}" class="card text-center mx-2 announcement-banner mb-3 " style="">
                    <div class="card-header">Announcement {{\Carbon\Carbon::parse($announcement->created_at)->format('g:i A, D d F')}} <span class="float-right" role="button" onclick="closeAnnounceMent({{$announcement->id}})">X</span> </div>
                    <div class="card-body">
                    <h5 class="card-title">{{$announcement->title}}</h5>
                    <p class="card-text">{{$announcement->content}}</p>
                    </div>
                </div>           
            
        @endforeach
         </div>
    @endempty

    

    

    <div  style="" id="carouselExampleIndicators" data-interval="7000" class="carousel slide w-75 mx-auto" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" ></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3" ></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="6" ></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
            
        </ol>


        <div class="carousel-inner">

            <div class="carousel-item active">
            <img  src="{{url('/storage/images/system/icons/building.png')}}" alt="" class="d-block w-100">
                <div class="carousel-caption d-none d-md-block">
                    <h4 >SMARTII Building</h4>
                    <p>St. Mark Institue of Arts and Training Incorporated Building</p>
                </div>
            </div>

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/event.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 >Event</h4>
                <p>an Event held at Provincial Capitol</p>
            </div>
            </div>

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/bartending.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 >Bartending</h4>
                <p>Student learning Bartending</p>
            </div>
            </div>

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/basketball.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 >Basketball Team</h4>
                <p>St. Mark Basketball Team in CCAA</p>
            </div>
            </div>
           
            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/sports.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 >St. Mark Sports Fest </h4>
                <p>Start of the Sports Ceremony</p>
            </div>
            </div>

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/seaman.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 > Maritime Training </h4>
                <p>Students Martitime Training </p>
            </div>
            </div>           

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/intrams.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 > Intramurals </h4>
                <p>St. Mark Arts and Training Institute</p>
            </div>
            </div>

            <div class="carousel-item">
            <img  src="{{url('/storage/images/system/icons/ball.jpg')}}" alt="" class="d-block w-100">
            <div class="carousel-caption d-none d-md-block">
                <h4 > Prom Night </h4>
                <p>St. Mark Arts and Training Institute High School Prom</p>
            </div>
            </div>

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

    <hr class="footer-line-1">
        
    
    <hr class="footer-line-2">

    


    <div class="row text-center mt-3">

        <div class="col border-right ">

            
            
        </div>
        <div class="col ">

            &copy;Capstone Project
            Myca Ponce, Kobe Ferran
            <br>
            <br>
            
            
            
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
