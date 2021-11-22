@extends('layouts.module')

@section('page-title')
    View
@endsection

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
@include('inc.messages')
<?php 
    $admin = \App\Models\Admin::find(auth()->user()->member->member_id);
?>

<div id="viewTab" class="bs-example" > 
     <ul id="view-tabs" class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link view-btns {{ $applicants ? 'active' : '' }}" id="applicants-view-tab" data-toggle="tab" href="#applicants" role="tab" aria-controls="profile" aria-selected="false">Applicants</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link view-btns {{ $students ? 'active' : '' }}" id="students-view-tab" data-toggle="tab" href="#students" role="tab" aria-controls="home" aria-selected="true">Students</a>
        </li>

        @if ($admin->position == "superadmin")            
            <li class="nav-item">
                <a class="nav-link view-btns {{ $faculties ? 'active' : '' }}" id="faculty-view-tab" data-toggle="tab" href="#facultyMembers" role="tab" aria-controls="profile" aria-selected="false">Faculty Members</a>               
            </li>
            <li class="nav-item">
                <a class="nav-link view-btns {{ $admins ? 'active' : '' }}" id="admins-view-tab" data-toggle="tab" href="#admins" role="tab" aria-controls="contact" aria-selected="false">Admins</a>
            </li>
        @endif
       
    </ul>

</div>

	    
<div class="tab-content clearfix">            

    <div class="tab-pane {{ $applicants ? 'active' : '' }}" id="applicants">        
        @include('admin.view.applicants')
	</div>

	<div class="tab-pane {{ $students ? 'active' : '' }}" id="students">
        @include('admin.view.students')
	</div>

    @if ($admin->position == "superadmin")
        <div class="tab-pane {{ $faculties ? 'active' : '' }}" id="facultyMembers">
            @include('admin.view.faculty')
            @if ($faculties)
            <script>
                viewFaculty(document.getElementById('faculty-view-tab'));
            </script>                   
            @endif
        </div>

        <div class="tab-pane {{ $admins ? 'active' : '' }}" id="admins">        
            @include('admin.view.admins')
        </div> 
    @endif
                  
</div>


<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}


// nav_links = document.getElementsByClassName('view-btns');

//     for(let i in nav_links){
//         if(nav_links[i].tagName == "A")
//             nav_links[i].style.pointerEvents = "auto";      
// }   



</script>


@endsection
