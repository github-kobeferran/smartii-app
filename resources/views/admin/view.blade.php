@extends('layouts.module')


@section('content')


<?php 
    $applicants = false;
    $students = false;
    $faculties = false;
    $admins = false;

    $btnID = null;
?>

@if (session()->has('app-id'))
    
    <?php
        
        $appID = session('app-id');

    ?>   

    <script>          
        let APP_ID = {!! json_encode($appID) !!}    

    </script>
    
@endif




@if ( session()->has('active') )
<?php 

    $value = session('active');

    switch ($value) {
        case 'applicants':
            $applicants = true;
            break;
        case 'students':
            $students = true;
            break;
        case 'faculties':
            $faculties = true;
            break;
        case 'admins':
            $admins = true;
            break;
        
        default:
            $applicants = true;
            break;
    }
?>

@else     
<?php
    
     $applicants = true;
?>
    
@endif

<h5>View</h5>




<div  id="viewTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $applicants ? 'active' : '' }}" id="applicants-view-tab" data-toggle="tab" href="#applicants" role="tab" aria-controls="profile" aria-selected="false">Applicants</a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $students ? 'active' : '' }}" id="students-view-tab" data-toggle="tab" href="#students" role="tab" aria-controls="home" aria-selected="true">Students</a>
        </li>
        <li class="nav-item">
        <a class="nav-link {{ $faculties ? 'active' : '' }}" id="faculty-view-tab" data-toggle="tab" href="#facultyMembers" role="tab" aria-controls="profile" aria-selected="false">Faculty Members</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $admins ? 'active' : '' }}" id="admins-view-tab" data-toggle="tab" href="#admins" role="tab" aria-controls="contact" aria-selected="false">Admins</a>
        </li>
       
    </ul>

</div>

	    
<div class="tab-content clearfix">            

    <div class="tab-pane {{ $applicants ? 'active' : '' }}" id="applicants">        
        @include('admin.view.applicants')
	</div>

	<div class="tab-pane {{ $students ? 'active' : '' }}" id="students">
        @include('admin.view.students')
	</div>

	<div class="tab-pane {{ $faculties ? 'active' : '' }}" id="facultyMembers">
        @include('admin.view.faculty')
	</div>

    <div class="tab-pane {{ $admins ? 'active' : '' }}" id="admins">        
       @include('admin.view.admins')
	</div> 
                  
</div>

@endsection


<script>

window.addEventListener('load', (event) => {         
    
    viewAdmins();
    fillProgramList(0);
                     

    if(typeof APP_ID !== 'undefined')  {
        fillApplicantList(APP_ID);  
    } else {
        fillApplicantList();  
    }


            
});

</script>