@extends('layouts.module')

@section('content')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

@include('inc.messages')

<?php 
    $currentSetting = \App\Models\Setting::first();

    $yearNow= \Carbon\Carbon::now();
    $now = $yearNow->year;
    $min = $yearNow->subYears(1)->year;
    $maxFrom = $yearNow->addYear(1)->year;
    $maxTo = $yearNow->addYear(2)->year;

?>
<h5 class="">Settings</h5>

<div class="row">        
    <div class="col-sm-6">                
        <span clas=""><em class=" lead mb-3">Set your defaults here</em></span>
        <hr>
{!! Form::open(['url' => 'admin/update/setting', 'id' => 'settingsForm']) !!}

<b>Academic Year</b> 

    <div class="form-inline m-2">            
        {{ Form::label('fromyear', 'Starting Year', ['class' => 'm-2']) }}
        {{ Form::number('from', $currentSetting->from_year, ['min' => $min, 'max' => $maxFrom, 'placeholder' => $currentSetting->from_year, 'class' => 'form-control']) }}
    </div>
    <div class="form-inline m-2">            
        {{ Form::label('toyear', 'Ending Year', ['class' => 'm-2']) }}
        {{ Form::number('to', $currentSetting->to_year, ['min' => $now, 'max' => $maxTo, 'placeholder' => $currentSetting->from_year, 'class' => 'form-control']) }}
    </div>
    
    <hr>

<b>Semester</b>

    <div class="form-group m-2">
        
        {{Form::select('sem', ['1' => 'First', '2' => 'Second'], $currentSetting->semester, ['class' => 'form-control w-25'])}}

    </div>
    <hr>

<b>Prices per Unit</b>    

    <div class="form-inline m-2">            
        {{ Form::label('shsprice', 'SHS price/unit', ['class' => 'm-2']) }}
        {{ Form::number('shs_price', $currentSetting->shs_price_per_unit, ['min' =>'0', 'max' => "1000", 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control']) }}
    </div>

    <div class="form-inline m-2">            
        {{ Form::label('collegeprice', 'College price/unit', ['class' => 'm-2']) }}
        {{ Form::number('col_price', $currentSetting->college_price_per_unit, ['min' =>'0', 'max' => "1000", 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control']) }}
    </div>
    <hr>
<b>Number of Student per Class</b> 

    <div class="form-inline m-2">                    
        {{ Form::number('class_quantity', $currentSetting->class_quantity, ['min' =>'1', 'max' => "50", 'placeholder' => $currentSetting->class_quantity, 'class' => 'form-control']) }}
    </div>

    <hr class="shadow">

    <div class="form-group">

        {{Form::submit('Save', ['class' => 'btn btn-success w-50'])}}

    </div>
        
{!! Form::close() !!}

        
    </div>

    <div class="col-sm-6">


    </div>

</div>



@endsection