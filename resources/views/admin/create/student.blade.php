{!! Form::open(['url' => 'foo/bar', 'method', 'files' => true]) !!}

    <div class = "form-group">        
        {{Form::label('lastName', 'Last Name')}}
        {{Form::text('lastName', '', ['class' => 'form-control w-50', 'placeholder' => 'Last Name here..'])}}
    </div> 

    <div class = "form-group">        
        {{Form::label('firstName', 'First Name')}}
        {{Form::text('firstName', '', ['class' => 'form-control w-50', 'placeholder' => 'First Name here..'])}}
    </div> 

{!! Form::close() !!}