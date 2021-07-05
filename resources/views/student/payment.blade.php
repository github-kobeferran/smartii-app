@extends('layouts.module')

@section('content')

<div class="container">
   
    
    <div class="row ">
        <a class="btn-back mt-3 ml-2" href="{{route('studentBalance')}}"><i class="fa fa-angle-left" aria-hidden="true"></i> Balances</a>
    </div>
    <div class="row ">
      
        <div class="col-sm d-flex justify-content-center ">

            <div class="text-center">
                <p class="m-1">
                    Gcash - {{$setting->gcash_number}}
                </p>
                <p class="m-1">
                    Bank {{$setting->bank_name}} -  {{$setting->bank_number}}
                </p>
            </div>                               

        </div>

    </div>

    <div class="row">

        <div class="col-sm d-flex justify-content-center">

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('inc.messages')

        </div>
        
    </div>

    <div class="row">

        <div class="col-sm d-flex justify-content-center">
            {!! Form::open(['url' => 'student/request/payment', 'files' => true, 'class' => ' p-2 border rounded border-primary']) !!}

                <h5 class="text-center">Payment Details</h5>

                <p class="text-center payment-text">by {{$student->first_name . ' ' . $student->last_name}}</p>

                {{Form::hidden('stud_id', $student->id)}}

                <div class="form-group ">
                    {{Form::text('trans_id', '',['maxLength' => '13', 'class' => 'text-center form-control', 'placeholder' => 'Your TRANSACTION ID/NO. here', 'required' => 'required'])}}
                </div>

                <div class="form-group text-center">
                    {{Form::label('proof', 'Mode of Payment' , [])}}
                    {{ Form::select('payment_mode', 
                    ['gcash' => 'Gcash', 'bank' => 'Bank'], 
                    'gcash',
                    ['class' => 'form-control'])}}
                </div>

                <div class=" border rounded border-secondary mb-2">
                
                    <div class="form-group m-3">
    
                        {{Form::label('image', 'Payment Image Proof (optional)')}}
                        {{Form::file('image', ['class' => 'form-control-file '])}}
        
                    </div>
    
                </div>

                <div class="form-group">

                    {{Form::textarea('desc', '',['maxLength' => '50', 'class' => ' form-control', 'placeholder' => 'Write Something here.. (optional) 50 characters'])}}
                </div>

                <div class="form-group">

                    {{Form::submit('Submit Payment Request', ['class' => 'btn btn-primary btn-block'])}}

                </div>

            
            {!! Form::close() !!}
        </div>
    
    </div>

</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

</script>


@endsection