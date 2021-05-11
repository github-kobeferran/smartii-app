@extends('layouts.module')

@section('content')
<h2>Create : <span id="object" class="h4">Student</span></h2>

<div  id="createTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="student-tab" data-toggle="tab" href="#student" role="tab" aria-controls="home" aria-selected="true">Student</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="faculty-tab" data-toggle="tab" href="#faculty" role="tab" aria-controls="profile" aria-selected="false">Faculty</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="subject-tab" data-toggle="tab" href="#subject" role="tab" aria-controls="contact" aria-selected="false">Subject</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="subjectSet-tab" data-toggle="tab" href="#subjectSet" role="tab" aria-controls="contact" aria-selected="false">Subject Set</a>
        </li>
    </ul>

</div>

	    
<div class="tab-content clearfix">
	<div class="tab-pane active" id="student">
        Student
        {!! Form::open(['url' => 'foo/bar']) !!}
            
        {!! Form::close() !!}
	</div>

	<div class="tab-pane" id="faculty">
        Faculty ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>

    <div class="tab-pane" id="subject">
         Subject ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>       
    
    <div class="tab-pane" id="subjectSet">
         Subject Set ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>        
</div>


@endsection