
<div class="row">

    <div class="col-sm d-flex justify-content-center">

        <h5>CLASSES AND SCHEDULES</h5>                

    </div>

</div>

<div class="row border-bottom">

    <div class="col-sm">
        <div class="btn-group btn-group-toggle border mb-3" data-toggle="buttons">
            <label id="shsOptionLabel" class="btn btn-light active">
                <input type="radio" name="options" id="shsOption" autocomplete="off" checked> SHS
            </label>
            <label id="collegeOptionLabel" class="btn btn-light">
                <input type="radio" name="options" id="collegeOption" autocomplete="off"> College
            </label>
        </div>

        <p><strong>Programs</strong></p>
        <div id="program-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
              
        </div>
        
    </div>
    
    <div class="col-sm">
        <div class="row">
            <div class="col text-right">
                <?php
                    $setting = \App\Models\Setting::first(); 
                    $subjects_taken = \App\Models\SubjectTaken::all();
                ?>    
                @if ($subjects_taken->count() > 0)
                    <?php 
                        $from_years = $subjects_taken->pluck('from_year');
                        $to_years = $subjects_taken->pluck('to_year');
                        $from_years = $from_years->unique();                        
                        $to_years = $to_years->unique();                        

                    ?>

                    <button type="button" data-toggle="modal" data-target="#export-to-excel" class="btn btn-sm btn-success">Export to Excel</button>

                    <div class="modal fade" id="export-to-excel" tabindex="-1" role="dialog" aria-labelledby="exportToExcelTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-success">
                                    <h5 class="modal-title"><span class="text-white">Export Classes to Excel</span></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">                                                          
                                    <div class="row">
                                        <div class="col text-left">                                            
                                            <div class="row">
                                                <div class="col text-center text-danger" id="export-title">
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="text-center">
                                                    <label  for=""><b>Academic Year</b></label>
                                                </div>
                                                <div class="input-group">                                                
                                                    <select name="from_year" max="{{$setting->from_year}}" id="select-export-from-year" class="form-control text-center">
                                                        @foreach ($from_years as $year)
                                                            <option value="{{$year}}">{{$year}}</option>
                                                        @endforeach
                                                    </select>                                                
                                                    <select  name="to_year" min="" max="{{$setting->to_year}}" id="select-export-to-year" class="form-control text-center">
                                                        @foreach ($to_years as $year)
                                                            <option value="{{$year}}">{{$year}}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Department</b></label>
                                                        </div>
                                                        <select id="select-export-dept" name="dept" id="" class="form-control text-center">
                                                            <option value="0">SHS</option>
                                                            <option value="1">COLLEGE</option>
                                                        </select> 
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Program</b></label>
                                                        </div>
                                                        <select id="select-export-prog" name="prog" id="" class="form-control text-center">
                                                            <option value="0">All in SHS</option>
                                                        </select> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Level</b></label>
                                                        </div>
                                                        <select id="select-export-level" name="level" id="" class="form-control text-center">
                                                            <option value="0">All SHS</option>
                                                            <option value="1">Grade 11</option>
                                                            <option value="2">Grade 12</option>
                                                        </select> 
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Semester</b></label>
                                                        </div>
                                                        <select name="sem" id="select-export-semester" class="form-control text-center">
                                                            <option value="0">All Semesters</option>
                                                            <option value="1">First Semester</option>
                                                            <option value="2">Second Semester</option>
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div>                                     
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Faculty</b></label>
                                                        </div>
                                                        <select id="select-export-faculty" name="faculty" id="" class="form-control text-center">
                                                            <option value="0">All SHS FACULTIES</option>
                                                        </select> 
                                                    </div>
                                                    <div class="col">
                                                        <div class="text-center">
                                                            <label for=""><b>Subject</b></label>
                                                        </div>
                                                        <select name="sem" id="select-export-subject" class="form-control text-center">
                                                            <option value="0">All Subjects</option>
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div>                                     
                                            <div class="text-center">
                                                <input id="check-export-active" type="checkbox" class="border border-secondary">
                                                <label><b>Active Classes Only</b></label>
                                            </div>
                                            <button type="button" onclick="advanceExport()" class="btn btn-block btn-success">GENERATE REPORT</button>
                                        </div>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                    </div>

                    
                @endif
      
            </div>
        </div>

        <div class="mb-4"></div>
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
window.onload = () => {
    deptIsChanged();
};

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

let viewPanel = document.getElementById('view-panel');

let selectFromYearExport = document.getElementById('select-export-from-year');
let selectToYearExport = document.getElementById('select-export-to-year');
let selectDeptExport = document.getElementById('select-export-dept');
let selectProgExport = document.getElementById('select-export-prog');
let selectLevelExport = document.getElementById('select-export-level');
let selectSemesterExport = document.getElementById('select-export-semester');
let selectFacultyExport = document.getElementById('select-export-faculty');
let selectSubjectExport = document.getElementById('select-export-subject');
let checkActiveExport = document.getElementById('check-export-active');

let cur_sched_id = null;
let cur_faculty_id = null;;
let cur_room_id = null;;

selectDeptExport.addEventListener('change', deptIsChanged)
selectProgExport.addEventListener('change', progExportIsChanged)
selectFromYearExport.addEventListener('change', () => {
    selectToYearExport.setAttribute('min', Number(selectFromYearExport.value) + 1);
});

editDay.addEventListener('change', () => {   
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_room_id);        
});

edit_from_time.addEventListener('input', () => {
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_room_id);     

});
edit_until_time.addEventListener('input', () => {
    availableFacultyExcept(cur_faculty_id);
    availableRoomsExcept(cur_room_id);     
});

let dept = 0;
let currentProgram = null;
let another_current_program = 0;

shsOption.onclick = () => {
    fillProgramList(0);
    dept = 0;
    viewPanel.innerHTML = `<div id="view-panel" ></div>`;
    subjectsList.innerHTML = ` <div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
            <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>`;
}

collegeOption.onclick = () => {
    fillProgramList(1);
    dept = 1;
    viewPanel.innerHTML = `<div id="view-panel" ></div>`;
    subjectsList.innerHTML = ` <div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
            <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>`;
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

    let programbuttons = document.getElementsByClassName('program-button');

    let btn = document.getElementById('prog-' + id);

    for(i=0; i<programbuttons.length; i++){
        programbuttons[i].classList.remove('active');           
        programbuttons[i].classList.remove('text-white');  
        programbuttons[i].style.pointerEvents = "none";           
        programbuttons[i].style.opacity = "0.6";                   
    }      
    
    document.getElementById('shsOptionLabel').classList.add('disabled');           
    document.getElementById('collegeOptionLabel').classList.add('disabled');           

    btn.classList.add('active');
    btn.classList.add('text-white');    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/department/' + dept + '/program/' + currentProgram , true);

    subjectsList.innerHTML = ` <div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group border">                               
            <div id="subject-spinner" class="spinner-border text-success d-none" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>`;
    viewPanel.innerHTML = `<div id="view-panel" ></div>`;    
    
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
                for(i=0; i<programbuttons.length; i++){               
                    programbuttons[i].style.pointerEvents = "";           
                    programbuttons[i].style.opacity = "";    
                }  

                document.getElementById('shsOptionLabel').classList.remove('disabled');           
                document.getElementById('collegeOptionLabel').classList.remove('disabled'); 
                        

            } else {

                output = `<div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
                output +=`<h5>No Classes</h5>`;   
                output +=`</div>`;   

                document.getElementById('subject-spinner').classList.add('d-none');
                subjectsList.innerHTML = output;
                for(i=0; i<programbuttons.length; i++){               
                    programbuttons[i].style.pointerEvents = "";           
                    programbuttons[i].style.opacity = "";             
                }  

                document.getElementById('shsOptionLabel').classList.remove('disabled');           
                document.getElementById('collegeOptionLabel').classList.remove('disabled'); 

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
                                                        
                                                    classes[i].subjects_taken.forEach(subject_taken => {
                                                        if(typeof subject_taken.student != 'undefined' && typeof subject_taken.student != null){
                                                            output+=`
                                                                <tr>
                                                                    <td><a href="${APP_URL}/studentprofile/${subject_taken.student.student_id}">${subject_taken.student.student_id}</a></td>
                                                                    <td class="text-left ml-2">${subject_taken.student.first_name} ${subject_taken.student.last_name}</td>
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
                                                    
                                                    

                                                    classes[i].subjects_taken.forEach(subject_taken => {                                                        
                                                        if(typeof subject_taken.student != 'undefined' && typeof subject_taken.student != null){
                                                            output+=`
                                                                <tr>
                                                                    <td><a href="${APP_URL}/studentprofile/${subject_taken.student.student_id}">${subject_taken.student.student_id}</a></td>
                                                                    <td class="text-left ml-2">${subject_taken.student.first_name} ${subject_taken.student.last_name}</td>
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

    cur_faculty_id = facultyID;   

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

    cur_room_id = roomId;

    let xhr = new XMLHttpRequest();

    let day = document.getElementById('editDay').value;
    let from = document.getElementById('edit_from_time').value;
    let until = document.getElementById('edit_until_time').value;

    xhr.open('GET', APP_URL + '/admin/availablerooms/' + from + '/' + until + '/' + day + '/' + roomId, true);

    xhr.onload = function() {
        if (this.status == 200) {             
            let rooms = JSON.parse(this.responseText);      

            let output =`<select name="room" class="custom-select bg-light text-dark border-secondary" id="editRoom">`;
            for (let i in rooms) {                 
                output+= `<option value="${rooms[i].id}" ${(rooms[i].id == roomId) ? 'selected' : ''}>${rooms[i].name}</option>`;
            }
            output +=`</select>`;

            editRoom.innerHTML = output;
        } 
    }

    xhr.send(); 
}

function cancelEditSched(){
    editPanel.classList.add('d-none');
}

async function deptIsChanged(){

    const res = await fetch(APP_URL + `/admin/view/programs/department/${selectDeptExport.value}/`)
                    .catch((error) => {console.log(error)});

    const data = await res.json();

    let output = `<select name="prog" value="" id="select-export-prog" class="form-control-md mr-2 rounded-0 border border-secondary">
                    <option value="0" selected> All ` + (selectDeptExport.value == 0 ? `SHS` : `College`) + ` Programs</option>`;
                    for(i in data){
                        output+=`<option value="${data[i].id}"> ${data[i].abbrv} </option>`;
                    }
    output+=`</select>`;

    selectProgExport.innerHTML = output;

    if(selectProgExport.value != 0){
        if(selectDeptExport.value == 0){
            selectLevelExport.innerHTML = ` <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All SHS</option>
                                                <option value="1">Grade 11</option>
                                                <option value="2">Grade 12</option>
                                            </select>`; 
        
        } else if(selectDeptExport.value == 1){
            selectLevelExport.innerHTML = `  <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All COLLEGE</option>
                                                <option value="1">First Year</option>
                                                <option value="2">Second Year</option>
                                            </select>`;
        }
    } else {
        if(selectDeptExport.value == 0){

            selectLevelExport.innerHTML = ` <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All in SHS</option>
                                                <option value="1">First Year</option>
                                                <option value="2">Second Year</option>
                                            </select>`;
        }else {
            selectLevelExport.innerHTML = ` <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All in COLLEGE</option>
                                                <option value="1">First Year</option>
                                                <option value="2">Second Year</option>
                                            </select>`;
        }
    }
    

    const res2 = await fetch(APP_URL + `/admin/view/faculty/department/${selectDeptExport.value}/`)
                    .catch((error) => {console.log(error)});

    const faculties = await res2.json();
    

    output = `<select id="select-export-faculty" name="faculty" id="" class="form-control text-center">
                <option value="0">All ${(selectDeptExport.value ? "COLLEGE" : "SHS")} FACULTIES</option>`;
                for(i in faculties){
                    output+=`<option value="${faculties[i].id}"> ${faculties[i].first_name} ${faculties[i].middle_name} ${faculties[i].last_name}</option>`;
                }
    output += `</select>`;

    selectFacultyExport.innerHTML = output;
    
    const res3 = await fetch(APP_URL + `/admin/view/subjects/dept/${selectDeptExport.value}/`)
                    .catch((error) => {console.log(error)});

    const subjects = await res3.json();

    output = `<select id="select-export-subject" name="subject" id="" class="form-control text-center">
                <option value="0">All ${(selectDeptExport.value ? "COLLEGE" : "SHS")} SUBJECTS</option>`;
                for(i in subjects){
                    output+=`<option value="${subjects[i].id}"> ${subjects[i].code} - ${subjects[i].desc}</option>`;
                }
    output += `</select>`;

    selectSubjectExport.innerHTML = output;


}

async function progExportIsChanged(){

    another_current_program = selectProgExport.value;

    if(selectProgExport.value > 0){
        const res = await fetch(APP_URL + `/admin/view/programs/${selectProgExport.value}/`)
                        .catch((error) => {console.log(error)});

        const program = await res.json();


        if(program.department == 0){
            selectLevelExport.innerHTML = ` <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All in ${program.abbrv}</option>
                                                <option value="1">Grade 11</option>
                                                <option value="2">Grade 12</option>
                                            </select>`;
        
        } else if(selectDeptExport.value == 1){
            selectLevelExport.innerHTML = `  <select name="level" id="select-level-level" class="form-control text-center">
                                                <option value="0">All in  ${program.abbrv}</option>
                                                <option value="1">First Year</option>
                                                <option value="2">Second Year</option>
                                            </select>`;
        }

        const res2 = await fetch(APP_URL + `/admin/view/faculty/program/${program.id}/`)
                    .catch((error) => {console.log(error)});

        const faculties = await res2.json();

        console.log(faculties);

        output = `<select id="select-export-faculty" name="faculty" id="" class="form-control text-center">
                    <option value="0">All ${(selectDeptExport.value ? "COLLEGE" : "SHS")}/${program.abbrv} FACULTIES</option>`;
                    for(i in faculties){
                        output+=`<option value="${faculties[i].id}"> ${faculties[i].first_name} ${faculties[i].middle_name} ${faculties[i].last_name}</option>`;
                    }
        output += `</select>`;

        selectFacultyExport.innerHTML = output;

        const res3 = await fetch(APP_URL + `/admin/view/subjects/department/${selectDeptExport.value}/program/${selectProgExport.value}/`)
                    .catch((error) => {console.log(error)});

        const subjects = await res3.json();

        output = `<select id="select-export-subject" name="subject" id="" class="form-control text-center">
                    <option value="0">All ${(selectDeptExport.value ? "COLLEGE" : "SHS")} SUBJECTS</option>`;
                    for(i in subjects){
                        output+=`<option value="${subjects[i].id}"> ${subjects[i].code} - ${subjects[i].desc}</option>`;
                    }
        output += `</select>`;

        selectSubjectExport.innerHTML = output;
  
    } else {

    }
}

function advanceExport(){
    error_div = document.getElementById('export-title')

    if(Number(selectFromYearExport.value) >= Number(selectToYearExport.value)){
        error_div.textContent = "Academic Start Year must be less than Academic End Year"
        return;
    }     

    params = `${selectFromYearExport.value}/${selectToYearExport.value}/${selectDeptExport.value}/${selectProgExport.value}/${selectLevelExport.value}/${selectSemesterExport.value}/${selectFacultyExport.value}/${selectSubjectExport.value}/${checkActiveExport.checked? 1: 0}`;
    window.location.href = `${APP_URL}/advancedclasses/export/${params}`;
}

</script>



