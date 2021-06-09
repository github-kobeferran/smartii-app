@extends('layouts.module')

@section('content')

<?php 
    $create = false;
    $view = false;
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

<div  id="classesTab" class="bs-example"> 
     <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $create ? 'active' : '' }}" id="create-class-tab" data-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="false">Class Assignment</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $view ? 'active' : '' }}" id="view-class-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">View</a>
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