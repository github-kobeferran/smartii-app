@extends('layouts.app')

@section('meta-content')
Contacts page of St Mark Institute Integrated Information System, platform for handling services offered by St Mark Arts and Training Institute Incorporated
@endsection

@section('content')

@include('inc.homenav')
    <div class="container">
        <div style="background-color: #fcf5de; border: .2rem solid #05551B;" class="row p-5 mx-auto mt-2">

            <div class="col">
                <div class="row">
                    <h1 class="formal-font">CONTACT US</h1>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <p class=" text-justify mt-2" style="font-size:1.2em ;">
                                Email <i class="fa fa-envelope-o" aria-hidden="true"></i> : stmarkinstitute@yahoo.com
                            </p>
                        </div>
                        <div class="row">
                            <p class=" text-justify mt-2" style="font-size:1.2em ;">
                                Phone <i class="fa fa-phone" aria-hidden="true"></i> : (043) 4410054
                            </p>
                        </div>
                    </div>
                </div>
            </div>            

        </div>
    </div>
@endsection