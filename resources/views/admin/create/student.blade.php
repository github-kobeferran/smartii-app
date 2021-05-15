{!! Form::open(['url' => 'admin/create/student', 'files' => true]) !!}
    <div class="custom-control custom-switch">
        <input name="new_stud" type="checkbox" class="custom-control-input" id="newStudSwitch">
        <label class="custom-control-label" for="newStudSwitch"><strong>Change to New Student Form</strong></label>
    </div>

    <div id="new-stud-div" class="form-group" style="display:block;">         
        {{Form::label('studentId', 'Student ID', ['class' => 'mt-3'])}}
        {{Form::text('student_id', '', ['class' => 'form-control w-25 ', 'id' => 'studentID', 'required' => 'required', 'placeholder' => 'Enter Student ID'])}}
    </div> 
 <hr class= "w-50 ml-0"/>
{{-- row0 --}}
    <div class="row">    
         <div class="col-5">  {{-- left --}}
            <div class="form-inline">
                  {{Form::label('dept', 'Department')}}
                  {{Form::select('dept', [
                                          '0' => 'Senior High School',
                                          '1' => 'College'
                                          ], null,
                                    ['class' => 'custom-select w-75 ml-2', 'id' => 'selectDept'])}}                   
            </div>
        </div>
         <div class="col-7"> {{-- right --}}
           <div class="form-inline">
                  {{Form::label('program', 'Program')}}
                  {{Form::select('program_id', [], null, ['class' => 'custom-select w-75 ml-2' , 'id' => 'selectProg'])}}                   
            </div>

        </div>
    </div>    

    <hr class= "w-50 ml-0"/>
{{-- row1 --}}
    <div class="row">    
         <div class="col">  {{-- left --}}

            <div class = "form-group">        
                {{Form::label('lastName', 'Last Name', ['class' => 'mt'])}}
                {{Form::text('last_name', '', ['class' => 'form-control text-bold', 'placeholder' => 'Last Name here..'])}}
            </div> 


            <div class = "form-group">        
                {{Form::label('middleName', 'Middle Name')}}
                {{Form::text('middle_name', '', ['class' => 'form-control', 'placeholder' => 'Middle Name here..'])}}
            </div> 
                   
        </div>
         <div class="col"> {{-- right --}}
            <div class = "form-group">        
                {{Form::label('firstName', 'First Name')}}
                {{Form::text('first_name', '', ['class' => 'form-control', 'placeholder' => 'First Name here..'])}}
            </div>  

        </div>
    </div>
    
    <hr class= "w-50 ml-0"/>
{{-- row2 --}}
    <div class="row">    
         <div class="col">  {{-- left --}}

            <div class = "form-group">        
                {{Form::label('dob', 'Date of Birth')}}
                {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), ['class' => ' datepicker ml-2'] )}}
            </div> 

            <div class = "form-group">        
                {{Form::label('sex', 'Sex')}}
                {{Form::select('sex', ['m' => 'Male', 
                                        'f' => 'Female'], null,
                                       ['class' => 'custom-select ml-2 w-25'])}}
            </div>         
                
            
        </div>

         <div class="col"> {{-- right --}}
            <div class = "form-group">        
            {{Form::label('gender', 'Gender')}}
            {{Form::select('gender', ['m' => 'Male', 
                                    'f' => 'Female',
                                    'l' => 'Lesbian',
                                    'g' => 'Gay',
                                    'b' => 'Bisexual',
                                    't' => 'Transgender',
                                    'q' => 'Queer'                                        
                                    ], null,
                                    ['class' => 'custom-select w-25 ml-2'])}}
            </div> 

            <div class = "form-inline">        
                {{Form::label('nationality', 'Nationality')}}
                {{Form::text('nationality', '', ['class' => 'form-control w-50 ml-2', 'placeholder' => 'Nationality'])}}
            </div>    

        </div>

    </div>
    



    {{-- submit --}}
   <hr class= "w-50 ml-0"/>

    <div class = "form-group">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
    </div> 


{!! Form::close() !!}

<script>
    document.querySelector('#newStudSwitch').addEventListener('click', () => {
        idDiv = document.getElementById('new-stud-div');
        if(idDiv.style.display == 'none') {
            idDiv.style.display = 'block';
            document.querySelector('#studentID').required = true;
            document.querySelector('#newStudSwitch').textcontent = "Change to Existing Student Form";
        } else {
            idDiv.style.display =  'none';
            document.querySelector('#studentID').required = false;
            document.querySelector('#studentID').value = "";
        }
        
    });

    let selectDept = document.querySelector('#selectDept');
    let selectProg = document.querySelector('#selectProg');   


    window.addEventListener('load', (event) => {

    let dept = selectDept.value;
    var xhr = new XMLHttpRequest();

    xhr.open('GET', 'http://smartii-app.test/admin/view/programs/department/' + dept, true);

    xhr.onload = function() {
        if (this.status == 200) {
            var programs = JSON.parse(this.responseText);

            for (let i in programs) {
               selectProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id);
            }
        } 
    }

    xhr.send();
}); 

    selectDept.addEventListener('change', () => {                    
        let dept = selectDept.value;
        var xhr = new XMLHttpRequest();
        
        if(dept == ''){
           console.log(selectProg.options.length);
            for(let i=0; i<selectProg.options.length; i++){
                selectProg.remove(i);
            }
            selectProg.options[0] = new Option('Select Program', '');

        }else {
            xhr.open('GET', 'http://smartii-app.test/admin/view/programs/department/' + dept, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    var programs = JSON.parse(this.responseText);
                    

                        for (let i in programs) {                
                            selectProg.options[i].text = programs[i].abbrv + ' - ' + programs[i].desc;
                            selectProg.options[i].value = programs[i].id;
                        }
                    } else {
                    
                    }

                }
        
             xhr.send(); 
        }

        
              
    });
       
</script>