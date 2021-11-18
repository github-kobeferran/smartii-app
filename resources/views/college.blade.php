@extends('layouts.app')

@section('meta-content')
College programs page of St Mark Institute Integrated Information System, platform for handling services offered by St Mark Arts and Training Institute Incorporated
@endsection
@section('content')

@include('inc.homenav')
<div class="container">

    <div class="row">
        <div class="col p-5 mx-auto formal-font" style="background-color: #fcf5de; border: .2rem solid #05551B;">

            <div class="row">
                <div class="col">
                    <h5 class=""><span class="formal-font">COLLEGE COURSES</span></h5>

                    @if (App\Models\Program::where('department' , 1)->where('id', '!=', 4)->where('is_tesda', 0)->count() > 0)
    
                        <ul class="list-group list-group-flush">
                            @foreach (App\Models\Program::where('department', 1)->where('id', '!=', 4)->where('is_tesda', 0)->get() as $prog)
                        
                            <li style="font-size: 1.2em;" class="list-group-item smartii-bg-light"><i class="fa fa-square" aria-hidden="true"></i><span class="ml-3">{{$prog->desc}} ({{$prog->abbrv}})</span></li>
            
                        @endforeach
                        </ul>
                    @endif
    
                </div>
            </div>

            <div class="row">
                <div class="col">

                    <h5 class=""><span class="formal-font">TESDA COURSES</span></h5>

                    @if (App\Models\Program::where('department' , 1)->where('id', '!=', 4)->where('is_tesda', 1)->count() > 0)
    
                    <ul class="list-group list-group-flush">
                        @foreach (App\Models\Program::where('department', 1)->where('id', '!=', 4)->where('is_tesda', 1)->get() as $prog)
                    
                        <li style="font-size: 1.2em;" class="list-group-item smartii-bg-light"><i class="fa fa-square" aria-hidden="true"></i><span class="ml-3">{{$prog->desc}} ({{$prog->abbrv}})</span></li>
        
                    @endforeach
                        </ul>
                    @endif

                </div>
            </div>

        </div>
    </div>

</div>

  
@endsection