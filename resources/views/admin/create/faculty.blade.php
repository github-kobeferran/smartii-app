{!! Form::open(['url' => 'admin/create/faculty', 'id' => 'facultyForm']) !!}

  
<div class="row">    

    <div class="col-9">
        
        <div class = "form-group">        
            {{Form::label('Last Name', 'Last Name', ['class' => 'mt'])}}
            {{Form::text('last_name', '', ['class' => 'form-control', 'placeholder' => 'Last Name here..'])}}
        </div> 

        <div class = "form-group">        
            {{Form::label('First Name', 'First Name', ['class' => 'mt'])}}
            {{Form::text('first_name', '', ['class' => 'form-control', 'placeholder' => 'First Name here..'])}}
        </div> 

        <div class = "form-group">        
            {{Form::label('Middle Name', 'Middle Name', ['class' => 'mt'])}}
            {{Form::text('middle_name', '', ['class' => 'form-control', 'placeholder' => 'Middle Name here..'])}}
        </div> 

        {{Form::label('dob', 'Date of Birth')}}
        <div class = "form-group">                        
            {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), ['class' => 'ml-2', 'id' => 'dob'] )}}
        </div> 

        <div class = "form-group">        
            {{Form::label('email', 'Email Address', ['class' => 'mt'])}}
            {{Form::email('email', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Email here..'])}}
        </div>      
        
        <?php
            $programs = \App\Models\Program::orderBy('id', 'asc')->pluck('abbrv', 'id');            
        ?>

        <label for="">Specialty (Select Program)</label>
        <div class="form-inline">            
            {{Form::select('program_id', $programs, null, ['required' => 'required', 'id' => 'programSelect', 'placeholder' => '--Select a Specialty--', 'class' => 'form-control mr-2'])}}
            {{Form::hidden('all_program', 0)}}
            <input type="checkbox" name="all_program" value="1" class="form-check-input" style="width: 25px; height: 25px;" id="programCheck">
            <label class="form-check-label" for="exampleCheck1">Check to make faculty elligble for all Programs</label>

        </div>  



    </div>

    <div class="col-3"> 



    </div>

<hr class= "w-75 ml-0"/>


</div>  

<div class = "form-group mr-0">        
    {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
</div> 



{!! Form::close() !!}

<script>

window.onbeforeunload = function(event)
{
    return '';
};

let programCheck = document.getElementById('programCheck');
let programSelect = document.getElementById('programSelect');

programCheck.addEventListener('change', () => {

    if(programCheck.checked == true){
        programSelect.disabled = true;
        programSelect.required = false;
    }else{
        programSelect.disabled = false;
        programSelect.required = true;
    }

});

document.getElementById("facultyForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>