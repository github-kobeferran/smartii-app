@extends('layouts.module')


@section('page-title')
    Payment Request
@endsection

@section('content')

<div class="container">
   
    
    <div class="row ">
        <a class="btn-back mt-3 ml-2" href="{{route('studentBalance')}}"><i class="fa fa-angle-left" aria-hidden="true"></i> Balances</a>
    </div>
    <div class="row ">
      
        <div class="col-sm d-flex justify-content-center ">

            <div class="text-center">
                <p class="m-1">
                    Gcash - {{!is_null($setting->gcash_number) ? $setting->gcash_number : 'N\A as of now please wait before making a request.'}}
                </p>

                

                <div class="row no-gutters">
                   
                    <div class="col">
                        <p class="mr-1">
                            <?php echo nl2br($setting->bank_name);?>                             
                       </p>
                    </div>
                    <div class="col">
                        <p class="ml-1">
                            <?php echo nl2br($setting->bank_number);?>        
                        </p>
                    </div>

                </div>
               
                
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
                    {{Form::text('trans_id', '',['maxlength' => '13', 'class' => 'text-center form-control', 'placeholder' => 'Your TRANSACTION ID/NO. here', 'required' => 'required'])}}
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
    
                        {{Form::label('image', 'Payment Image Proof (optional, max-size: 3MB)')}}
                        {{Form::file('image', ['class' => 'form-control-file '])}}
        
                    </div>
    
                </div>

                <div class="form-group">
                    <label for="amount-input" class="ml-2">Payment Amount ( &#8369; currency )</label>
                    {{Form::number('amount', 50, ['id' => 'amount-input', 'class' => 'form-control text-right', 'style' => "font-family: 'Source Code Pro', monospace; font-size: 1.5em;", 'maxlength' => '9', 'min' => '50', 'max' => $student->balance->amount, 'required' => 'required'])}}
                </div>                
                
                <div class="form-group">
                    <label for="" class="ml-2">Payment Description (optional max: 50 characters)</label>
                    {{Form::textarea('desc', '',['maxlength' => '50', 'class' => ' form-control', 'placeholder' => 'Write Something here.. (optional) 50 characters'])}}
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

let amountInput = document.getElementById('amount-input');

amountInput.addEventListener("keypress", function (evt) {
    if (evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
});

</script>


@endsection