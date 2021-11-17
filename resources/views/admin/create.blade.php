@extends('layouts.module')

@section('page-title')
    Create
@endsection

@section('content')


<?php 
    $student = false;
    $faculty = false;
    $admin = false;
    $subject = false;
    $program = false;

    $the_admin = \App\Models\Admin::find(auth()->user()->member->member_id);

?>

@if ( session()->has('active') )
<?php 

    $value = session('active');

    switch ($value) {
        case 'student':
            $student = true;
            break;
        case 'faculty':
            $faculty = true;
            break;
        case 'admin':
            $admin = true;
            break;
        case 'subject':
            $subject = true;
            break;
        case 'program':
            $program = true;
            break;
        
        default:
            $student = true;
            break;
    }
?>

@else     
<?php
    
     $student = true;
?>
    
@endif

<h5 >Create</h5>

</button>
<div  id="createTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">

        <li class="nav-item ">
            <a class="nav-link {{ $student ? 'active' : '' }}" id="student-create-tab" data-toggle="tab" href="#student" role="tab" aria-controls="student" aria-selected="true">Student</a>
        </li>
        
        @if ($the_admin->position == 'superadmin')
        
            <li class="nav-item">
                <a class="nav-link {{ $faculty ? 'active' : ''}}" id="faculty-create-tab" data-toggle="tab" href="#faculty" role="tab" aria-controls="faculty" aria-selected="false">Faculty</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $admin ? 'active' : ''}}" id="admin-create-tab" data-toggle="tab" href="#admin" role="tab" aria-controls="admin" aria-selected="false">Admin</a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $subject ? 'active' : '' }}" id="subject-create-tab" data-toggle="tab" href="#subject" role="tab" aria-controls="subject" aria-selected="false">Subject</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $program ? 'active' : '' }}" id="program-create-tab" data-toggle="tab" href="#program" role="tab" aria-controls="program" aria-selected="false">Program</a>
            </li>                          

        @endif


       
    </ul>

</div>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@include('inc.messages')
	    
<div class="tab-content clearfix">
	<div class="tab-pane {{ $student ? 'active' : '' }}" id="student">
        @include('admin.create.student')               
	</div>

	<div class="tab-pane {{ $faculty ? 'active' : '' }}" id="faculty">
        @include('admin.create.faculty')  
	</div>

    @if ($the_admin->position == 'superadmin')
        <div class="tab-pane {{ $admin ? 'active' : '' }}" id="admin">
            @include('admin.create.admin')  
        </div>  
    @else 

    @endif
    

    <div class="tab-pane {{ $subject ? 'active' : '' }}" id="subject">
        @include('admin.create.subject')  
	</div>       
    
    <div class="tab-pane {{ $program ? 'active' : '' }}" id="program">
        @include('admin.create.program')               
	</div>        
</div>   

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

window.addEventListener('load', (event) => {
    changeSubjectSelect();
    changeSelect();    
    document.getElementById("preReqSwitch").checked = false;
    document.getElementById("newStudSwitch").checked = false;
    fillSubjectsList(0);
    fillProgramList(0);     
}); 



</script>

@endsection