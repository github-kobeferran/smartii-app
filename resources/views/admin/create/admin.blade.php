{!! Form::open(['url' => 'admin/create/admin','id' => 'adminForm']) !!}
    

    <div class="row">    
        <div class="col-lg">

            <div class="row">
                <div class="col-lg text-center">
                    <h3 class="raleway-font">ADMIN REGISTRATION FORM</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <b>{{Form::label('Full Name', 'Full Name', ['class' => ''])}}</b>
                    {{-- {{Form::text('name', '', ['class' => 'form-control material-input', 'placeholder' => 'Full Name here..'])}} --}}
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-control material-input @error('name') is-invalid @enderror" placeholder="Admin Name here.." required>
                </div>
                <div class="col-lg-6">
                    <b>{{Form::label('email', 'Email Address', ['class' => ''])}}</b>
                    {{-- {{Form::email('email', '', ['class' => 'form-control material-input', 'required' => 'required', 'placeholder' => 'Email here..'])}} --}}
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control material-input @error('email') is-invalid @enderror" placeholder="Admin Email here.." required>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-6">
                    <b>{{Form::label('position', 'Position',  ['class' => ''])}}</b>
                    <div class="form-group">                
                        {{Form::select('position', ['registrar' => 'Registrar', 'accounting' =>  'Accounting', 'superadmin' => 'Site Administrator'], old('position'), ['class' => 'custom-select material-input', 'id' => 'selectLevel'])}}                   
                    </div>
                </div>    
                <div class="col-lg-6">
                    <b>{{Form::label('theContact', 'Contact Number', ['class' => ''])}}</b>
                    {{Form::text('contact', old('contact'), ['maxlength' => '11','class' => 'form-control material-input', 'placeholder' => 'Contact Number here..'])}}
                </div>    
            </div>                        

            <div class="row">
                <div class="col-lg">
                    <b>{{Form::label('address', 'Address', ['class' => 'mt'])}}</b>
                    {{Form::text('address', old('address'), ['class' => 'form-control material-input', 'placeholder' => 'Address here..'])}}
                </div>
            </div>

            <div class="row">
                <div class="col-lg">
                    {{Form::submit('REGISTER ADMIN',  ['class' => 'material-btn btn btn-block btn-success w-75 mx-auto mt-3'])}}
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

document.getElementById("adminForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};





</script>