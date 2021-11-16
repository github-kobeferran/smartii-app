

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
    
    <div class="text-center d-none" id="loader-panel">
        <div class="spinner-border text-success" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="text-secondary mx-auto text-center">Creating a class for: </div>

    <div id="class-panel">
        <div class="row mt-0 ">
            <div class="col-sm mx-auto d-flex justify-content-center">
                <u><h5 id="cur-program" style="font-family: 'Source Code Pro', monospace !important;" class=""></h5></u>
                <span class="mx-2">|</span>
                <u><h5 id="cur-subject" style="font-family: 'Source Code Pro', monospace !important;" class="ml-1"></h5></u>
            </div>
        </div>
    
        <div class="row ">
            <div class="col-sm mx-auto text-center">
                <h6 class="">SET CLASS NAME</h6>
                <input type="text" name="class_name" maxlength="25" class="text-center mx-auto w-25" required>                               

            </div>            
            
        </div>
        
        <div class="row mt-2 ">    
            
            <div class="col-sm">                        
                <div class="dropdown float-right mb-2">

                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Sort By
                    </button>
    
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" onclick="classesTableData('id_asc')">Student ID (ASC)</button>              
                        <button class="dropdown-item" onclick="classesTableData('id_desc')">Student ID (DESC)</button>              
                      <button class="dropdown-item" onclick="classesTableData('last_name_asc')">Last Name (ASC)</button>              
                      <button class="dropdown-item" onclick="classesTableData('last_name_desc')">Last Name (DESC)</button>              
                    </div>
    
                    <span id="cur-sort" class="ml-2">                    
                    </span>
    
                </div>
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
                    ['name' => 'day', 'placeholder' => 'Pick a Day', 'class' => 'custom-select bg-light text-dark border-secondary', 'id' => 'selectDay', 'required' => 'required'])}}            
    
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
                    [ 'name' => 'room_id', 'class' => 'custom-select bg-light text-dark border-secondary ', 'id' => 'selectRoom', 'required' => 'required'])}}            
                </div>    
    
            </div>
    
            <div class="col-sm">
    
                <p class="text-center"><strong>Instructor</strong></p>
                
                <div class="form-group text-center">        
                        
                    {{Form::select('level', 
                    [], null,
                    ['name' => 'instructor_id', 'class' => 'custom-select bg-light text-dark border-secondary ', 'id' => 'selectFaculty', 'required' => 'required'])}}            
                </div>    
    
            </div>
           
        </div>        

    </div>
    

    <div class = "form-group mt-2">
        <div>
            <button onclick="anotherSched()" type="button" class="btn btn-light border-success"><i class="fa fa-plus" aria-hidden="true"></i> Add another schedule</button>
        <button id="minusButton" onclick="deleteSched()" type="button" class="btn btn-light border-danger d-none"><i class="fa fa-minus-circle" aria-hidden="true"></i> Remove the last schedule</button>
        </div>
        <span class="ml-2" style="font-size: .8em;">NOTE: <b class="text-primary">ONCE SUBMITTED</b>, YOU CAN EDIT, BUT <b class="text-danger">UNABLE TO ADD AND DELETE</b> A SCHEDULE INTO THIS CLASS</span>                
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
let loaderPanel = document.getElementById('loader-panel');
let classPanel = document.getElementById('class-panel');

let cur_program = '';
let cur_subject = '';
let cur_mode = 'id_asc';
let counter = 1;

selectDept.addEventListener('change', () => {
    changeClassesSelects();
    // changeProgramID(selectSubject.value);
    currentProgramAndSubject(selectProg.value, selectSubject.value);
});
selectProg.addEventListener('change', () => {
    changeClassesSelects(true);
    // changeProgramID(selectSubject.value);
    currentProgramAndSubject(selectProg.value, selectSubject.value);
});
selectSubject.addEventListener('change', () => {
    classesTableData();
    availableFaculty();
    availableRooms();   
    changeProgramID(selectSubject.value);
    currentProgramAndSubject(selectProg.value, selectSubject.value);
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

    let selects = {
            day: null,
            room: null,
            faculty: null,
            from: null,
            until: null,
        }
  

    schedContainerChildCount = schedContainer.childElementCount;

    let newSchedRow = schedRow.cloneNode([true]);

    for(let i = 0; i<newSchedRow.childNodes.length; i++){
        
        if(newSchedRow.childNodes[i].tagName  == "DIV"){

            for(let j=0; j<newSchedRow.childNodes[i].childNodes.length; j++){                            

                if(newSchedRow.childNodes[i].childNodes[j].tagName  == "DIV"){

                
                    for(let k=0; k<newSchedRow.childNodes[i].childNodes[j].childNodes.length; k++){      

                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].tagName  == "SELECT" ) {

                            newSchedRow.childNodes[i].childNodes[j].childNodes[k].name += '_' + schedContainerChildCount;
                            newSchedRow.childNodes[i].childNodes[j].childNodes[k].id += '_' + schedContainerChildCount;  

                            if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].name.includes('day')){                                
                                    selects.day = newSchedRow.childNodes[i].childNodes[j].childNodes[k];
                            }else if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].name.includes('room')){
                                selects.room = newSchedRow.childNodes[i].childNodes[j].childNodes[k];
                            }else if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].name.includes('instructor')){
                                selects.faculty = newSchedRow.childNodes[i].childNodes[j].childNodes[k];
                            }
                                                   
                        
                        }                      
                        

                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].tagName  == "DIV"){                          

                            for(let l=0; l<newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes.length; l++){

                                if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].tagName == "INPUT")  {

                                    if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name != null){
                                        newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name += '_' + schedContainerChildCount;
                                        newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].id += '_' + schedContainerChildCount;


                                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name.includes('from')){                                
                                                selects.from = newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l];
                                        }else if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name.includes('until')){
                                                selects.until = newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l];
                                        }

                                    }

                                }

                            }

                        }

                    }
                
                }
                                        
            }
            
        }

    }    

    selects.day.addEventListener('change', () => {
        availableRooms(selects.room);    
        changeProgramID(selectSubject.value, selects.faculty);
    });

    selects.from.addEventListener('input', () => {
        availableRooms(selects.room);  
        changeProgramID(selectSubject.value, selects.faculty);  

    });

    selects.until.addEventListener('input', () => {
        availableRooms(selects.room);    
        changeProgramID(selectSubject.value, selects.faculty);
    });

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

function availableRooms(anotherSelect = null){  


    let selectElement = null;
    let day = null;
    let from = null;
    let until = null;  

    if(anotherSelect != null){
        let count = anotherSelect.id.slice(-1)
        day = document.getElementById('selectDay_' + count).value;
        from = document.getElementById('from_time_' + count).value;
        until = document.getElementById('until_time_' + count).value;        
        selectElement = anotherSelect;
    } else {
        day = document.getElementById('selectDay').value;
        from = document.getElementById('from_time').value;
        until = document.getElementById('until_time').value;    
        selectElement = selectRoom;
    }

    let xhr = new XMLHttpRequest();


    xhr.open('GET', APP_URL + '/admin/available/rooms/' + from + '/' + until + '/' + day , true);

    xhr.onload = function() {
        if (this.status == 200) { 
    
            removeOptions(selectElement);
         
            let rooms = JSON.parse(this.responseText);            

            for (let i in rooms) {                                        
                selectElement.options[i] = new Option(rooms[i].name, rooms[i].id); 
            }
                          

        } else {
        
        }                

    }

    xhr.send(); 

}



function availableFaculty(programID, anotherSelect = null){

    let selectElement = null;
    let day = null;
    let from = null;
    let until = null;

    if(anotherSelect != null){
        let count = anotherSelect.id.slice(-1)
        day = document.getElementById('selectDay_' + count).value;
        from = document.getElementById('from_time_' + count).value;
        until = document.getElementById('until_time_' + count).value;        
        selectElement = anotherSelect;
    } else {
        day = document.getElementById('selectDay').value;
        from = document.getElementById('from_time').value;
        until = document.getElementById('until_time').value;    
        selectElement = selectFaculty;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/available/faculty/' + programID + '/' + from + '/' + until + '/' + day , true);
    xhr.onload = function() {
        if (this.status == 200) { 
            
            removeOptions(selectElement);       

            var faculty = JSON.parse(this.responseText);  

            

            for (let i in faculty) {                                                        
                selectElement.options[i] = new Option(capitalizeFirstLetter(faculty[i].last_name) + ', ' + capitalizeFirstLetter(faculty[i].first_name), faculty[i].id); 
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

            var subjects = JSON.parse(this.responseText);                                

            for (let i in subjects) {   
                if(subjects[i].student_count < 1)
                    selectSubject.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc  + ' [units: ' + subjects[i].units + ']', subjects[i].id);                                    
                else
                    selectSubject.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc + ' [units: '+ subjects[i].units +']  (pending:' + subjects[i].student_count + ')', subjects[i].id); 
            }              
                    
            classesTableData();     

            currentProgramAndSubject(selectProg.value, selectSubject.value);       

        } else {

        }       

    }

    xhr.send(); 


}

function classesTableData(mode){
    
    dept = selectDept.value;
    prog = selectProg.value;
    subj = selectSubject.value;  
    
    switch(mode){
        case 'id_asc':
            document.getElementById('cur-sort').textContent = 'Student ID asc';
            cur_mode = 'id_asc';
        break;
        case 'id_desc':
            document.getElementById('cur-sort').textContent = 'Student ID desc';
            cur_mode = 'id_desc';
        break;
        case 'last_name_asc':
            document.getElementById('cur-sort').textContent = 'Last Name asc';
            cur_mode = 'last_name_asc';
        break;
        case 'last_name_desc':
            document.getElementById('cur-sort').textContent = 'Last Name desc';
            cur_mode = 'last_name_desc';
        break;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + `/admin/pendingstudentclass/${dept}/${prog}/${subj}/${cur_mode}`, true);

    loaderPanel.classList.remove('d-none');
    classPanel.classList.add('d-none');

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
    '<td>' + capitalizeFirstLetter(students[i].last_name) + ',  ' + capitalizeFirstLetter(students[i].first_name) + ' ' + (students[i].middle_name != null ? students[i].middle_name.charAt(0).toUpperCase() : '' ) + '. ' + '</td>' +
    '</tr>';

                }
               
            }

    output += ` </tbody>
              </table>`;

            document.getElementById('subjects-table').innerHTML = output;
            
            loaderPanel.classList.add('d-none');
            classPanel.classList.remove('d-none');

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

function changeProgramID(subjectid, selectElement = null){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/' + subjectid, true);

    xhr.onload = function() {
        if (this.status == 200) { 
      
            var subject = JSON.parse(this.responseText);                                                    
            
            programID.value = subject.program_id;
            
            if(selectElement == null)
                availableFaculty(programID.value);
            else
                availableFaculty(programID.value, selectElement);

        }    

    }

    xhr.send(); 
}

async function currentProgramAndSubject(progid, subjid){
    const res = await fetch(APP_URL + `/admin/view/programs/${progid}`);
    const program = await res.json();


    const res2 = await fetch(APP_URL + `/admin/view/subjects/${subjid}`);
    const subject = await res2.json();    

    let level = '';

    switch(subject.level){
        case 1:
            level = 'Grade 11';
        break;
        case 2:
            level = 'Grade 12';
        break;
        case 11:
            level = 'First Year';
        break;
        case 12:
            level = 'Second Year';
        break;
    }
    document.getElementById('cur-program').textContent = level + " - " + program.abbrv;

    document.getElementById('cur-subject').textContent = subject.desc;
}
</script>