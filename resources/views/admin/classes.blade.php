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
        <li class="nav-item">
            <a class="nav-link {{ session('rooms') ? 'active' : '' }}" id="rooms-tab" data-toggle="tab" href="#rooms" role="tab" aria-controls="rooms" aria-selected="false">Rooms</a>
        </li>       
    </ul>

</div>

	    
<div class="tab-content clearfix">
	<div class="tab-pane {{ session('create') ? 'active' : '' }}" id="create">
        @include('admin.classes.create')       
	</div>

	<div class="tab-pane {{ session('view') ? 'active' : '' }}" id="view">
        
	</div>

    <div class="tab-pane {{ session('rooms') ? 'active' : '' }}" id="rooms">
        
	</div>       
    
      
</div>


@endsection