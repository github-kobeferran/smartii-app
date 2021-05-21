@extends('layouts.module')

@section('content')
<h4><span id="object" class="h2">Choose what to</span> Create </h4>

{{-- {{dd($student)}} --}}

<div  id="createTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ session('student') ? 'active' : '' }}" id="student-create-tab" data-toggle="tab" href="#student" role="tab" aria-controls="student" aria-selected="true">Student</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('faculty') ? 'active' : '' }}" id="faculty-create-tab" data-toggle="tab" href="#faculty" role="tab" aria-controls="faculty" aria-selected="false">Faculty</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('admin') ? 'active' : '' }}" id="admin-create-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('subject') ? 'active' : '' }}" id="subject-create-tab" data-toggle="tab" href="#subject" role="tab" aria-controls="subject" aria-selected="false">Subject</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('subjectSet') ? 'active' : '' }}" id="subjectSet-create-tab" data-toggle="tab" href="#subjectSet" role="tab" aria-controls="subject-set" aria-selected="false">Subject Set</a>
        </li>
    </ul>

</div>

 @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@include('inc.messages')
	    
<div class="tab-content clearfix">
	<div class="tab-pane {{ session('student') ? 'active' : '' }}" id="student">
        @include('admin.create.student')               
	</div>

	<div class="tab-pane {{ session('faculty') ? 'active' : '' }}" id="faculty">
        Faculty ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>

    <div class="tab-pane {{ session('admin') ? 'active' : '' }}" id="admin">
         Admin ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>  

    <div class="tab-pane {{ session('subject') ? 'active' : '' }}" id="subject">
         Subject ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>       
    
    <div class="tab-pane {{ session('subjectSet') ? 'active' : '' }}" id="subjectSet">
         Subject Set ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>        
</div>  

<script>
// let studentNavLink = document.getElementById('student-create-tab');
// let facultyNavLink = document.getElementById('faculty-create-tab');
// let adminNavLink = document.getElementById('admin-create-tab');
// let subjectNavLink = document.getElementById('subject-create-tab');
// let subjectSetNavLink = document.getElementById('subjectSet-create-tab');

// let subjectContent = document.getElementById('student');
// let facultyContent = document.getElementById('faculty');
// let adminContent = document.getElementById('admin');
// let subjectContent = document.getElementById('subject');
// let subjectSetContent = document.getElementById('subjectSet');





// window.addEventListener('load', (event) => {

    
// }); 


</script>

@endsection