
<div class="row " >

    <div class="col-sm-5">
        {!! Form::open(['url' => 'admin/create/class', 'files' => true, 'id' => 'classForm']) !!}  

        

        <hr>
    <div class = "form-group">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-50 mt-3'])}}
    </div> 

    

{!! Form::close() !!}
    
    </div>

    <div class="col-sm-7" id="second-column">        
    
    </div>


</div>

<script>


</script>