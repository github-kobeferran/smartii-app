{!! Form::open(['url' => 'admin/create/subject', 'files' => true, 'id' => 'subjectForm']) !!}    

    <div class="row">    

        <div class="col">                            
            
                <div class="form-group">
                    {{Form::label('code', 'Subject Code', ['class' => 'mt'])}}
                    {{Form::text('code', '', ['class' => 'form-control w-25', 'placeholder' => 'Subject Code'])}}                 
                </div>
                
                <div class = "form-group">        
                    {{Form::label('desc', 'Subject Description', ['class' => 'mt'])}}
                    {{Form::text('desc', '', ['class' => 'form-control', 'placeholder' => 'Subject Description'])}}
                </div> 

        </div>

        <div class="col">                

        </div>

    </div>
    
    <hr class= "w-75 ml-0"/>
    

    <div class="row">    
        
        <div class="col"> 

            {{Form::label('department', 'Subject is for:')}}
            <div class="form-group">                
                {{Form::select('dept', 
                  ['0' => 'Senior High School Students',                              
                  '1' => 'College Students'], 0,
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectDept'])}}                   
            </div>                      

            <div class="form-group">                
                {{Form::select('level', 
                  [], null,
                  ['class' => 'custom-select w-25 ml-2', 'id' => 'selectLevel'])}}                   
            </div>                      

            {{Form::label('prog', 'Choose Dedicated Program:')}}
            <div class="form-group">                
                {{Form::select('prog', 
                  [], null,
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectProg'])}}                   
            </div>                      
            
            {{Form::label('sem', 'To be taken in:')}}
            <div class="form-group">                
                {{Form::select('prog', 
                  ['1' => 'First Semester (1st)',
                   '2' => 'Second Semester (2nd)'], null,
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectSemester'])}}                   
            </div>  
            
            <div class="form-group">
                {{Form::label('units', 'No. of Units', ['class' => 'mt'])}}
                {{Form::text('units', '', ['class' => 'form-control w-25', 'placeholder' => 'Units'])}}                 
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input name="pre_req" type="checkbox" class="custom-control-input" id="preReqSwitch">
                    <label class="custom-control-label" for="preReqSwitch"><strong>Add Pre-Requisites</strong></label>
                </div>            
            </div>

            <div class="form-group" id="addPreReq" style="display: none;">
                <input name="subjects[]"  list="subjectsList" class="form-control " id="subjects">
                <datalist id="subjectsList">                    
                </datalist>         
            </div>

        </div>


        <div class="col">                

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

document.getElementById("subjectForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

document.querySelector('#preReqSwitch').addEventListener('click', () => {        
    togglePreReq();
});


function togglePreReq(){
let idDiv = document.getElementById('addPreReq');
    if(idDiv.style.display == 'none') {
        idDiv.style.display = 'block';
        // document.querySelector('#studentID').required = true;
        // document.querySelector('#newStudSwitch').textContent = "Change to Existing Student Form";
    } else {
        idDiv.style.display =  'none';
        // document.querySelector('#studentID').required = false;
        // document.querySelector('#studentID').value = "";
    }
}

</script>
