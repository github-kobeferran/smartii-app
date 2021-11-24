{!! Form::open(['url' => 'admin/create/faculty', 'id' => 'facultyForm']) !!}

  
<div class="row">        

    <div class="col-lg">

        <div class="row">
            <div class="col-lg text-center">
                <h4 class="formal-font smartii-text-dark">Faculty Registration Form</h4>
            </div>
        </div>

        <div class="row mb-1">
            <div class="col-lg-4">
                <b>{{Form::label('Last Name', 'Last Name', ['class' => 'mt'])}}</b>
                {{-- {{Form::text('last_name', '', ['class' => 'form-control material-input', 'placeholder' => 'Last Name here..'])}} --}}
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" class="form-control material-input @error('last_name') is-invalid @enderror" placeholder="Last Name here.." required>
            </div>
            <div class="col-lg-4">
                <b>{{Form::label('First Name', 'First Name', ['class' => 'mt'])}}</b>
                {{-- {{Form::text('first_name', '', ['class' => 'form-control material-input', 'placeholder' => 'First Name here..'])}} --}}
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" class="form-control material-input @error('first_name') is-invalid @enderror" placeholder="First Name here.." required>
            </div>
            <div class="col-lg-4">
                <b>{{Form::label('Middle Name', 'Middle Name', ['class' => 'mt'])}}</b>
                {{-- {{Form::text('middle_name', '', ['class' => 'form-control material-input', 'placeholder' => 'Middle Name here..'])}} --}}
                <input id="middle_name" name="middle_name" type="text" value="{{ old('middle_name') }}" class="form-control material-input @error('middle_name') is-invalid @enderror" placeholder="Middle Name here..">
            </div>
        </div>
        
        <div class="row mb-1">
            <div class="col-lg-8">
                <b>{{Form::label('email', 'Email Address', ['class' => 'mt'])}}</b>
            {{-- {{Form::email('email', '', ['class' => 'form-control material-input', 'required' => 'required', 'placeholder' => 'Email here..'])}}     --}}
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control material-input @error('email') is-invalid @enderror" placeholder="Faculty Member's Email here.." required>
            </div>
            <div class="col-lg-4">
                <b>{{Form::label('dob', 'Date of Birth')}}</b>
                {{-- {{Form::date('dob', \Carbon\Carbon::now()->subYears(18), ['class' => 'ml-2 form-control w-50 material-input', 'id' => 'dob'] )}} --}}
                <input  id="dob" name="dob" value="{{\Carbon\Carbon::now()->subYears(18)->toDateString()}}" min="1903-01-01" max="{{\Carbon\Carbon::now()->subYears(18)->toDateString()}}" type="date" class="form-control material-input @error('dob') is-invalid @enderror" placeholder="" required>            
            </div>
        </div>

        <div class="row mb-1">
            <?php
                $programs = \App\Models\Program::orderBy('id', 'asc')->pluck('abbrv', 'id');            
            ?>
            <div class="col-lg-4">
                <b><label for="">Specialty (Select Program)</label></b>
                {{Form::select('program_id', $programs, old('first_name') , ['required' => 'required', 'id' => 'programSelect', 'placeholder' => 'Select Faculty Specialty', 'class' => 'form-control material-input mr-2'])}}
                {{Form::hidden('all_program', 0)}}
            </div>
            <div class="col-lg-4 pt-3 mt-3 ml-4">
                <input type="checkbox" name="all_program" value="1"  class="form-check-input" style="width: 25px; height: 25px;" id="programCheck">
                <b><label class="form-check-label ml-2 mt-2" for="exampleCheck1">Check to make faculty elligble for all Programs</label></b>
            </div>
            
        </div>

        <div class="row">
            <div class="col-lg text-center">
                {{Form::submit('REGISTER FACULTY',  ['class' => 'material-btn btn btn-block btn-success w-75 mx-auto mt-3'])}}
            </div>
        </div>

    </div>    

</div>  

<div class = "form-group mr-0">        
    
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