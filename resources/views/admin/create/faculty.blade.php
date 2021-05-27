{!! Form::open(['url' => 'admin/create/faculty', 'files' => true, 'id' => 'facultyForm']) !!}

  
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

document.getElementById("facultyForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>