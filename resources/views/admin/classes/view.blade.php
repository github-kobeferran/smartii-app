
<div class="row">

    <div class="col-sm d-flex justify-content-center">

        <h5>CLASSES AND SCHEDULES</h5>                

    </div>

</div>

<div class="row border-bottom">

    <div class="col-sm">
        <div class="btn-group btn-group-toggle border mb-3" data-toggle="buttons">
            <label class="btn btn-light active">
                <input type="radio" name="options" id="shsOption" autocomplete="off" checked> SHS
            </label>
            <label class="btn btn-light">
                <input type="radio" name="options" id="collegeOption" autocomplete="off"> College
            </label>
        </div>

        <p><strong>Programs</strong></p>
        <div id="program-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
              
        </div>
        
    </div>
    
    <div class="col-sm">

        <div class="mb-5"></div>
        <p><strong>Subjects</strong></p>

        <div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
            <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>

    </div>    

</div>

<div id="edit-panel" class="row mt-2 text-center d-none" >

    <div class="col-sm d-flex justify-content-center">
        
        {!! Form::open(['url' => '/updateschedule', 'class' => 'border p-2', "style"=>"box-shadow: 1.5px 1.5px 25px 3.5px #ababab;"]) !!}
        <h5>EDIT SCHEDULE</h5>

        {{Form::hidden('class_id', null, ['id' => 'hiddenClassID'])}}
        {{Form::hidden('sched_id', null, ['id' => 'hiddenSchedID'])}}

    
        <div class="form-group">

            {{Form::select('instructor', 
            [], null,
            [ 
                'name' => 'instructor',                 
                'class' => 'custom-select bg-light text-dark border-secondary', 
                'id' => 'editInstructor'
            ])}}            

        </div>

        <div class="form-group d-flex justify-content-center">

            {{Form::text('class_name', '',['id' => 'editName', 'class' => 'form-control w-50 border border-dark', 'required' => 'required'])}}

        </div>
        <div class="form-group">

            {{Form::select('day', 
            ['mon' => 'Monday',
             'tue'=> 'Tuesday',
             'wed'=> 'Wednesday',
             'thu'=> 'Thursday',
             'fri'=> 'Friday',
             'sat'=> 'Saturday',                
            ], "mon",
            [ 
                'name' => 'day',                 
                'class' => 'custom-select bg-light text-dark border-secondary', 
                'id' => 'editDay'
            ])}}            

        </div>        

        <div class="row no-gutters">            
    
            <div class="col-md">                                                                
                
                <p><strong >From:   </strong></p>

                <input type="time" id="edit_from_time" name="from" value="07:00"
                min="07:00" max="19:00" class="form-control bg-light text-dark border-secondary" required>          
                

            </div>

            <div class="col-sm">
                <p><strong >Until:   </strong></p>
                <input type="time" id="edit_until_time"  name="until" value="08:00"
                min="08:00" max="21:00" class="form-control bg-light text-dark border-secondary" required>          

            </div>

        </div>
        <div class="form-group mt-2">

            {{Form::select('room', 
            [], null,
            [ 
                'name' => 'room',                 
                'class' => 'custom-select bg-light text-dark border-secondary', 
                'id' => 'editRoom'
            ])}}            

        </div>

        <div class="form-group mt-2">

            <button type="submit" class="btn btn-primary">

                Update

            </button>

            <button type="button" onclick="cancelEditSched()" class="btn btn-warning">

                Cancel

            </button>

        </div>

        {!! Form::close() !!}

    </div>

</div>

<div id="view-panel" >

    

</div>


<script>

let editPanel = document.getElementById('edit-panel');
let editDay = document.getElementById('editDay');
let editName = document.getElementById('editName');
let editRoom = document.getElementById('editRoom');
let editInstructor = document.getElementById('editInstructor');
let edit_from_time = document.getElementById('edit_from_time');
let edit_until_time = document.getElementById('edit_until_time');
let hiddenClassID = document.getElementById('hiddenClassID');
let hiddenSchedID = document.getElementById('hiddenSchedID');

let programList = document.getElementById('program-list');
let subjectsList = document.getElementById('subjects-list');
let shsOption = document.getElementById('shsOption');
let collegeOption = document.getElementById('collegeOption');

let cur_sched_id = null;
let cur_faculty_id = null;;
let cur_school_id = null;;

editDay.addEventListener('change', () => {   
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_faculty_id);        
});

edit_from_time.addEventListener('input', () => {
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_faculty_id);     

});
edit_until_time.addEventListener('input', () => {
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_faculty_id);     
});

let dept = 0;
let currentProgram = null;

shsOption.onclick = () => {
    fillProgramList(0);
    dept = 0;
}

collegeOption.onclick = () => {
    fillProgramList(1);
    dept = 1;
}

function fillProgramList(dept){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept , true);

    xhr.onload = function() {
        if (this.status == 200) {

        let programs = JSON.parse(this.responseText);

        
        
    output = `<div id="program-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
    output = `<ul class="list-group mt-2">`;

        for(let i in programs){

            output += ` <li id="prog-`+ programs[i].id +`" onclick="programSelect(`+ programs[i].id + `)" class="list-group-item program-button">`+ programs[i].abbrv + ' - ' + programs[i].desc  +`</li>`;

        }    

    output +=`</ul>`;       
    output +=`</div>`;   
    

        programList.innerHTML = output;

        } else {
            output = '';
            programList.innerHTML = output;
        }
    }

    xhr.send();
            
}

function programSelect(id){
    cancelEditSched();

    currentProgram = id;
    console.log(currentProgram);

    let programbuttons = document.getElementsByClassName('program-button');

    let btn = document.getElementById('prog-' + id);

    for(i=0; i<programbuttons.length; i++){
        programbuttons[i].classList.remove('active');           
        programbuttons[i].classList.remove('text-white');           
    }  

    btn.classList.add('active');
    btn.classList.add('text-white');    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/department/' + dept + '/program/' + currentProgram , true);

    subjectsList.innerHTML = ` <div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
            <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>`;
    document.getElementById('subject-spinner').classList.remove('d-none');

    xhr.onload = async function() {
        if (this.status == 200) {

            let subjects = JSON.parse(this.responseText);

            if(typeof subjects !== 'undefined'){            
    
                output = `<div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                    
                    <ul class="list-group mt-2">`;

                    for(let i in subjects){
                        const res = await fetch(APP_URL + `/countclass/${currentProgram}/${subjects[i].id}`);
                        const count = await res.json();                                                

                        output+=`<li role="button" id="subj-${subjects[i].id}" onclick="subjectSelect(${subjects[i].id},'${subjects[i].code} - ${subjects[i].desc}', ${subjects[i].program_id})" class="subject-button list-group-item list-group-item-action"> 
                            <span >${subjects[i].desc} </span> `;
                            if(Number(count) > 0)
                                output+=`<span class="badge badge-success">classes count: ${count}</span>`;
                        output+=`</li>`;

                    }    

                output +=`</ul>
                <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>`;   
                

                subjectsList.innerHTML = output;

            } else {

                output = `<div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
                output +=`<h5>No Classes</h5>`;   
                output +=`</div>`;   

                document.getElementById('subject-spinner').classList.add('d-none');
                subjectsList.innerHTML = output;

            }

        }else {

            output = `<div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
            output +=`<h5>No Classes</h5>`;   
            output +=`</div>`;   

            subjectsList.innerHTML = output;

        }

    }

    xhr.send();

}


function subjectSelect(subjid, subjDescAndCode, programid){  
    cancelEditSched();      

    let viewPanel = document.getElementById('view-panel');

    let subjectbuttons = document.getElementsByClassName('subject-button');

    let btn = document.getElementById('subj-' + subjid);

    for(i=0; i<subjectbuttons.length; i++){
        subjectbuttons[i].classList.remove('active');           
        subjectbuttons[i].classList.remove('text-white');           
    }  

    btn.classList.add('active');
    btn.classList.add('text-white');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/schedules/' + currentProgram + '/' + subjid , true);

    xhr.onload = function() {
        if (this.status == 200) {


            let classes = JSON.parse(this.responseText);

            if(classes && Object.keys(classes).length !== 0){

                currentProgram = classes[0].subjects_taken[0].student.program_id

                let output = `<div class="text-center container ">
                                <div class=" row mt-2">
                                    <h5 class="mx-auto">${subjDescAndCode} Schedules</h5>                            
                                </div>`;

                output += `<div id="view-panel" class="row mt-2 d-flex d-flex justify-content-between align-items-start align-content-start flex-wrap">`;            

                for(let i in classes){                                        
                    
                    if(i != 0 && i % 2 == 0){

                        output+= `<div class="card text-white bg-success m-2" style="min-width: 18rem; max-width: 18rem;">
                                    <div class="card-header">
                                        ${classes[i].class_name}
                                        <a  type="button" data-toggle="modal" data-target="#class-${classes[i].id}" class="text-secondary">View Students</a>
                                    </div>
                                    <div class="card-body">
                                    <h5 class="card-title text-white">`+classes[i].faculty_name +`</h5>`;
                                        classes[i].schedules.forEach(sched => {

                                            output+=`<ul class="list-group bg-success list-group-flush text-center">`;
                                                output+=`<li class="list-group-item list-group-item-success">`+ sched.day_name +`</li>`;
                                                output+=`<li class="list-group-item list-group-item-success">`+ sched.formatted_start +`</li>`;
                                                output+=`<li class="list-group-item list-group-item-success">`+ sched.formatted_until +`</li>`;
                                                output+=`<li class="list-group-item list-group-item-success">`+ sched.room_name +`</li>`;
                                                output+=`<li class="list-group-item list-group-item-success"><button onclick="editSched(` +sched.id +`)" class="btn btn-light">Edit this Sched</button></li>`;

                                            output+=`</ul> 
                                                <hr>
                                            `;                                            
                                        });
                            output+=`</div>
                                </div>
                                
                                <div class="modal fade" id="class-${classes[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title" id="exampleModalLongTitle">STUDENTS in ${classes[i].class_name}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive"> 
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Student ID</th>
                                                            <th>Name</th>
                                                        </tr>                                                        
                                                    </thead>
                                                    <tbody>`;
                                                        console.log(classes[i].subjects_taken);
                                                    classes[i].subjects_taken.forEach(subject_taken => {
                                                        if(typeof subject_taken.student != 'undefined' && typeof subject_taken.student != null){
                                                            output+=`
                                                                <tr>
                                                                    <td><a href="${APP_URL}/studentprofile/${subject_taken.student.student_id}">${subject_taken.student.student_id}</a></td>
                                                                    <td>${subject_taken.student.first_name} ${subject_taken.student.last_name}</td>
                                                                </tr>
                                                            `;
                                                        }
                                                    });                                                     
                                            output+=`</tbody>
                                                </table>
                                            </div>
                                        </div>               
                                        </div>
                                    </div>
                                </div>
                                `;

                    }else{
                        output+= `<div class="card text-secondary bg-warning m-2" style="min-width: 18rem; max-width: 18rem;">
                                    <div class="card-header">
                                        ${classes[i].class_name}
                                        <a  type="button" data-toggle="modal" data-target="#class-${classes[i].id}" class="text-info">View Students</a>
                                    </div>
                                    <div class="card-body">
                                    <h5 class="card-title">`+classes[i].faculty_name +`</h5>`;

                                    classes[i].schedules.forEach(sched => {

                                        output+=`<ul class="list-group bg-warning list-group-flush text-center">`;
                                            output+=`<li class="list-group-item list-group-item-warning">`+ sched.day_name +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-warning">`+ sched.formatted_start +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-warning">`+ sched.formatted_until +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-warning">`+ sched.room_name +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-warning"><button onclick="editSched(`+ sched.id +`)" class="btn btn-light">Edit this Sched</button></li>`;
                                            
                                        output+=`</ul> 
                                            <hr>
                                        `;                                        
                                        });
                                        
                        output+=`</div>
                                </div> 
                                
                                <div class="modal fade" id="class-${classes[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-dark">STUDENTS in ${classes[i].class_name}</span></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="table-responsive"> 
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Student ID</th>
                                                            <th>Name</th>
                                                        </tr>                                                        
                                                    </thead>
                                                    <tbody>`;
                                                    
                                                    console.log(classes[i].subjects_taken);

                                                    classes[i].subjects_taken.forEach(subject_taken => {                                                        
                                                        if(typeof subject_taken.student != 'undefined' && typeof subject_taken.student != null){
                                                            output+=`
                                                                <tr>
                                                                    <td><a href="${APP_URL}/studentprofile/${subject_taken.student.student_id}">${subject_taken.student.student_id}</a></td>
                                                                    <td>${subject_taken.student.first_name} ${subject_taken.student.last_name}</td>
                                                                </tr>
                                                            `;
                                                        }
                                                    });                                                     
                                            output+=`</tbody>
                                                </table>
                                            </div>
                                        </div>               
                                        </div>
                                    </div>
                                </div>`;

                    }

                }

                

                output+=`</div>
                    </div>`;


                viewPanel.innerHTML = output;


            } else {
                viewPanel.innerHTML = `
                    <div class="text-center mt-2">
                        <h5>No Classes in this program and subject</h5>
                    </div>
                `;
            }            

        }
    }

    xhr.send();

}

function editSched(schedID){

    editPanel.classList.remove('d-none');
    
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/schedule/'+ schedID , true);

    xhr.onload = function() {
        if (this.status == 200) {

            let schedule = JSON.parse(this.responseText); 
            
            cur_sched_id = schedule.id;
            cur_faculty_id = schedule.student_class.faculty_id;            
                        
            for(let i, j = 0; i = editDay.options[j]; j++) {
                if(i.value == schedule.day) {
                    editDay.selectedIndex = j;
                    break;
                }
            }

            editName.value = schedule.student_class.class_name;
            edit_from_time.value = schedule.start_time;
            edit_until_time.value = schedule.until;
            hiddenClassID.value = schedule.class_id;
            hiddenSchedID.value = schedule.id;

            availableFacultyExcept(schedule.student_class.faculty_id);
            availableRoomsExcept(schedule.room_id);

        }
    }

    xhr.send();

    editPanel.scrollIntoView({behavior: 'smooth'});

}

function availableFacultyExcept(facultyID){

    

    let xhr = new XMLHttpRequest();

    let day = document.getElementById('editDay').value;
    let from = document.getElementById('edit_from_time').value;
    let until = document.getElementById('edit_until_time').value;
    

    xhr.open('GET', APP_URL + '/admin/availablefaculty/' + from + '/' + until + '/' + day + '/' + facultyID + '/' + currentProgram, true);

    xhr.onload = function() {
        if (this.status == 200) { 
    
            removeOptions(editInstructor); 
            
            let faculty = JSON.parse(this.responseText);                           

            for (let i in faculty) {                                        
                editInstructor.options[i] = new Option(capitalizeFirstLetter(faculty[i].last_name) + ', ' + capitalizeFirstLetter(faculty[i].first_name), faculty[i].id); 

                if(faculty[i].id == facultyID)
                    editInstructor.selectedIndex = i;
            }
                          

        } else {
        
        }                

    }

    xhr.send(); 



}


function availableRoomsExcept(roomId){

    let xhr = new XMLHttpRequest();

    let day = document.getElementById('editDay').value;
    let from = document.getElementById('edit_from_time').value;
    let until = document.getElementById('edit_until_time').value;

    xhr.open('GET', APP_URL + '/admin/availablerooms/' + from + '/' + until + '/' + day + '/' + roomId, true);

    xhr.onload = function() {
        if (this.status == 200) { 

            removeOptions(editRoom); 
            
            let rooms = JSON.parse(this.responseText);                         

            for (let i in rooms) {                                        
                editRoom.options[i] = new Option(rooms[i].name, rooms[i].id);

                if(rooms[i].id == roomId)
                    editRoom.selectedIndex = i;
            }
            

                        

        } else {
        
        }                

    }

    xhr.send(); 



}

function cancelEditSched(){

    editPanel.classList.add('d-none');

    

}

</script>



