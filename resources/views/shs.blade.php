@extends('layouts.app')

@section('content')

@include('inc.homenav')
<div class="container">

    <div class="row">
        <div class="col p-5 mx-auto formal-font" style="background-color: #fcf5de; border: .2rem solid #05551B;">

            <h5 class=""><span class="formal-font">SENIOR HIGH SCHOOL PROGRAMS</span></h5>

            @if (App\Models\Program::where('department' , 0)->where('id', '!=', 3)->count() > 0)            
    
                <ul class="list-group list-group-flush">
                @foreach (App\Models\Program::where('department', 0)->where('id', '!=', 3)->get() as $prog)
                
                    <li style="font-size: 1.2em;" class="list-group-item smartii-bg-light"><i class="fa fa-square" aria-hidden="true"></i><span class="ml-3">{{$prog->desc}} ({{$prog->abbrv}})</span></li>
    
                @endforeach
                </ul>
            @else
            
            @endif

        </div>
    </div>   

</div>
@endsection