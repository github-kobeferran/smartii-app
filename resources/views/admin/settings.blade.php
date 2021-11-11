@extends('layouts.module')

@section('page-title')
    Settings
@endsection

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


    function semDesc($sem){

        switch ($sem) {
            case 1:
                echo "Every First Semester";
                break;
            case 2:
                echo "Every Second Semester";
                break;
            case 5:
                echo "Every Semester";
                break;                    
        }

    }

?>
<h5 class="">Settings</h5>

<div class="row">  

    <div class="col-sm-8">                                
{!! Form::open(['url' => 'admin/update/setting', 'id' => 'settingsForm']) !!}

    <div class="container border m-2">

        <p class="mt-2"> <b> Academic Year  </b></p> 

        <div class="form-inline m-2">            
            {{ Form::label('fromyear', 'Starting Year', ['class' => 'm-2']) }}
            {{ Form::number('from', $currentSetting->from_year, ['id' => 'fromYear', 'min' => $min, 'max' => $maxFrom, 'placeholder' => $currentSetting->from_year, 'class' => 'form-control']) }}            
        </div>
        
        <div class="form-inline m-2">            
            {{ Form::label('toyear', 'Ending Year', ['class' => 'm-2']) }}
            {{ Form::number('to', $currentSetting->to_year, ['id' => 'toYear','min' => $now, 'max' => $maxTo, 'placeholder' => $currentSetting->from_year, 'class' => 'form-control']) }}
        </div>        

        <p class="mt-2"> <b> Semester</b></p>

        <div class="form-group mb-3">
            
            {{Form::select('sem', ['1' => 'First', '2' => 'Second'], $currentSetting->semester, ['id' => 'semester','class' => 'form-control w-25'])}}

        </div>

    </div>

    <div class="container border  m-2">

        <p class="mt-2"> <b>Prices per Unit</b></p>     

        <div class="form-inline m-2">            
            {{ Form::label('shsprice', 'SHS price/unit', ['class' => 'm-2']) }}
            {{ Form::number('shs_price', $currentSetting->shs_price_per_unit, ['min' =>'0', 'max' => "1000", 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control']) }}
        </div>

        <div class="form-inline m-2">            
            {{ Form::label('collegeprice', 'College price/unit', ['class' => 'm-2']) }}
            {{ Form::number('col_price', $currentSetting->college_price_per_unit, ['min' =>'0', 'max' => "1000", 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control']) }}
        </div>        

        <p class="mt-2"> <b>Payment Details</b></p> 

        <div class="form-inline m-2">            
            {{ Form::label('gcash', 'Gcash', ['class' => 'm-2']) }}
            {{ Form::text('gcash', $currentSetting->gcash_number, ['maxLength' => '25', 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control']) }}
        </div>

        {{ Form::label('bank', 'Bank Details', ['class' => 'm-2']) }}    
        
        <div class="form-inline m-2 ">            
            
            {{ Form::textarea('bank_name', $currentSetting->bank_name, ['maxLength' => '191', 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control w-25', 'placeholder' => 'Bank Name here']) }}
            {{ Form::textarea('bank_number', $currentSetting->bank_number, ['maxLength' => '191', 'placeholder' => $currentSetting->shs_price_per_unit, 'class' => 'form-control w-25', 'placeholder' => 'Account Number here']) }}
            
        </div>
        
        <em> match the bank name and account number per line</em>

    </div>
        
    <div class="container border m-2">

        <p class="mt-2"> <b>Number of Student per Class</b></p>  

        <div class="form-inline m-2">                    
            {{ Form::number('class_quantity', $currentSetting->class_quantity, ['min' =>'1', 'max' => "50", 'placeholder' => $currentSetting->class_quantity, 'class' => 'form-control']) }}
            
        </div>    


    </div>

    <div class="container border  m-2">

        <p class="mt-2"> <b>Enrollment Mode</b></p>  

        <div class="form-group m-2">
            
            {{Form::select('mode', ['0' => 'Close', '1' => 'Open'], $currentSetting->enrollment_mode, ['class' => 'form-control w-25', 'id' => 'enrollment-mode-input'])}}            

        </div>

    </div>
    

    <div class="form-group">

        {{Form::submit('Save', ['id' => 'submitButton', 'class' => 'btn btn-success w-50'])}}            
          
          <!-- Modal -->
          <div class="modal fade" id="confirmSubmit" tabindex="-1" role="dialog" aria-labelledby="confirmSubmit" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header bg-success">
                  <h5 class="modal-title" id="exampleModalLongTitle">Update Semester Values</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>                
                <div id="confirmSubmitBody" class="modal-body">
                  
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
              </div>
            </div>
          </div>        

    </div>
        
{!! Form::close() !!}

        
    </div>

    <div class="col-sm-4 ">

    <hr>
        <div class="container" id="addButton">

            <button type="button" onclick="createFee()" data-toggle="tooltip" data-placement="top" title="Add a Fee" class="btn btn-outline-info py-1 mr-2">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </button>

            Add a Fee 

        </div>

        <div class="container d-none" id="addFeePanel">

            <h5 id="formtitle">ADD A FEE</h5>

            <div  class="form-inline mt-2">
                {!!Form::open(['url' => 'addfee', 'id' => 'feeForm'])!!}
                    
                    Fee Description
                    <div class="form-group">
    
                        {{Form::text('desc', '', ['id' => 'descInput', 'class' => 'form-control', 'placeholder' => 'ex. miscellaneous fee', 'required' => 'required'])}}
    
                    </div>
    
                    Amount
    
                    <div class="form-group">
    
                        {{Form::number('amount', '', ['id' => 'amountInput', 'class' => 'form-control mb-2', 'step' => '.01' , 'placeholder' => 'enter amount here', 'required' => 'required'])}}
    
                    </div>

                    For whom:

                    <div class="form-group mb-2">
                        {{Form::select('dept', ['2' => "All Students",

                                                '0' => "SHS Students",
                                                '1' => "College Students"], null,
                                                ['id' => 'deptSelect', 'class' => 'form-control', 'placeholder' => 'Select an option', 'required' => 'required'])}}

                    </div>

                    For what Program:

                    <div class="form-group mb-2">

                        {{Form::select('prog', ["" => 'For All Programs'], null,[ 'id' => 'progSelect',
                                                          'class' => 'form-control',                                                                                                                    
                                                        ])}}                        

                    </div>

                    Level:

                    <div class="form-group mb-2">

                        {{Form::select('level', [], 50,
                                                ['id' => 'levelSelect', 'placeholder' => 'Select Level', 'class' => 'form-control'])}}

                    </div>

                    Semester:

                    <div class="form-group mb-2">

                        {{Form::select('sem', ['' => "Every Semester",
                                                '1' => "For First Semester",
                                                '2' => "For Second Semester"], null,
                                                ['id' => 'semSelect', 'class' => 'form-control', 'placeholder' => 'select'])}}

                    </div>
                        
                    <button type="button" id="feeFormSubmit" data-toggle="modal" data-target="#addFeeModal" class="btn btn-success rounded-0">Save</button>
                    
                    <div class="modal fade" id="addFeeModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmit" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>                
                            <div id="" class="modal-body">
                                Adding this fee will add it's amount to matching students balance, continue?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="" class="btn btn-success ">Yes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </div>
                          </div>
                        </div>
                    </div>        
                    <div class="modal fade" id="updateFeeModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmit" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-success">
                              <h5 class="modal-title" id="exampleModalLongTitle">Confirm Update</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>                
                            <div id="" class="modal-body">
                                Updating this fee will compute its difference to its old fee amount and also update it's matching students balance, continue?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="" class="btn btn-success ">Yes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </div>
                          </div>
                        </div>
                    </div>        
            

                    <button type="button" onclick="cancelAdd()" class="btn btn-secondary">Cancel</button>

                    {!!Form::close()!!}                      
    
            </div>


        </div>

       <hr>
        
        @if(\App\Models\Fee::count() > 0)


        <div class=" border container">

            <h5 class="mt-3 text-center">FEES</h5>

            <p class="mt-2"> <b>For All Students:</b></p> 

            @if (\App\Models\Fee::where('dept', 2)->count() > 0)

                Every semester: 
                    @if (\App\Models\Fee::where('dept', 2)->whereNull('sem')->count() > 0)

                    
                        <ul class="list-group">
                            @foreach (\App\Models\Fee::where('dept', 2)->whereNull('sem')->get()  as $fee)
                        
                            <li class="list-group-item border rounded-0 mb-2">
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all students' : '<span class="text-dark bg-info px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>
                            </li>   

                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>                    
                            
                            @endforeach
                        </ul>
                        
                    @else
                        <span class="text-muted">None</span>
                    @endif


                Every First Semester: 
                    @if (\App\Models\Fee::where('dept', 2)->where('sem', 1)->count() > 0)

                    
                        <ul class="list-group">
                            @foreach (\App\Models\Fee::where('dept', 2)->where('sem', 1)->get()  as $fee)
                        
                            <li class="list-group-item border rounded-0 mb-2">
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all students' : '<span class="text-dark bg-info px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>
                            </li>   

                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>                    
                            
                            @endforeach
                        </ul>
                        
                    @else
                        <span class="text-muted">None</span>
                    @endif

                <br>

                Every Second Semester: 
                    @if (\App\Models\Fee::where('dept', 2)->where('sem', 2)->count() > 0)

                    
                        <ul class="list-group">
                            @foreach (\App\Models\Fee::where('dept', 2)->where('sem', 2)->get()  as $fee)
                        
                            <li class="list-group-item border rounded-0 mb-2">
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all students' : '<span class="text-dark bg-info px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>
                            </li>   

                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>                    
                            
                            @endforeach
                        </ul>
                        
                    @else
                        <span class="text-muted">None</span>
                    @endif

            @else 

            <li class="list-group-item border rounded-0 mb-2">
                None
            </li>
                
            @endif

            <hr>

            <p class="mt-2"> <b>For SHS only</b></p>

            @if (\App\Models\Fee::where('dept', 0)->count() > 0)

                @if (\App\Models\Fee::where('dept', 0)->whereNull('level')->count() > 0)

                    <button onclick="toggleButton('allshs')" class="btn btn-warning text-dark rounded-0 btn-block mb-2">
                        ALL SHS Fees
                    </button>

                
                    
                    <ul class="list-group">
                        @foreach (\App\Models\Fee::where('dept', 0)->whereNull('level')->orderBy('sem')->get()  as $fee)                            
        
                            <li class="list-group-item border border-secondary mb-2 details allshs d-none">                              
                                
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all SHS students' : '<span class="text-dark bg-warning px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>

                            </li>   
        
                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>
        
                        @endforeach
        
                    </ul>

                @endif

                @if (\App\Models\Fee::where('dept', 0)->where('level', 1)->count() > 0)

                    <button onclick="toggleButton('grade11')" class="btn btn-warning text-dark rounded-0 btn-block mb-2">
                        Grade 11 Fees
                    </button>

                
                    
                    <ul class="list-group">
                        @foreach (\App\Models\Fee::where('dept', 0)->where('level', 1)->orderBy('sem')->get()  as $fee)                            
        
                            <li class="list-group-item border border-secondary mb-2 details grade11 d-none">                              
                                
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all SHS students' : '<span class="text-dark bg-warning px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>

                                
                            </li>   
        
                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>
        
                        @endforeach
        
                    </ul>

                @endif

                @if(\App\Models\Fee::where('dept', 0)->where('level', 2)->count() > 0)

                    <button onclick="toggleButton('grade12')" class="btn btn-warning text-dark rounded-0 btn-block mb-2">
                        Grade 12 Fees
                    </button>


                    <ul class="list-group">
                        @foreach (\App\Models\Fee::where('dept', 0)->where('level', 2)->orderBy('sem')->get()  as $fee)                            
        
                            <li class="list-group-item border border-secondary mb-2 details grade12 d-none">
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all SHS students' : '<span class="text-dark bg-warning px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>

                            </li>   
        
                            <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    {!!Form::open(['url' => '/deletefee'])!!}
                                    <div class="modal-body">
                                        Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                        {{Form::hidden('id', $fee->id)}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                    {!!Form::close()!!}
                                </div>
                                </div>
                            </div>
        
                        @endforeach
        
                    </ul>

                    
                @endif                

                
                               
            @else 

            <li class="list-group-item border rounded-0 mb-2">
                None
            </li>
            
            @endif

            <hr>

            <p class="mt-2"> <b>For College only</b></p>            

            @if (\App\Models\Fee::where('dept', 1)->count() > 0)

                @if (\App\Models\Fee::where('dept', 1)->whereNull('level')->count() > 0)

                    <button onclick="toggleButton('allcol')" class="btn btn-success text-white rounded-0 btn-block mb-2">
                        ALL College
                    </button>

            
                
                <ul class="list-group">
                    @foreach (\App\Models\Fee::where('dept', 1)->where('level', 15)->orderBy('sem')->get()  as $fee)                            

                        <li class="list-group-item border border-secondary mb-2 details allcol d-none">                              
                            
                            <span>
                                <span>
                                    <?php semDesc($fee->sem); ?> 
                                    <br>
                                    {!!is_null($fee->program_id) ? 'For all College students' : '<span class="text-dark bg-success px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                    <br>
                                    <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                </span>

                                <span >
                                    <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                    <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                </span>

                            </span>

                        </li>   

                        <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                {!!Form::open(['url' => '/deletefee'])!!}
                                <div class="modal-body">
                                    Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                    {{Form::hidden('id', $fee->id)}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                </div>
                                {!!Form::close()!!}
                            </div>
                            </div>
                        </div>

                    @endforeach

                </ul>

            @endif



                @if (\App\Models\Fee::where('dept', 1)->where('level', 11)->count() > 0)

                        <button onclick="toggleButton('firstyear')" class="btn btn-success text-white rounded-0  btn-block mb-2">
                            First Year
                        </button>

                    
                        
                        <ul class="list-group">
                            @foreach (\App\Models\Fee::where('dept', 1)->where('level', 11)->orderBy('sem')->get()  as $fee)                            
            
                                <li class="list-group-item border border-secondary mb-2 details firstyear d-none">
                                    <span>
                                        <span>
                                            <?php semDesc($fee->sem); ?> 
                                            <br>
                                            {!!is_null($fee->program_id) ? 'For all College students' : '<span class="text-dark bg-success px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                            <br>
                                            <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                        </span>
    
                                        <span >
                                            <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                            <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                        </span>
    
                                    </span>
                                </li>   
            
                                <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        {!!Form::open(['url' => '/deletefee'])!!}
                                        <div class="modal-body">
                                            Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                            {{Form::hidden('id', $fee->id)}}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                        </div>
                                        {!!Form::close()!!}
                                    </div>
                                    </div>
                                </div>
            
                            @endforeach
            
                        </ul>

                @endif

                @if (\App\Models\Fee::where('dept', 1)->where('level', 12)->count() > 0)

                <button onclick="toggleButton('secondyear')" class="btn btn-success text-white rounded-0  btn-block mb-2">
                    Second Year
                </button>

            
                
                <ul class="list-group">
                    @foreach (\App\Models\Fee::where('dept', 1)->where('level', 12)->orderBy('sem')->get()  as $fee)                            
    
                        <li class="list-group-item border border-secondary mb-2 details secondyear d-none">
                                <span>
                                    <span>
                                        <?php semDesc($fee->sem); ?> 
                                        <br>
                                        {!!is_null($fee->program_id) ? 'For all College students' : '<span class="text-dark bg-success px-2">For '. \App\Models\Program::find($fee->program_id)->abbrv .' students</span>'!!}
                                        <br>
                                        <u>{{ucfirst($fee->desc)}}</u>: <b>&#8369; {{ number_format((float) $fee->amount, 2, '.', '')  }}</b>
                                    </span>

                                    <span >
                                        <span role="button" data-toggle="modal" data-target="#modal-{{$fee->id}}" class="float-right text-danger mr-2"><i data-toggle="tooltip" title="remove" class="fa fa-minus" aria-hidden="true"></i></span>
                                        <span role="button" onclick="editFee({{$fee->id}})" type="button" class="float-right text-primary mr-2"><i data-toggle="tooltip" title="edit" class="fa fa-pencil-square" aria-hidden="true"></i></span>
                                    </span>

                                </span>
                        </li>   
    
                        <div class="modal fade" id="modal-{{$fee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete this Fee?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                {!!Form::open(['url' => '/deletefee'])!!}
                                <div class="modal-body">
                                    Deleting {{$fee->desc}} will subtract the amount to matching students, continue?                                                                                
                                    {{Form::hidden('id', $fee->id)}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light border border-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Confirm</button>
                                </div>
                                {!!Form::close()!!}
                            </div>
                            </div>
                        </div>
    
                    @endforeach
    
                </ul>

        @endif

            @else 

                <li class="list-group-item border rounded-0 mb-2">
                    None
                </li>
            @endif              


        </div>
           
       
        @endif

    </div>

</div>

<script>

let fromYear = document.getElementById('fromYear');
let toYear = document.getElementById('toYear');
let semester = document.getElementById('semester');
let submitButton = document.getElementById('submitButton');
let confirmSubmitBody = document.getElementById('confirmSubmitBody');
let enrollmentModeInput = document.getElementById('enrollment-mode-input');
let changes = {};
let enrollment_mode = {!! json_encode($currentSetting->enrollment_mode) !!};

enrollmentModeInput.addEventListener('change', () => {    

    if(enrollment_mode != enrollmentModeInput.value){

        changes.enrollment_mode = true;

        submitButton.type = 'button';
        submitButton.dataset.toggle = 'modal';
        submitButton.dataset.target = '#confirmSubmit';

        output = '';

        if(changes.fromYear == true)
            output += 'Starting Year, ';
        if(changes.toYear == true)
            output += 'Ending Year, ';
        if(changes.semester == true)
            output += 'Semester, ';
        if(changes.enrollment_mode == true)
            output += 'Enrollment Mode, ';

        confirmSubmitBody.textContent = output + (Object.keys(changes).length > 1 ? ' value' : ' values' ) + ' has been changed wish to continue?';
        
    }

});

fromYear.addEventListener('change', () => {

    changes.fromYear = true;

    submitButton.type = 'button';
    submitButton.dataset.toggle = 'modal';
    submitButton.dataset.target = '#confirmSubmit';

    output = '';

    if(changes.fromYear == true)
        output += 'Starting Year, ';
    if(changes.toYear == true)
        output += 'Ending Year, ';
    if(changes.semester == true)
        output += 'Semester, ';
    if(changes.enrollment_mode == true)
        output += 'Enrollment Mode, ';


    confirmSubmitBody.textContent = output + (Object.keys(changes).length > 1 ? ' value' : ' values' ) + ' has been changed wish to continue?';

});

toYear.addEventListener('change', () => {

    changes.toYear = true;

    submitButton.type = 'button';
    submitButton.dataset.toggle = 'modal';
    submitButton.dataset.target = '#confirmSubmit';

    output = '';

    if(changes.fromYear == true)
        output += 'Starting Year, ';
    if(changes.toYear == true)
        output += 'Ending Year, ';
    if(changes.semester == true)
        output += 'Semester, ';
    if(changes.enrollment_mode == true)
        output += 'Enrollment Mode, ';


        confirmSubmitBody.textContent = output + (Object.keys(changes).length > 1 ? ' value' : ' values' ) + ' has been changed wish to continue?';

});

semester.addEventListener('change', () => {

    changes.semester = true;

    submitButton.type = 'button';
    submitButton.dataset.toggle = 'modal';
    submitButton.dataset.target = '#confirmSubmit';

    output = '';

    if(changes.fromYear == true)
        output += 'Starting Year, ';
    if(changes.toYear == true)
        output += 'Ending Year, ';
    if(changes.semester == true)
        output += 'Semester, ';
    if(changes.enrollment_mode == true)
        output += 'Enrollment Mode, ';


        confirmSubmitBody.textContent = output + (Object.keys(changes).length > 1 ? ' value' : ' values' ) + ' has been changed wish to continue?';

});



if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                location.reload();
}


let deptSelect = document.getElementById('deptSelect');
let levelSelect = document.getElementById('levelSelect');
let progSelect = document.getElementById('progSelect');

deptSelect.addEventListener('change', deptIsChanged);

async function deptIsChanged(){

     if(deptSelect.value != 2){

        const res = await fetch(APP_URL + `/admin/view/programs/department/${deptSelect.value}/`)
                        .catch((error) => {console.log(error)});

        const data = await res.json();

        let output = `<select name="prog" value="" id="progSelect" class="form-control"`;
        output+=`<option selected="selected" value="">Select a Program</option>`;

            data.forEach((prog, i) => {
                if(i < 1)
                    output+=`<option selected="selected" value=""> All ` + (deptSelect.value == 0 ? `SHS` : `College`) + ` Programs</option>`;

                output+=`<option value="${prog.id}"> ${prog.desc} </option>`;
            });

        output+=`</select>`;

        progSelect.innerHTML = output;

        if(deptSelect.value == 0){
            levelSelect.innerHTML = `  {{Form::select('level', ['' => "For all SHS levels",
                                                '1' => " For Grade 11 only",
                                                '2' => "For Grade 12 only"], null,
                                                ['id' => 'levelSelect', 'class' => 'form-control d-none'])}}`;  
        
        } else if(deptSelect.value == 1){
            levelSelect.innerHTML = `  {{Form::select('level', ['' => "All College Levels",
                                                '11' => "For First Year only",
                                                '12' => "For Second Year only"], null,
                                                ['id' => 'levelSelect', 'class' => 'form-control d-none'])}}`;
        }

    } else {
        progSelect.innerHTML = `{{Form::select('prog', ['' => 'For All Programs'], 
                                                          null,[ 'id' => 'progSelect',
                                                          'class' => 'form-control',
                                                          'placeholder' => 'Select a Program'
                                                        ])}}`;

        levelSelect.innerHTML = `  {{Form::select('level', ['' => "For all Student levels"], null,
                                        ['id' => 'levelSelect', 'class' => 'form-control'])}}`; 
    }     

}

progSelect.addEventListener('change', progIsChanged);

async function progIsChanged(){

      if(progSelect.value != null && progSelect.value != ''){

        const res = await fetch(APP_URL + '/admin/view/programs/' + progSelect.value)
                            .catch(error => console.log(error));
        const data = await res.json();        

        if(deptSelect.value == 0){
            levelSelect.innerHTML = `<select name="level" id="levelSelect" class="form-control"> 
                                        <option value="">For all ${data.abbrv} students</option>
                                        <option value="1">For Grade 11-${data.abbrv} only</option>
                                        <option value="2">For Grade 12-${data.abbrv} only</option>
                                    </select>`;
        }else{
            levelSelect.innerHTML = `<select name="level" id="levelSelect" class="form-control"> 
                                        <option value="">For all ${data.abbrv} students</option>
                                        <option value="11">For First Year-${data.abbrv} only</option>
                                        <option value="12">For Second-${data.abbrv} only</option>
                                    </select>`;            
        }


    } else {        

        if(deptSelect.value == 2){
            levelSelect.innerHTML = `  {{Form::select('level', ['' => "For all Student levels"], null,
                                        ['id' => 'levelSelect', 'class' => 'form-control'])}}`; 
   
        } else if(deptSelect.value == 0){
            levelSelect.innerHTML = `  {{Form::select('level', ['' => "For all SHS levels",
                                                '1' => " For Grade 11 only",
                                                '2' => "For Grade 12 only"], null,
                                                ['id' => 'levelSelect', 'class' => 'form-control d-none'])}}`;  
        
        } else if(deptSelect.value == 1){
            levelSelect.innerHTML = `  {{Form::select('level', ['10' => "All College Levels",
                                                '11' => "For First Year only",
                                                '12' => "For Second Year only"], 15,
                                                ['id' => 'levelSelect', 'class' => 'form-control d-none'])}}`;
        }

    }

}



function createFee(){

    let btn = document.getElementById('addButton');
    let panel = document.getElementById('addFeePanel');
    let feeForm = document.getElementById('feeForm');    

    btn.classList.add('d-none');
    panel.classList.remove('d-none');

    document.getElementById('formtitle').textContent = 'ADD A FEE';
    feeForm.action = APP_URL + '/addfee';


}

function cancelAdd(){

    let btn = document.getElementById('addButton');
    let panel = document.getElementById('addFeePanel');

    btn.classList.remove('d-none');
    panel.classList.add('d-none');

    document.getElementById('formtitle').textContent = 'ADD A FEE';
    

    document.getElementById('descInput').value = '';
    document.getElementById('amountInput').value = '';

    document.getElementById('amountInput').innerHTML = `{{Form::number('amount', '', ['id' => 'amountInput', 'class' => 'form-control mb-2', 'step' => '.01' , 'placeholder' => 'enter amount here'])}}`;

    document.getElementById('deptSelect').innerHTML = `{{Form::select('dept', ['2' => "All Students",
                                                    '0' => "SHS Students",
                                                    '1' => "College Students"], null,
                                                    ['id' => 'deptSelect', 'class' => 'form-control', 'placeholder' => 'select'])}}`;

    document.getElementById('levelSelect').innerHTML = `{{Form::select('level', ['50' => "All Levels"], 50,
                                                ['id' => 'levelSelect', 'class' => 'form-control'])}}`;

    document.getElementById('semSelect').innerHTML = `{{Form::select('sem', ['' => "Every Semester",
                                                        '1' => "For First Semester",
                                                        '2' => "For Second Semester"], null,
                                                        ['id' => 'semSelect', 'class' => 'form-control', 'placeholder' => 'select'])}}`;


    document.getElementById('feeFormSubmit').innerHTML = `<button type="button" id="feeFormSubmit" data-toggle="modal" data-target="#addFeeModal" class="btn btn-success rounded-0 p-0">Save</button>`;

   


}

function toggleButton(className){

    let details = document.getElementsByClassName('details');  
    let data = document.getElementsByClassName(className); 

    if(data[0].classList.contains('opened')){

        for(let i=0; i<details.length; i++){
           details[i].classList.add('d-none');
           details[i].classList.remove('opened');
        }       

    } else{

        for(let i=0; i<details.length; i++){
            details[i].classList.add('d-none');
        }

        for(let i=0; i<data.length; i++){
            data[i].classList.remove('d-none');
            data[i].classList.add('opened');
        }

    }

}

function editFee(id){    

    let btn = document.getElementById('addButton');
    let panel = document.getElementById('addFeePanel');
    let feeForm = document.getElementById('feeForm');
    
    feeForm.action = APP_URL + '/editfee';    

    btn.classList.add('d-none');
    panel.classList.remove('d-none');    
    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/fees/' + id, true);

    xhr.onload = async function() {    

        if (this.status == 200) {                                

            let fee = JSON.parse(this.responseText);   

            document.getElementById('formtitle').textContent = 'EDIT FEE: ' + fee.desc;

            let feeid = document.createElement("input"); 

            feeid.setAttribute("type", "hidden");
            feeid.setAttribute("id", "feeID");
            feeid.setAttribute("name", "id");
            feeid.setAttribute("value", fee.id);

            let olddesc = document.createElement("input");
            
            olddesc.setAttribute("type", "hidden");
            olddesc.setAttribute("olddesc", "olddesc");
            olddesc.setAttribute("name", "olddesc");
            olddesc.setAttribute("value", fee.desc);
            
            feeForm.appendChild(feeid);
            feeForm.appendChild(olddesc);

            document.getElementById('descInput').value = fee.desc;
            document.getElementById('amountInput').value = fee.amount;                    

            let one = false;
            let two = false;
            let zero = false;

            let programs = null;

            let grade11 = false;
            let grade12 = false;
            let firstyear = false;
            let secondyear = false;
            let allSHS = false;
            let allCol = false;
            let allStudents = false;

            let firstsem = false;
            let secondsem = false;
            let everysem = false;          

            let res = null;
            
            switch(fee.dept){
                case 2: 
                    two = true;
                break;
                case 0: 
                    zero = true;
                    res = await fetch(APP_URL + '/admin/view/programs/department/' + fee.dept)
                                .catch(error => console.log(error));

                    programs = await res.json();
                break;
                case 1: 
                    one = true;

                    res = await fetch(APP_URL + '/admin/view/programs/department/' + fee.dept)
                                .catch(error => console.log(error));

                    programs = await res.json();
                break;
            }                     

            if(fee.level == 1 || fee.level == 2)
                level = 'shs';
            else if(fee.level == 11 || fee.level == 12)
                level = 'col';
            else 
                level = 'all';


            switch(fee.level){
                case 1: 
                    grade11 = true;
                break;
                case 2: 
                    grade12 = true;
                break;
                case 11: 
                    firstyear = true;
                break;
                case 12: 
                    secondyear = true;
                break;
                case 5: 
                    allSHS = true;
                break;
                case 15: 
                    allCol = true;
                break;
                case 50: 
                    allStudents = true;
                break;
            }

            switch(fee.sem){
                case 1: 
                    firstsem = true;
                break;
                case 2: 
                    secondsem = true;
                break;
                case 5: 
                    everysem = true;
                break;
                
            }

            let ouput = '';

            output = `<select name="dept" class="form-control" id="deptSelect">
                                <option value="2" `; two ? output+='selected="selected"' : output+=''; output+=`>All Students</option>
                                <option value="0" `; zero ? output+='selected="selected"' : output+=''; output+=`>SHS Students</option>
                                <option value="1" `; one ? output+='selected="selected"' : output+=''; output+=`>College Students</option>                            
                            </select>`;

            document.getElementById('deptSelect').innerHTML = output;     


            if(fee.dept != 2){
                output = `<select name="prog" class="form-control" id="progSelect">
                            <option value="" >${(fee.dept == 0 ? 'For all SHS programs' :'For all College programs')}</option>`;
                            
                        programs.forEach(prog => {
                            output+= `<option value="${prog.id}" ${(fee.program_id == prog.id ? `selected="selected"` : ``)}> ${prog.desc} </option>`;
                        });                                                  

                document.getElementById('progSelect').innerHTML = output;    

            } else {
                output = `<select name="dept" class="form-control" id="">
                                <option value="" selected="selected" >For All Programs</option>
                            </select>`;

                document.getElementById('levelSelect').innerHTML = output;
            }

            

            if(level == 'shs'){ 

                output = `<select name="dept" class="form-control" id="">
                                <option value="" `; allSHS ? output+='selected="selected"' : output+=''; output+=`>All Students</option>
                                <option value="1" `; grade11 ? output+='selected="selected"' : output+=''; output+=`>Grade 11</option>
                                <option value="2" `; grade12 ? output+='selected="selected"' : output+=''; output+=`>Grade 12</option>                            
                            </select>`;

                document.getElementById('levelSelect').innerHTML = output;

            } else if(level == 'col'){

                output =  ` <select name="dept" class="form-control" id="">
                                <option value="" `; allCol ? output+='selected="selected"' : output+=''; output+=`>All College</option>
                                <option value="11" `; firstyear ? output+='selected="selected"' : output+=''; output+=`>First Year</option>
                                <option value="12" `; secondyear ? output+='selected="selected"' : output+=''; output+=`>Second Year</option>                            
                            </select>`;

                document.getElementById('levelSelect').innerHTML = output;


            } else {
                output = ` <select name="level" class="form-control" id="">
                                <option value="" `; allStudents ? output+='selected="selected"' : output+=''; output+=`>All Students</option>                                
                            </select>`;

                document.getElementById('levelSelect').innerHTML = output;
            }

            output = ` <select name="sem" class="form-control" id="">
                                <option value="" `; everysem ? output+='selected="selected"' : output+=''; output+=`>Every Semester</option>
                                <option value="1" `; firstsem ? output+='selected="selected"' : output+=''; output+=`>First Semester</option>
                                <option value="2" `; secondsem ? output+='selected="selected"' : output+=''; output+=`>Second Semester</option>                            
                        </select>`;

            document.getElementById('semSelect').innerHTML = output;
            
            document.getElementById('feeFormSubmit').setAttribute("data-target", "#updateFeeModal");
            document.getElementById('feeFormSubmit').textContent = "Update Fee";

            panel.scrollIntoView({behavior: 'smooth'});
            
                            
        } 
    }

    xhr.send(); 

}


</script>


@endsection

