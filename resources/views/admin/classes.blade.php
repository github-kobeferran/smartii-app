@extends('layouts.module')

@section('page-title')
    Classes
@endsection

@section('content')

<?php 
    $create = false;
    $view = false;
    $rooms = false;
    $archived = false;
?>

@if ( session()->has('active') )
<?php 

    $value = session('active');

    switch ($value) {
        case 'create':
            $create = true;
            break;
        case 'view':
            $view = true;
            break;     
        case 'rooms':
            $rooms = true;
            break;     
        case 'archived':
            $archived = true;
            break;     
        
        default:
            $create = true;
            break;
    }
?>

@else     
<?php    

     $create = true;
?>
    
@endif

@if ($active == 'archived')
    <?php
        $archived = true;
        $create = false;
    ?>
@endif

<h5>Classes </h5>

<div  id="classesTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $create ? 'active' : '' }}" id="create-class-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="false">Class Assignment</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $view ? 'active' : '' }}" id="view-class-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">View Classes</a>
        </li>             
        <li class="nav-item">
            <a class="nav-link {{ $archived ? 'active' : '' }}" id="archived-class-tab" data-toggle="tab" href="#archived" role="tab" aria-controls="view" aria-selected="false">View Archived Classes</a>
        </li>             
        <li class="nav-item">
            <a class="nav-link {{ $rooms ? 'active' : '' }}" id="rooms-class-tab" data-toggle="tab" href="#rooms" role="tab" aria-controls="view" aria-selected="false">Rooms</a>
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
	<div class="tab-pane {{ $create ? 'active' : '' }}" id="create">
        @include('admin.classes.create')       
	</div>

	<div class="tab-pane {{ $view ? 'active' : '' }}" id="view">
        @include('admin.classes.view') 
	</div>                

	<div class="tab-pane {{ $archived ? 'active' : '' }}" id="archived">
        @include('admin.classes.archived') 
	</div>                

	<div class="tab-pane {{ $rooms ? 'active' : '' }}" id="rooms">
        @include('admin.classes.rooms') 
	</div>                
</div>

<script>

if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}

var programID = {value : null};

window.addEventListener('load', (event) => {        

    changeClassesSelects();     
    availableRooms(); 
    availableFaculty();       
    updateSchedCounter();
    fillProgramList(0);
    fillRoomTable();    
    
   

}); 
        
</script>


@endsection