{!! Form::open(['url' => 'admin/create/program', 'files' => true, 'id' => 'programForm']) !!}    

    <div class="row">    

        <div class="col-sm"> 
            {{Form::label('department', 'Department')}}
                <div class="form-group">
                    
                    {{Form::select('dept', 
                      ['0' => 'Senior High School',                              
                      '1' => 'College'], 0,
                      ['class' => 'custom-select w-50 ml-2', 'id' => 'selectDept'])}}                   
                </div>
                
                <div class = "form-group">        
                    {{Form::label('desc', 'Program Description', ['class' => 'mt'])}}
                    {{Form::text('desc', '', ['class' => 'form-control', 'placeholder' => 'Course/Strand Description'])}}
                </div> 

        </div>

        <div class="col">                

        </div>

    </div>    

    <div class="row">    

        <div class="col-sm"> 

                <div class = "form-group">        
                    {{Form::label('abbrv', 'Program Abbreviation', ['class' => 'mt'])}}
                    {{Form::text('abbrv', '', ['class' => 'form-control w-50', 'placeholder' => 'Course/Strand Abbreviation'])}}
                </div>                                 

        </div>

        <div class="col-sm">                

        </div>

    </div>
    
    <hr class= "w-75 ml-0"/>
    
    

    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}   
    <div class = "form-group mr-0">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
    </div> 
    <hr class=""/> 

    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}


{!! Form::close() !!}

<script>

window.onbeforeunload = function(event)
{
    return '';
};

document.getElementById("programForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>
