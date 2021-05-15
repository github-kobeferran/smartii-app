@extends('layouts.module')

@section('content')
<h4>You're creating a <span id="object" class="h2">Student</span></h4>

<div  id="createTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="student-create-tab" data-toggle="tab" href="#student" role="tab" aria-controls="student" aria-selected="true">Student</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="faculty-create-tab" data-toggle="tab" href="#faculty" role="tab" aria-controls="faculty" aria-selected="false">Faculty</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="admin-create-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="subject-create-tab" data-toggle="tab" href="#subject" role="tab" aria-controls="subject" aria-selected="false">Subject</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="subjectSet-create-tab" data-toggle="tab" href="#subjectSet" role="tab" aria-controls="subject-set" aria-selected="false">Subject Set</a>
        </li>
    </ul>

</div>

	    
<div class="tab-content clearfix">
	<div class="tab-pane active" id="student">
        @include('admin.create.student')               
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