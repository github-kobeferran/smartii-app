@extends('layouts.module')

@section('content')
<h2>Classes</h2>
{{-- <span id="object" class="h4">Student</span> --}}

<div  id="classesTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ session('create') ? 'active' : '' }}" id="create-class-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="false">Class Assignment</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ session('view') ? 'active' : '' }}" id="view-class-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">View</a>
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
	<div class="tab-pane {{ session('create') ? 'active' : '' }}" id="create">
        @include('admin.classes.create')       
	</div>

	<div class="tab-pane {{ session('view') ? 'active' : '' }}" id="view">
        @include('admin.classes.view') 
	</div>                
</div>

<script>

    window.addEventListener('load', (event) => {
        

        changeClassesSelects(); 
        availableRooms(); 
        availableFaculty();   
        updateSchedCounter();
        changeViewSelects();
        fillRoomTable();
        
    }); 
    
    
    </script>


@endsection