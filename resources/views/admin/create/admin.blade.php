{!! Form::open(['url' => 'admin/create/admin','id' => 'adminForm']) !!}
    

    <div class="row">    

        <div class="col-9">
            
            <div class = "form-group">        
                {{Form::label('Full Name', 'Full Name', ['class' => 'mt'])}}
                {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Full Name here..'])}}
            </div> 

            <div class = "form-group">        
                {{Form::label('email', 'Email Address', ['class' => 'mt'])}}
                {{Form::email('email', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Email here..'])}}
            </div> 
            
            {{Form::label('position', 'Position',  ['class' => 'mt-2'])}}
            <div class="form-group">
                
                {{Form::select('position', ['registrar' => 'Registrar', 'accounting' =>  'Accounting', 'superadmin' => 'Site Administrator'], null, ['class' => 'custom-select w-50 mt-2 ml-2', 'id' => 'selectLevel'])}}                   
            </div>

            <div class = "form-group">        
                {{Form::label('theContact', 'Contact Number', ['class' => 'mt'])}}
                {{Form::text('contact', '', ['maxlength' => '11','class' => 'form-control', 'placeholder' => 'Contact Number here..'])}}
            </div> 

            <div class = "form-group">        
                {{Form::label('address', 'Address', ['class' => 'mt'])}}
                {{Form::text('address', '', ['class' => 'form-control', 'placeholder' => 'Address here..'])}}
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

document.getElementById("adminForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>