{!! Form::open(['url' => 'admin/create/class', 'files' => true, 'id' => 'classForm']) !!}

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

    <div class="row shadow">    

        <div class="col-sm">
            
            <div class="form-group text-center">        
                    
                {{Form::select('subject', 
                [], null,
                ['class' => 'custom-select w-50 m-1', 'id' => 'selectSubject'])}}            
            </div>    

        </div>
       
    </div>

    <div class="row mt-2 ">    
        
        <div class="col-sm">
            
            <div class ="table-responsive border shadow" style="max-height: 500px; overflow: auto; display:inline-block;">
                <table class="table table-striped table-responsive-sm border" >
                    <thead class="thead">
                        <tr>
                            <th scope="col">Check</th>
                            <th scope="col">Student ID</th>
                            <th scope="col">Name</th>
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
    
                        <input type="time" id="appt" name="from"
                        min="07:00" max="19:00" class="form-control bg-light text-dark border-secondary" required>          
                        
    
                    </div>
    
                    <div class="col-sm">
                        <p><strong >Until:   </strong></p>
                        <input type="time" id="appt" name="until"
                        min="08:00" max="21:00" class="form-control bg-light text-dark border-secondary" required>          
    
                    </div>
    
                </div>
    
            </div>        
            
            <div class="col-sm">
    
                <p class="text-center"><strong>Room</strong></p>
                
                <div class="form-group text-center">        
                        
                    {{Form::select('room', 
                    [], null,
                    ['name' => 'room_id', 'class' => 'custom-select bg-light text-dark border-secondary ', 'id' => 'selectRoom'])}}            
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

    


{!! Form::close() !!}

<script>

let selectDept = document.getElementById('selectDept');
let selectProg = document.getElementById('selectProg');
let selectSubject = document.getElementById('selectSubject');
let selectRoom = document.getElementById('selectRoom');
let selectFaculty = document.getElementById('selectFaculty');
let schedContainer = document.getElementById('sched-container');


let counter = 1;

selectDept.addEventListener('change', () => {
    changeClassesSelects();
    
});
selectProg.addEventListener('change', () => {
    changeClassesSelects(true);
    
});
selectSubject.addEventListener('change', () => {
    classesTableData();
    
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
                           console.log(newSchedRow.childNodes[i].childNodes[j].childNodes[k].name);
                        }

                        if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].tagName  == "DIV"){                          

                            for(let l=0; l<newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes.length; l++){

                                if(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].tagName == "INPUT")

                                    newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name += '_' + schedContainerChildCount;
                                    console.log(newSchedRow.childNodes[i].childNodes[j].childNodes[k].childNodes[l].name);

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

function allRooms(){
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/rooms/', true);

    xhr.onload = function() {
        if (this.status == 200) { 
    
            for(i = 0; i < selectProg.length; i++){
                selectProg.remove(i);
            }

            var rooms = JSON.parse(this.responseText);                                

            for (let i in rooms) {                                        
                selectRoom.options[i] = new Option(rooms[i].name, rooms[i].id); 
            }
                          

        } else {
        
        }                

    }

    xhr.send(); 

}

function allFaculty(){
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/faculty/', true);

    xhr.onload = function() {
        if (this.status == 200) { 
    
            for(i = 0; i < selectProg.length; i++){
                selectFaculty.remove(i);
            }

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
    removeAllOptions(selectSubject);

    dept = selectDept.value;
    prog = selectProg.value;
    subj = selectSubject.value;   

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept + '/', true);

    xhr.onload = function() {
        if (this.status == 200) { 

            if(!$isSelectProg){
                for(i = 0; i < selectProg.length; i++){
                    selectProg.remove(i);
                }

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

            for(i = 0; i < selectSubject.length; i++){
                selectSubject.remove(i);
            }

            var subjects = JSON.parse(this.responseText);                                

            for (let i in subjects) {                                        
                selectSubject.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc, subjects[i].id); 
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
                output += '<tr>' +
                    '<th scope="row"><input class="form-control position-static" type="checkbox" id="blankCheckbox" value="'+ students[i].id +'" aria-label="..."></th>' +
                    '<td>' + students[i].student_id + '</td>' +
                    '<td>' + capitalizeFirstLetter(students[i].last_name) + ',  ' + capitalizeFirstLetter(students[i].first_name) + ' ' + students[i].middle_name.charAt(0).toUpperCase() + '. ' + '</td>' +
                    '</tr>';
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

    console.log(multiSched.value);
}

</script>