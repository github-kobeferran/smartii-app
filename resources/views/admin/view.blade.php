@extends('layouts.module')


@section('content')
<h4>You're viewing <span id="object" class="h2">Student</span> List</h4>

<div  id="viewTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="applicants-view-tab" data-toggle="tab" href="#applicants" role="tab" aria-controls="profile" aria-selected="false">Applicants</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" id="students-view-tab" data-toggle="tab" href="#students" role="tab" aria-controls="home" aria-selected="true">Students</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" id="faculty-view-tab" data-toggle="tab" href="#facultyMembers" role="tab" aria-controls="profile" aria-selected="false">Faculty Members</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="admins-view-tab" data-toggle="tab" href="#admins" role="tab" aria-controls="contact" aria-selected="false">Admins</a>
        </li>
       
    </ul>

</div>

	    
<div class="tab-content clearfix">    

    <div class="tab-pane" id="applicants">
        Applicants ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
	</div>

	<div class="tab-pane active" id="students">
        Students
        {!! Form::open(['url' => 'foo/bar']) !!}
            
        {!! Form::close() !!}
	</div>

	<div class="tab-pane" id="facultyMembers">
        Faculty ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac est at eros malesuada lobortis eget quis elit. Mauris dapibus interdum mollis. Cras semper a.
        <br>
        <br>
        <?php 
            $id = 3;
            $year =  date("y");
            $prefix = "B";
            $user_id = $prefix . $year . '-' . sprintf('%04d', $id);
        
        ?>

        {{ 'for stud, emp, app id manipulation ' . $user_id}}
	</div>

    <div class="tab-pane" id="admins">
       @include('admin.view.admins')
	</div> 
                  
</div>

@endsection