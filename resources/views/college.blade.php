@extends('layouts.app')


@section('content')

@include('inc.homenav')

    <div class="container border border-success rounded p-5 mt-5 mx-auto">

        <div class="row">
        
            <div>
    
                <h5 class="text-center mx-auto">
    
                    COLLEGE PROGRAMS
    
                </h5>
    
            </div>
    
           
    
        </div>

        @if (App\Models\Program::where('department' , 1)->where('id', '!=', 4)->count() > 0)
    
            <div class="d-flex flex-wrap">

                @foreach (App\Models\Program::where('department', 1)->where('id', '!=', 4)->get() as $prog)

                    <div class="border rounded border-dark m-2 p-3">

                        <p>{{$prog->desc}}</p>

                    </div>
            
                    
                @endforeach

            </div>

            
        @else
            

            
        @endif
        
        

    </div>
@endsection