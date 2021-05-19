{!! Form::open(['url' => 'admin/create/student', 'files' => true]) !!}
    <div class="custom-control custom-switch">
        <input name="new_stud_switch" type="checkbox" class="custom-control-input" id="newStudSwitch">
        <label class="custom-control-label" for="newStudSwitch"><strong>New Student</strong></label>
    </div>

    <div id="new-stud-div" class="form-group" style="display:block;">         
        {{Form::label('studentId', 'Student ID', ['class' => 'mt-3'])}}
        {{Form::text('student_id', '', ['maxlength' => '8', 'class' => 'form-control w-25 ', 'id' => 'studentID', 'required' => 'required', 'placeholder' => 'ex. C18-2159'])}}
    </div> 



 <hr class= "w-75 ml-0"/>



{{-- row0 --}}
    <div class="row">    

        <div class="col-5"> 

                <div class="form-inline">
                    {{Form::label('department', 'Department')}}
                    {{Form::select('department', ['0' => 'Senior High School',
                                            '1' => 'College'
                                            ], 0,
                                        ['class' => 'custom-select w-75 ml-2', 'id' => 'selectDept'])}}                   
                </div>
                
                <div class="form-inline">
                    {{Form::label('level', 'Student Level',  ['class' => 'mt-2'])}}
                    {{Form::select('level', [], null,
                                        ['class' => 'custom-select w-50 mt-2 ml-2', 'id' => 'selectLevel'])}}                   
                </div>

        </div>

        <div class="col-7"> 


            <div class="form-inline">
                  {{Form::label('program', 'Program')}}
                  {{Form::select('program_id', [], null, ['class' => 'custom-select w-75 ml-2' , 'id' => 'selectProg'])}}                   
            </div>

            <div class="form-inline">
                  {{Form::label('semester', 'Semester',  ['class' => 'mt-2'])}}
                  {{Form::select('semester', ['1' => 'First Semester',
                                              '2' => 'Second Semester'], null,
                                              ['class' => 'custom-select w-50 mt-2 ml-2', 'id' => 'selectSemester'])}}                   
            </div>

        </div>

    </div>  
    
    

    <hr class= "w-75 ml-0"/>



    <div class="row "> 

        <div class="col">  

            <div class = "form-group">        
                {{Form::label('email', 'Email Address', ['class' => 'mt'])}}
                {{Form::email('email', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Email here..'])}}
            </div> 

        </div>

        <div class="col">  

            <div class = "form-group">        
                {{Form::label('theContact', 'Contact Number', ['class' => 'mt'])}}
                {{Form::number('contact', '', ['maxlength' => '11','class' => 'form-control', 'placeholder' => 'Contact Number here..'])}}
            </div> 

        </div>

    </div>


    <hr class= "w-75 ml-0"/>

    
    <div class="row">    

        <div class="col">  

            <div class = "form-group">        
                {{Form::label('lastName', 'Last Name', ['class' => 'mt'])}}
                {{Form::text('last_name', '', ['class' => 'form-control', 'placeholder' => 'Last Name here..'])}}
            </div> 

            <div class = "form-group">        
                {{Form::label('middleName', 'Middle Name')}}
                {{Form::text('middle_name', '', ['class' => 'form-control', 'placeholder' => 'Middle Name here..'])}}
            </div> 
                   
        </div>

        <div class="col"> 

            <div class = "form-group">        
                {{Form::label('firstName', 'First Name')}}
                {{Form::text('first_name', '', ['class' => 'form-control', 'placeholder' => 'First Name here..'])}}
            </div>  

            {{Form::label('dob', 'Date of Birth')}}
            <div class = "form-group">                        
                {{Form::date('dob', \Carbon\Carbon::now()->subYears(15), ['class' => 'ml-2', 'id' => 'dob'] )}}
            </div> 

        </div>

    </div>
    

    <hr class= "w-75 ml-0"/>


    <div class="row">    

         <div class="col">  

            <div class = "form-group">        
                {{Form::label('permanent_address', 'Permanent Address')}}
                {{Form::text('permanent_address', '', ['class' => 'form-control', 'placeholder' => 'Permanent Address'])}}
            </div>

            <div class = "form-group">        
                {{Form::label('present_address', 'Present Address')}}
                {{Form::text('present_address', '', ['class' => 'form-control', 'placeholder' => 'Present Address'])}}
            </div> 
            
        </div>
    </div>
      

    <hr class= "w-75 ml-0"/> 


    <div class="row" >

        <div class="col">

            Transferee<strong>?</strong>
            <div class="form-check form-check-inline ml-4">  
                {{Form::label('transferee', 'No')}}                 
                {{ Form::radio('transferee', '0', true, ['class' => 'mb-2 ml-2'] )}}
                {{Form::label('transferee', 'Yes', ['class' => 'mb-2 ml-2'])}}                 
                {{ Form::radio('transferee', '1', false, ['class' => 'mb-2 ml-2'])}}
            </div>  

        </div>  

        <div class="col">

            Student Type<strong>?</strong>
            <div class="form-check form-check-inline ml-4">  
                {{Form::label('cur_status', 'Regular')}}                 
                {{ Form::radio('cur_status', '0', true, ['class' => 'mb-2 ml-2'] )}}
                {{Form::label('cur_status', 'Irregular', ['class' => 'mb-2 ml-2'])}}                 
                {{ Form::radio('cur_status', '1', false, ['class' => 'mb-2 ml-2'])}}
            </div>  

        </div> 

    </div>  

    
    <div class="table-responsive">

        <table class="table table-striped h-50" id="subjects-table">
            <thead class="bg-warning ">
                <tr>
                    <th scope="col">Action</th>
                    <th scope="col" >Rating</th>
                    <th scope="col">Code</th>
                    <th scope="col" class="w-50">Description</th>
                    <th scope="col">Program</th>
                    <th scope="col">Units</th>
                    <th scope="col">Pre-Req</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td><a class="btn btn-danger">Remove</a></td>
                    <td><input type="number" min="1" max="5" class="w-75" step="0.25"></td>
                    <th scope="row">HIS112</th>
                    <td>Readings in Philippine History 1</td>
                    <td>General</td>
                    <td>3</td>
                    <td>HIS111</td>
                </tr>              

            </tbody>
        </table>

    </div>


    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}
   <hr class= "w-75 ml-0"/>

    <div class = "form-group">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
    </div> 

    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}


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
    let selectLevel = document.querySelector('#selectLevel');   
    let selectSemester = document.querySelector('#selectSemester');   
    // let seniorHighDiv = document.querySelector('#seniorHighDiv');   


window.addEventListener('load', (event) => {
        changeSelect();       

    //     let dept = selectDept.value;

    //     if(dept == 0){
    //         selectLevel.options[0] = new Option('Grade 11', 'grade_11');     
    //         selectLevel.options[1] = new Option('Grade 12', 'grade_12'); 
    //         // seniorHighDiv.className = "row d-none";
    //     } else {
    //         selectLevel.options[0] = new Option('First Year', 'first_year');     
    //         selectLevel.options[1] = new Option('Second Year', 'second_year');     
    //         // seniorHighDiv.className = "row";
    //     }

    //     var xhr = new XMLHttpRequest();

    //     xhr.open('GET', 'http://smartii-app.test/admin/view/programs/department/' + dept, true);

    //     xhr.onload = function() {
    //         if (this.status == 200) {
    //             var programs = JSON.parse(this.responseText);

    //             for (let i in programs) {
    //                 selectProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id);
    //             }
    //         } 
    // }

    // xhr.send();
}); 

selectDept.addEventListener('change', () => {                    
    changeSelect();
    
    
});

selectProg.addEventListener('change', () => {                        
    changeTable();
    

});
selectLevel.addEventListener('change', () => {                        
    changeTable();
    

});

selectSemester.addEventListener('change', () => {                        
    changeTable();
    

});

function changeSelect(){
    
    let dept = selectDept.value;
    var xhr = new XMLHttpRequest();

    if(dept == 0){
        selectLevel.options[0] = new Option('Grade 11', '1');     
        selectLevel.options[1] = new Option('Grade 12', '2');     
        
    } else {
        selectLevel.options[0] = new Option('First Year', '11');     
        selectLevel.options[1] = new Option('Second Year', '12');     
        
    } 

    
    xhr.open('GET', 'http://smartii-app.test/admin/view/programs/department/' + dept, true);

    xhr.onload = function() {
        if (this.status == 200) {
            var programs = JSON.parse(this.responseText);                                

                for (let i in programs) {                                        
                    selectProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id);                     
                }

            } else {
            
            }
            changeTable();

        }

        xhr.send(); 
        
}

// table chnge per select
function changeTable(){
    
    let dept = selectDept.value;
    let program = selectProg.value;
    let level = selectLevel.value;
    let semester = selectSemester.value;

    console.log(dept);
    console.log(program);
    console.log(level);
    console.log(semester);

    var xhr = new XMLHttpRequest();   
    xhr.open('GET', 'http://smartii-app.test/admin/view/subjects'
                    +'/department/' + dept 
                    + '/program/' + program 
                    + '/level/' + level 
                    + '/semester/' + semester, true);

    

    xhr.onload = function() {
    if (this.status == 200) {
        let results = JSON.parse(this.responseText);

        // console.log(results['subjects']);
        // console.log(results['pre_reqs']);
        // for(i in results['pre_reqs']){
        //     console.log(results['pre_reqs'][i]);
        // }

        let output = `<table class="table table-striped h-50" id="subjects-table">`;
            output+= `<thead class="bg-warning ">`;
            output+=`<tr>`;
            output+=`<th scope="col">Action</th>`;
            output+=`<th scope="col" >Rating</th>`;
            output+=`<th scope="col">Code</th>`;
            output+=`<th scope="col" class="w-50">Description</th>`;
            output+=`<th scope="col">Program</th>`;
            output+=`<th scope="col">Units</th>`;
            output+=`<th scope="col">Pre-Req</th>`;
            output+=`</tr>`;
            output+=`</thead>`;
            output+=`<tbody>`;
            for (let i=0; i<results['subjects'].length; i++) { 
                // console.log(results['subjects'][i]);        
                output+=`<tr>`;
                output+=`<td><a class="btn btn-danger">Remove</a></td>`;
                output+=`<td><input type="number" min="1" max="5" class="w-75" step="0.25"></td>`;
                output+=`<th scope="row">` + results['subjects'][i].code + `</th>`;
                output+=`<td>` + results['subjects'][i].desc + `</td>`;
                output+=`<td>`;
                    if(results['programs'][i] == null){
                        output+= 'General Subject';
                    } else {
                        output+=results['programs'][i].desc + `</td>`;
                    }
                
                     
                output+=`<td>` + results['subjects'][i].units + `</td>`;
                output+=`<td>`;
                    // console.log(results['pre_reqs'][i]);
                    if(!results['pre_reqs'][i]){

                        output+=` `;

                    } else {
                        
                        for(let j in results['pre_reqs'][i]){

                            output+= results['pre_reqs'][i][j].code + ', ';

                        }

                    }
                output+=`</td>`;
                output+=`</tr>`;
            }                            
            output+=`</tbody>`;
            output+=`</table>`;

                                        
            document.getElementById('subjects-table').innerHTML = output;
            

        } else {
        
        }

    }

        xhr.send(); 


}


</script>







       {{-- <div class = "form-group">        
            {{Form::label('gender', 'Gender')}}
            {{Form::select('gender', ['male' => 'Male', 
                                    'female' => 'Female',
                                    'lesbian' => 'Lesbian',
                                    'gay' => 'Gay',
                                    'bisexual' => 'Bisexual',
                                    'male-transgender' => 'Transgender(Male)',
                                    'female-transgender' => 'Transgender(Female)',
                                    'queer' => 'Queer'                                        
                                    ], null,
                                    ['class' => 'custom-select w-25 ml-2'])}}
            </div>  --}}
        

         
           

        {{-- <div class = "form-inline">        
            {{Form::label('nationality', 'Nationality')}}
            {{Form::text('nationality', '', ['class' => 'form-control w-50 ml-2', 'placeholder' => 'Nationality'])}}
        </div>   --}}

  



{{-- row3 --}}


       


        {{-- <div class = "form-group">        
            {{Form::label('civil_status', 'Civil Status')}}
            {{Form::select('civil_status', 
                                ['single' => 'Single',
                                    'married' => 'Married', 
                                    'widowed' => 'Widowed',
                                    'separated' => 'Separated',
                                    'divorced' => 'Divorced'                                        
                                    ], null,
                                    ['class' => 'custom-select w-50 ml-2'])}}
        </div> --}}
  

     {{-- <div class="col"> --}}

        {{-- <div class = "form-inline">        
            {{Form::label('religion', 'Religion')}}
            {{Form::text('religion', '', ['class' => 'form-control w-50 ml-2', 'placeholder' => 'Religion'])}}
        </div>     --}}

     {{-- </div> --}}

  

{{-- <hr class= "w-75 ml-0"/>

<div class="row">

     <div class="col">
       <div class = "form-group">        
            {{Form::label('father_name', 'Father\'s Name')}}
            {{Form::text('father_name', '', ['class' => 'form-control w-75', 'placeholder' => 'Father\'s Name here..'])}}
        </div>

        <div class = "form-group">        
            {{Form::label('father_contact', 'Father\'s Contact Number')}}                
            {{Form::number('father_contact', '', ['maxlength' => '11','class' => 'form-control w-50', 'placeholder' => 'Father\'s Contact here..'])}}
        </div> 

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('father_occupation', 'Father\'s Occupation')}}
            {{Form::text('father_occupation', '', ['class' => 'form-control w-75', 'placeholder' => 'Father\'s Occupation here..'])}}
        </div> 
     </div>
              
</div>  

<hr class= "w-75 ml-0"/>

<div class="row">

     <div class="col">
       <div class = "form-group">        
            {{Form::label('mother_name', 'Mother\'s Name')}}
            {{Form::text('mother_name', '', ['class' => 'form-control w-75', 'placeholder' => 'Mother\'s Name here..'])}}
        </div>

        <div class = "form-group">        
            {{Form::label('mother_contact', 'Mother\'s Contact Number')}}                
            {{Form::number('mother_contact', '', ['maxlength' => '11','class' => 'form-control w-50', 'placeholder' => 'Mother\'s Contact here..'])}}
        </div> 

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('mother_occupation', 'Mother\'s Occupation')}}
            {{Form::text('mother_occupation', '', ['class' => 'form-control w-75', 'placeholder' => 'Mother\'s Occupation here..'])}}
        </div> 
     </div>
              
</div>  

<hr class= "w-75 ml-0"/>

<div class="row">

     <div class="col">
       <div class = "form-group">        
            {{Form::label('guardian_name', 'Guardian\'s Name')}}
            {{Form::text('guardian_name', '', ['class' => 'form-control w-75', 'placeholder' => 'Guardian\'s Name here..'])}}
        </div>

        <div class = "form-group">        
            {{Form::label('guardian_contact', 'Guardian\'s Contact Number')}}                
             {{Form::number('guardian_contact', '', ['maxlength' => '11','class' => 'form-control  w-50', 'placeholder' => 'Guardian\'s Contact here..'])}}                
        </div> 

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('guardian_occupation', 'Guardian\'s Occupation')}}
            {{Form::text('guardian_occupation', '', ['class' => 'form-control w-75', 'placeholder' => 'Guardian\'s Occupation here..'])}}
        </div> 
     </div>
              
</div>   --}}

{{-- <hr class= "w-75 ml-0"/>

<div class="row">

     <div class="col">
       <div class = "form-group">        
            {{Form::label('emergency_person_name', 'Name of Person to Contact in case of Emergency')}}
            {{Form::text('emergency_person_name', '', [ 'class' => 'form-control w-75', 'placeholder' => 'Name here..'])}}
        </div>

        <div class = "form-group">        
            {{Form::label('emergency_person_contact', 'Contact of Person to Contact in case of Emergency')}}
            {{Form::text('emergency_person_contact', '', [ 'maxlength' => '11', 'class' => 'form-control w-75', 'placeholder' => 'Contact here..'])}}
        </div> 

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('emergency_person_address', 'Address of Person to Contact in case of Emergency')}}
            {{Form::text('emergency_person_address', '', ['class' => 'form-control w-75', 'placeholder' => 'Address here..'])}}
        </div> 
     </div>
              
</div>   

<hr class= "w-75 ml-0"/> 

<div class="row">

     <div class="col">
       <div class = "form-group">   
            <div class = "form-group">        
                {{Form::label('elementary', 'Elementary School')}}
                {{Form::text('elementary', '', ['class' => 'form-control w-75', 'placeholder' => 'Elementary School'])}}
            </div>      
             
        </div>        

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('elementary_year', 'Elementary Year Graduated')}}                
            {{Form::number('elementary_year', '', ['min' => '1990', 'max' => date("Y"),'class' => 'form-control  w-50', 'placeholder' => 'Elementary Year'])}}                
            </div> 
     </div>
              
</div>   

<hr class= "w-75 ml-0"/> 

 <div class="row">

     <div class="col">
       <div class = "form-group">   
            <div class = "form-group">        
                {{Form::label('junior_high', 'Junior High School')}}
                {{Form::text('junior_high', '', ['class' => 'form-control w-75', 'placeholder' => 'Junior High School'])}}
            </div>      
             
        </div>        

     </div>

     <div class="col">
        <div class = "form-group">        
            {{Form::label('junior_high_year', 'Junior High Year Graduated')}}                
            {{Form::number('junior_high_year', '', ['min' => '1990', 'max' => date("Y"),'class' => 'form-control  w-50', 'placeholder' => 'Junior High Year'])}}                
            </div> 
     </div>
              
</div>  

<hr class= "w-75 ml-0"/> 

 <div class="row" id="seniorHighDiv">

     <div class="col">
       <div class = "form-group">   
            <div class = "form-group">        
                {{Form::label('senior_high', 'Senior High School')}}
                {{Form::text('senior_high', '', ['class' => 'form-control w-75', 'placeholder' => 'Senior High School'])}}
            </div>      
             
        </div>        

     </div>

     <div class="col" >
        <div class = "form-group">        
            {{Form::label('senior_high_year', 'Senior High Year Graduated')}}                
            {{Form::number('senior_high_year', '', ['min' => '1990', 'max' => date("Y"),'class' => 'form-control  w-50', 'placeholder' => 'Senior High Year'])}}                
            </div> 
     </div>
              
</div>  

<hr class= "w-75 ml-0"/> 

<div class="row" >

     <div class="col">
       <div class = "form-group">   
            <div class = "form-group">        
                {{Form::label('last_school', 'Last School Attended')}}
                {{Form::text('last_school', '', ['class' => 'form-control w-75', 'placeholder' => 'Last School Attended'])}}
            </div>                 
        </div>        

     </div>

     <div class="col" >
        <div class = "form-group">        
            {{Form::label('last_school_year', 'Last School Attended Year Graduated')}}                
            {{Form::number('last_school_year', '', ['min' => '1990', 'max' => date("Y"),'class' => 'form-control  w-50', 'placeholder' => 'Last School Year Attended Year'])}}                
            </div> 
     </div>      
              
</div>   --}}