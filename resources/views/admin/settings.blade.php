@extends('layouts.module')

@section('content')
<h2>Settings</h2>

<div  id="exTab1" class="bs-example">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#1a" data-toggle="tab" class="nav-link active">Student</a>
        </li>
        <li class="nav-item">
            <a href="#2a" data-toggle="tab" class="nav-link ">Faculty</a>
        </li>
        <li class="nav-item">
            <a href="#3a" data-toggle="tab" class="nav-link ">Subject</a>
        </li>
        <li class="nav-item">
            <a href="#4a" data-toggle="tab" class="nav-link ">Subject Set</a>
        </li>
        
    </ul>

</div>

	    
<div class="tab-content clearfix">
	<div class="tab-pane active" id="1a">
        {!! Form::open(['url' => 'foo/bar']) !!}
            
        {!! Form::close() !!}
	</div>

	<div class="tab-pane" id="2a">
        Faculty ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>

    <div class="tab-pane" id="3a">
         Subject ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>       
    
    <div class="tab-pane" id="4a">
         Subject Set ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>        
</div>
  







@endsection