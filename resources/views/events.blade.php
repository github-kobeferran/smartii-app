@extends('layouts.app')


@section('content')

    @include('inc.homenav')

    <div class="row mx-auto mt-2 justify-content-center">

        <h4 style="font-family: 'Roboto Condensed', sans-serif; color: #044716;" class="text-center m-2">SCHOOL EVENTS</h4> 

        @empty(auth()->user()->member)            

            

        @else                        

            @if (auth()->user()->member->member_type == 'admin')
            
                <a href="/events/create" class="btn-sm btn-primary m-2"><i class="fa fa-plus" aria-hidden="true"></i> Add an Event</a>

            @else
                

            @endif


        @endempty

    </div>

    @empty(\App\Models\Event::first())
        
        <div class="row mx-5 mt-5 justify-content-center " style="text-shadow: 1px 1px 2px pink;">
            No events yet
        </div>

    @else

    <div class="row mx-5 mt-2 justify-content-center">

        <table class="table table-bordered">

            <thead> 

                <tr>
                    <th >Month</th>
                    <th >Event</th>
                    <th >Date</th>

                </tr>

            </thead>

            <tbody>

                <?php $cur_month = 0; ?>

                @foreach (\App\Models\Event::orderBy('from', 'asc')->get() as $event)

                    <tr>

                        @if (\Carbon\Carbon::parse($event->from)->month != $cur_month)

                            <?php $cur_month = \Carbon\Carbon::parse($event->from)->month; ?>

                            <th scope="row">{{\Carbon\Carbon::parse($event->from)->isoFormat('MMMM')}}</th>
                            
                        @else

                        <th scope="row"></th>

                        @endif
                        
                        <td>{{$event->title}}</td>
                        <td>
                            {{\Carbon\Carbon::parse($event->from)->isoFormat('D, MMM OY')}}
                            @if ($event->from != $event->until)
                                - {{\Carbon\Carbon::parse($event->until)->isoFormat('D, MMM OY')}} 
                            @endif                

                        </td>
                    </tr>
                    
                @endforeach

            </tbody>
                
        </table>

    </div>

    @endif

@endsection
