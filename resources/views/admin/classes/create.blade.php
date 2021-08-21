

{!! Form::open(['url' => 'admin/create/class', 'id' => 'classForm']) !!}

    <blockquote  class="blockquote text-center">Pending Classes, Assign Schedule and Instructor</blockquote >
        
    <div class="row no-gutters">    

        <div class="col-sm">
            
            
            <div class="form-group">                                    
                {{Form::select('dept', 
                  ['0' => 'Senior High School Students',                              
                  '1' => 'College Students'], 0,
                  ['class' => 'custom-select m-1 w-50 float-right', 'id' => 'selectDept'])}}                   
            </div>   

        </div>
        <div class="col-sm">
            
            <div class="form-group">                            
                {{Form::select('prog', 
                [], null,
                ['class' => 'custom-select m-1 w-50 float-left', 'id' => 'selectProg'])}}          
            </div>                

        </div>
    </div>

    <div class="row ">    

        <div class="col-sm">
            
            <div class="form-group text-center">        
                    
                {{Form::select('subj', 
                [], null,
                ['class' => 'custom-select w-50 m-1', 'id' => 'selectSubject'])}}    
                {{Form::hidden('programid', '', ['id' => 'programID'])}}                        
            </div>    

        </div>
       
    </div>

    <hr>

    <div class="row mt-2 ">

        <div class="col-sm mx-auto text-center">
            <h5 class="">CLASS NAME</h5>
           

            <input type="text" name="class_name" maxlength="25" class="text-center ml-2 w-25" required>


        </div>

        

    </div>

    <div class="row mt-2 ">    
        
        <div class="col-sm">


            
            <div class ="table-responsive border shadow" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table class="table table-striped table-responsive-sm border" >
                    <thead class="thead">
                        <tr>
                            <th class="text-center bg-light" scope="col">Check</th>
                            <th class="bg-light" scope="col">Student ID</th>
                            <th class="bg-light" scope="col">Name</th>
                        </tr>
                    </thead> 
                    <tbody id="subjects-table" >

                    </tbody>                        
                </table>
            </div>

        </div>         

    </div> 

    {{ Form::hidden('multi_sched', 1, ['id' => 'multi-sched']) }}

    <div class="container p-0 " id="sched-container">

        <div class="row mt-2 no-gutters" id="sched-row">

            <div class="col-sm">                
                                
                <p class="text-center"><strong>Day</strong></p>
    
                <div class="form-group text-center">        
                        
                    {{Form::select('day', 
                    ['mon' => 'Monday',
                     'tue'=> 'Tuesday',
                     'wed'=> 'Wednesday',
                     'thu'=> 'Thursday',
                     'fri'=> 'Friday',
                     'sat'=> 'Saturday',                
                    ], null,
                    ['name' => 'day', 'placeholder' => 'Pick a Day', 'class' => 'custom-select bg-light text-dark border-secondary', 'id' => 'selectDay'])}}            
    
                </div>    
    
            </div>
    
            <div class="col-sm">
                
                <div class="row no-gutters">            
    
                    <div class="col-md">                                                                
                        
                        <p><strong >From:   </strong></p>
    
                        <input type="time" id="from_time" name="from" value="07:00"
                        min="07:00" max="19:00" class="form-control bg-light text-dark border-secondary" required>          
                        
    
                    </div>
    
                    <div class="col-sm">
                        <p><strong >Until:   </strong></p>
                        <input type="time" id="until_time"  name="until" value="08:00"
                        min="08:00" max="21:00" class="form-control bg-light text-dark border-secondary" required>          
    
                    </div>
    
                </div>
    
            </div>        
            
            <div class="col-sm">
    
                <p class="text-center"><strong>Room</strong></p>
                
                <div class="form-group text-center">        
                        
                    {{Form::select('room', 
                    [], null,
                    ['placeholder' => 'Pick a Room', 'name' => 'room_id', 'class' => 'custom-select bg-light text-dark border-secondary ', 'id' => 'selectRoom'])}}            
                </div>    
    
            </div>
    
            <div class="col-sm">
    
                <p class="text-center"><strong>Instructor</strong></p>
                
                <div class="form-group text-center">        
                        
                    {{Form::select('level', 
                    [], null,
                    ['name' => 'instructor_id', 'class' => 'custom-select bg-light text-dark border-secondary ', 'id' => 'selectFaculty'])}}            
                </div>    
    
            </div>
           
        </div>        

    </div>
    

    <div class = "form-group ">
        <button onclick="anotherSched()" type="button" class="btn btn-light border-success"><i class="fa fa-plus" aria-hidden="true"></i> Add another schedule</button>
        <button id="minusButton" onclick="deleteSched()" type="button" class="btn btn-light border-danger d-none"><i class="fa fa-minus-circle" aria-hidden="true"></i> Remove the last schedule</button>
    </div>    

    <hr class= ""/>
    

    <div class = "form-group mr-0 text-center">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-50 mt-3'])}}
    </div> 

    <?php $sectionLimit = App\Models\Setting::first()->class_quantity; ?>

{!! Form::close() !!}

<script>

var sectionLimit = {!! json_encode($sectionLimit) !!}

let selectDept = document.getElementById('selectDept');
let selectProg = document.getElementById('selectProg');
let selectSubject = document.getElementById('selectSubject');
let selectRoom = document.getElementById('selectRoom');
let schedContainer = document.getElementById('sched-container');
let selectFaculty = document.getElementById('selectFaculty');
let from_time = document.getElementById('from_time');
let until_time = document.getElementById('until_time');
let selectDay = document.getElementById('selectDay');

let counter = 1;

selectDept.addEventListener('change', () => {
    changeClassesSelects();
    // changeProgramID(selectSubject.value);
});
selectProg.addEventListener('change', () => {
    changeClassesSelects(true);
    // changeProgramID(selectSubject.value);
});
selectSubject.addEventListener('change', () => {
    classesTableData();
    availableFaculty();
    availableRooms();   
    changeProgramID(selectSubject.value);
});

selectDay.addEventListener('change', () => {   
    availableFaculty();
    availableRooms();    
    changeProgramID(selectSubject.value);
});

from_time.addEventListener('input', () => {
    availableFaculty();
    availableRooms();  
    changeProgramID(selectSubject.value);  

});
until_time.addEventListener('input', () => {
    availableFaculty();
    availableRooms();    
    changeProgramID(selectSubject.value);
});



function anotherSched(){
  
    let schedRow = document.getElementById('sched-row');
    let minusButton = document.getElementById('minusButton');

    schedContainerChildCount = schedContainer.childElementCount;

    let newSchedRow = schedRow.cloneNode([true]);

    for(let i = 0; i<newSchedRow.childNodes.length; i++){
        
        if(newSchedRow.childNodes[i].tagName  == "DIV"){

            for(let j=0; j<newSchedRow.childNodes[i].childNodes.length; j++){                            

                if(newSchedRow.childNodes[i].childNodes[j].tagName  == "DIV"){

                    for(let k=0; k<newSchedRow.childNodes[i].childNodes[j].childNodes.length; k++){                        

                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].tagName  == "SELECT" ) {
                                                                            
                           newSchedRow.childNodes[i].childNodes[j].childNodes[k].name += '_' + schedContainerChildCount;
                        
                        }

                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].tagName  == "DIV"){                          

                            for(let l=0; l<newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes.length; l++){

                                if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].tagName == "INPUT")

                                    newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name += '_' + schedContainerChildCount;
                                    
                            }

                        }

                    }

                }
                    
            }
        }

    }        

    if(schedContainerChildCount <= 2){
        schedContainer.appendChild(newSchedRow);
        counter++;   
        updateSchedCounter();             
    }            
    minusButton.className = "btn btn-light border-danger";        
  
    

}

function deleteSched(){

    let minusButton = document.getElementById('minusButton');
    
    if(schedContainer.childElementCount == 2){
        schedContainer.removeChild(schedContainer.lastChild);
        minusButton.className = "btn btn-light border-danger d-none";                 
    }else{
        schedContainer.removeChild(schedContainer.lastChild);                             
    }

    counter--; 
    updateSchedCounter();         

}

function availableRooms(){    

    let xhr = new XMLHttpRequest();

    let day = document.getElementById('selectDay').value;
    let from = document.getElementById('from_time').value;
    let until = document.getElementById('until_time').value;
    

    xhr.open('GET', APP_URL + '/admin/available/rooms/' + from + '/' + until + '/' + day , true);

    xhr.onload = function() {
        if (this.status == 200) { 
    
            removeOptions(selectRoom);
            // for(i = 0; i < selectRoom.length; i++){
            //     selectRoom.remove(i);
            // }
            
            let rooms = JSON.parse(this.responseText);            

            for (let i in rooms) {                                        
                selectRoom.options[i] = new Option(rooms[i].name, rooms[i].id); 
            }
                          

        } else {
        
        }                

    }

    xhr.send(); 

}



function availableFaculty(programID){

    

    let xhr = new XMLHttpRequest();

    let day = document.getElementById('selectDay').value;
    let from = document.getElementById('from_time').value;
    let until = document.getElementById('until_time').value;    

    xhr.open('GET', APP_URL + '/admin/available/faculty/' + programID + '/' + from + '/' + until + '/' + day , true);

    xhr.onload = function() {
        if (this.status == 200) { 
            
            removeOptions(selectFaculty);       

            var faculty = JSON.parse(this.responseText);                          

            for (let i in faculty) {                                        
                selectFaculty.options[i] = new Option(capitalizeFirstLetter(faculty[i].last_name) + ', ' + capitalizeFirstLetter(faculty[i].first_name), faculty[i].id); 
            }
                          

        } else {
        
        }                
        

    }

    xhr.send(); 
}

function changeClassesSelects($isSelectProg = false){  
    
    removeAllOptions(selectSubject);    

    dept = selectDept.value;
    prog = selectProg.value;
    subj = selectSubject.value;   

    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept + '/', true);

    xhr.onload = function() {
        if (this.status == 200) { 

            if(!$isSelectProg){

                removeOptions(selectProg);

                var programs = JSON.parse(this.responseText);                                

                for (let i in programs) {                  
                    selectProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id); 
                }

                changeSubjects();

            } else {
                changeSubjects();
            }

        } else {
        
        }                

    }

    xhr.send(); 

}


function changeSubjects(){

    
    dept = selectDept.value;
    prog = selectProg.value;
        
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/department/' + dept + '/program/' + prog, true);

    xhr.onload = function() {
        if (this.status == 200) { 

            removeOptions(selectSubject);

            // for(i = 0; i < selectSubject.length; i++){
            //     selectSubject.remove(i);
            // }

            var subjects = JSON.parse(this.responseText);                                

            for (let i in subjects) {   
               
                // if(i == 0)
                //     changeProgramID();     
                
                //     console.log(programID.value)

                if(subjects[i].student_count < 1)
                    selectSubject.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc  + ' [units: ' + subjects[i].units + ']', subjects[i].id);                                    
                else
                    selectSubject.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc + ' [units: '+ subjects[i].units +']   (pending:' + subjects[i].student_count + ')', subjects[i].id); 
                
                
            }  

                    
            classesTableData();

        } else {

        }       

    }

    xhr.send(); 


}

function classesTableData(){
    
    dept = selectDept.value;
    prog = selectProg.value;
    subj = selectSubject.value;  
    
   

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/pendingstudentclass/' + dept + '/' + prog + '/' + subj, true);

    xhr.onload = function() {
        if (this.status == 200) {

            let students = JSON.parse(this.responseText);
        

    output = `<tbody>`;
            for (let i in students) {
                if(students[i] != null){

                    output += '<tr>';



if(i < sectionLimit)
    output +='<th scope="row"><input name="student_ids[]" class="form-control position-static" type="checkbox" id="blankCheckbox" value="'+ students[i].id +'" aria-label="..." checked></th>'; 
else
    output +='<th scope="row"><input name="student_ids[]" class="form-control position-static" type="checkbox" id="blankCheckbox" value="'+ students[i].id +'" aria-label="..." ></th>';

output +='<td>' + students[i].student_id + '</td>' +
    '<td>' + capitalizeFirstLetter(students[i].last_name) + ',  ' + capitalizeFirstLetter(students[i].first_name) + ' ' + students[i].middle_name.charAt(0).toUpperCase() + '. ' + '</td>' +
    '</tr>';

                }
               
            }

    output += ` </tbody>
              </table>`;

            document.getElementById('subjects-table').innerHTML = output;
            

        } else if (this.status == 404) {
            let output = 'not found...';
            document.getElementById('subjects-table').innerHTML = output;
        }
    }

    xhr.send();

}

function removeAllOptions(select){

    for(i =  select.options.length; i >= 0 ; i--){
        select.remove(i);        
    }

    select.setAttribute("placeholder", "--Select--");

}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateSchedCounter(){
    let multiSched = document.getElementById('multi-sched');
    multiSched.setAttribute('value', counter);

   
}

function changeProgramID(subjectid){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/' + subjectid, true);

    xhr.onload = function() {
        if (this.status == 200) { 
      
            var subject = JSON.parse(this.responseText);                                                    
            
            programID.value = subject.program_id;
            availableFaculty(programID.value);

        }    

    }

    xhr.send(); 
    
    
    
}

</script>