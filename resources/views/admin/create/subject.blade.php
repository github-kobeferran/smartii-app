{!! Form::open(['url' => 'admin/create/subject', 'id' => 'subjectForm']) !!}    

    <div class="row">    

        <div class="col-sm">                            
            
                <div class="form-group">
                    {{Form::label('', 'Subject Code', ['class' => 'mt'])}}
                    {{Form::text('code', '', ['class' => 'form-control w-50', 'placeholder' => 'Subject Code'])}}                 
                </div>
                
                <div class = "form-group">        
                    {{Form::label('', 'Subject Description', ['class' => 'mt'])}}
                    {{Form::text('desc', '', ['class' => 'form-control', 'placeholder' => 'Subject Description'])}}
                </div> 

        </div>

        <div class="col">                

        </div>

    </div>
    
    <hr class= "w-75 ml-0"/>
    

    <div class="row">    
        
        <div class="col-sm-4"> 

            {{Form::label('department', 'Subject is for:')}}
            <div class="form-group">                
                {{Form::select('dept', 
                  ['0' => 'Senior High School Students',                              
                  '1' => 'College Students'], 0,
                  ['class' => 'custom-select ml-2', 'id' => 'selectSubjDept'])}}                   
            </div>                      

            <div class="form-group">                
                {{Form::select('level', 
                  [], null,
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectSubjLevel'])}}                   
            </div>                      

            {{Form::label('prog', 'Choose Dedicated Program:')}}
            <div class="form-group">                
                {{Form::select('prog', 
                  [], null,
                  ['class' => 'custom-select ml-2', 'id' => 'selectSubjProg'])}}                   
            </div>                      
            
            {{Form::label('sem', 'To be taken in:')}}
            <div class="form-group">                
                {{Form::select('sem', 
                  ['1' => 'First Semester ',
                   '2' => 'Second Semester '], null,
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectSubjSem'])}}                   
            </div>  
            
            <div class="form-group" id="units-div">
                {{Form::label('units', 'No. of Units', ['class' => '', 'id'=> 'units-label'])}}
                {{Form::number('units', 3, ['id' => 'subject-units', 'class' => 'form-control w-25', 'step' => '3', 'min' => '3', 'max' => '12', 'required' => 'required', 'placeholder' => 'Units'])}}                 
            </div>
           
            {{Form::hidden('is_tesda', 0, ['id' => 'is-tesda-hidden'])}}

        </div>


        <div class="col-sm-8"> 
            
            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input name="pre_req" type="checkbox" class="custom-control-input" id="preReqSwitch">
                    <label class="custom-control-label" for="preReqSwitch"><strong>Add Pre-Requisites</strong></label>
                </div>            
            </div>

            <div class="form-inline" id="addPreReq" style="display: none;">
                
                {{Form::select('prereqList', 
                  [], null, 
                  ['class' => 'custom-select w-50 ml-2', 'id' => 'selectPreReq', 'placeholder' => 'Select a Pre-Req Subject'])}}  
                <button type="button" onclick="clearList()" id="clear-pre-req" class="btn btn-danger" >Clear List</button>

                <div id="pre-req-list" class="card rounded-0 mt-2 ml-3 w-75 d-none" >
                    <h5 class="card-header bg-info text-white">Added Subjects</h5>
                    <ul id="preReqList" class="list-group list-group-flush ">
                        
                    </ul>
                </div>

            </div>

        </div>

    </div>
    
    
    
    
    <div class = "form-group mr-0">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
    </div>     
    
    <hr class= "ml-0"/>
    

{!! Form::close() !!}

    {{----------------------------------------- VIEW SECTION --}}   

<h5>VIEW SUBJECTS</h5>

<div class="row no-gutters">

    <div class="col-5 border-right">

        <div class="btn-group btn-group-toggle border" data-toggle="buttons">
            <label class="btn btn-light active">
                <input type="radio" name="options" id="shsOption" autocomplete="off" checked> SHS
            </label>        
            <label class="btn btn-light">
                <input type="radio" name="options" id="collegeOption" autocomplete="off"> College
            </label>
        </div>
     
        <div class="form-group has-search mt-2 mb-2">
            <span class="fa fa-search form-control-feedback"></span>
            <input id="subject-search" type="text" class="form-control" placeholder="Search Subject by Description or Program">
        </div>

        <div id="subject-list" style="max-height: 75%; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                               
    

        </div>
        

    </div>

    <div class="col-7">        
        
        <div class="align-middle" >

            <div id="subject-panel">

            </div>

            <div id="edit-panel" class="d-none" style="background: #6db1e96b;">

                {!! Form::open(['url' => '/updatesubject', 'class' => 'p-2 m-2']) !!}

                <div class="row">
                    <div class="col-10">
                        <h5>
                            Edit <i class="fa fa-caret-right"></i> <span id="edit-subj-title"> </span>
                        </h5>                      
                    </div>
                    <div class="col text-right">
                        <button type="button" onclick="cancelSubjEdit()" class="btn btn-sm btn-light text-danger">&times;</button>
                    </div>
                </div>
                
                {{Form::hidden('subject_id', null, ['id' => 'subj-id'])}}
                Code
                {{Form::text('code', '', ['id'=> 'edit-code', 'class' => 'form-control'])}}
                Description
                {{Form::text('desc', '', ['id'=> 'edit-desc', 'class' => 'form-control'])}}                
                Department
                {{Form::select('dept', ['0' => 'SHS', '1' => 'College'], '', ['id'=> 'edit-dept', 'class' => 'form-control'])}}                
                Program
                {{Form::select('prog', [], null, ['id'=> 'edit-prog', 'class' => 'form-control'])}}                
                Level
                {{Form::select('', [
                                        '1' => 'Grade 11',
                                        '2' => 'Grade 12',                                      
                                       ], null, ['id'=> 'edit-level-shs', 'class' => 'd-none form-control'])}}
                {{Form::select('', [
                                        '11' => 'First Year',
                                        '12' => 'Second Year',                                      
                                       ], null, ['id'=> 'edit-level-col', 'class' => 'd-none form-control'])}}
                Semester 
                {{Form::select('semester', [
                    '1' => 'First Semester',
                    '2' => 'Second Semester',                                      
                   ], '', ['id'=> 'edit-sem', 'class' => 'form-control'])}}
               
                Units/Hours
                {{Form::number('units', 3, ['id'=> 'edit-units', 'class' => 'form-control'])}}

                {{Form::hidden('is_tesda', 0, ['id'=> 'edit-is-tesda', 'class' => 'form-control'])}}

                <div class="form-group mt-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" onclick="cancelSubjEdit()" class="btn btn-danger">Cancel</button>
                </div>

                {!! Form::close() !!}

            </div>

        </div>
        
    </div>

</div>




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

let subjectList = document.getElementById('subject-list');
let subjectPanel = document.getElementById('subject-panel');
let editPanel = document.getElementById('edit-panel');

let unitsDiv = document.getElementById('units-div');

let editSubjectTitle = document.getElementById('edit-subj-title');
let editCode = document.getElementById('edit-code');
let editDesc = document.getElementById('edit-desc');
let editProg = document.getElementById('edit-prog');
let editLevelSHS = document.getElementById('edit-level-shs');
let editLevelCOL = document.getElementById('edit-level-col');
let editSem = document.getElementById('edit-sem');
let editDept = document.getElementById('edit-dept');
let editUnits = document.getElementById('edit-units');
let subjid = document.getElementById('subj-id');
let curDept = 0;

let selectSubjDept = document.getElementById('selectSubjDept');
let selectSubjLevel = document.getElementById('selectSubjLevel');
let selectSubjProg = document.getElementById('selectSubjProg');
let selectSubjSem = document.getElementById('selectSubjSem');
let selectPreReq = document.getElementById('selectPreReq');
let subjectSearch = document.getElementById('subject-search');

let is_tesda = false;

shsOption.onclick = () => {
    fillSubjectsList(0);
    cancelSubjEdit();
    curDept = 0;
}

collegeOption.onclick = () => {
    fillSubjectsList(1);
    cancelSubjEdit();
    curDept = 1;
}

selectSubjDept.addEventListener('change', () => {    
    changeSubjectSelect();    
    changePreReqList();
    clearList();
});

selectSubjLevel.addEventListener('change', () => {    
    clearList();
    changePreReqList();        
});

selectSubjProg.addEventListener('change', () => {    
    clearList();
    changePreReqList();    
    changeToUnitsOrHours(selectSubjProg.value);
});

selectSubjSem.addEventListener('change', () => {    
    clearList();
    changePreReqList();    
});

selectPreReq.addEventListener('change', () => {    
    preReqToAddList();
    
});


subjectSearch.addEventListener('keyup' , async () => {

    const res = await fetch(APP_URL + '/admin/search/subjects/' + (subjectSearch.value == '' ? 'SearchInputIsEmpty' : subjectSearch.value )+ '/' + curDept)
                        .catch((error) => {console.log(error)});

    const subjects = await res.json();    

    subjectList.innerHTML = '';

    let output = `<div id="subject-list" style="max-height: 75%; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="">
            <ul class="list-group">
    `;        

    subjects.forEach((subject) => {                
        output+= `<li role="button" id="subj-${subject.id}" onclick="subjectClicked(${subject.id})" class="btn-subject list-group-item">${subject.desc} </li>`;    
    });

    output+= `</ul>
    </div>`;

    subjectList.innerHTML = output;

});


function fillSubjectsList(dept){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/dept/'+ dept +'/true', true)

    xhr.onload = function() {

        if(this.status == 200){

            let subjects = JSON.parse(this.responseText);

            let output = '';

            for(let i in subjects){

                output+= '<li role="button" id="subj-'+ subjects[i].id +'" onclick="subjectClicked('+ subjects[i].id +')" class="btn-subject list-group-item">'+ subjects[i].desc +'</li>';

            }

            subjectList.innerHTML = output;

        }

    }

    xhr.send();

}

function subjectClicked(id){

    btn = document.getElementById('subj-' + id);
    btns = document.getElementsByClassName('btn-subject');

    editPanel.classList.add('d-none');
    editLevelSHS.classList.add('d-none');
    editLevelCOL.classList.add('d-none');



    for(let i=0; i<btns.length; i++){

        btns[i].classList.remove('active');

    }
    
    btn.classList.add('active');

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/'+ id, true)

    xhr.onload = async function() {

        if(this.status == 200){

            let subject = JSON.parse(this.responseText);

            let output = `<div id="subject-panel">

                            <table class="table table-bordered mt-5">

                                <tr>
                                    <td class="bg-info text-white">
                                        Code    
                                    </td>
                                    <td>
                                        `+ subject.code +`
                                    </td>

                                </tr>
                                <tr>
                                    <td class="bg-info text-white">
                                        Description
                                    </td>
                                    <td>
                                        `+ subject.desc +`
                                    </td>

                                </tr>
                                <tr>
                                    <td class="bg-info text-white">
                                        Program
                                    </td>
                                    <td>
                                        `+ subject.program_desc +`
                                    </td>                   
                                
                                </tr>

                                <tr>
                                    <td class="bg-info text-white">
                                        Intended for
                                    </td>
                                    <td>
                                        `+ subject.level_desc +`
                                    </td>                   
                                
                                </tr>

                                <tr>
                                    <td class="bg-info text-white">
                                        To be taken in
                                    </td>
                                    <td>
                                        `+ subject.semester_desc +`
                                    </td>                   
                                
                                </tr>

                                <tr>
                                    <td class="bg-info text-white">
                                        Units/Hours
                                    </td>
                                    <td>
                                        `+ subject.units + (subject.program.is_tesda == 1 ? ` hrs` : ` units`) + `
                                    </td>

                                </tr>

                                <tr>

                                    <td class="bg-info text-white">
                                        Pre Requisite(s)
                                    </td>
                                    <td classs='d-block'>

                                        `;                                        
                                        subject.pre_reqs.forEach(pre_req => {
                                    output+=`<button class="text-info border-0 danger-on-hover" data-toggle="modal" data-target="#prereq-`+ pre_req.id +`">`+ pre_req.code +`</button>                                            
                                            
                                            <div class="modal fade" id="prereq-`+ pre_req.id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">DETACH - ${pre_req.code} - ${pre_req.desc}?</span></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    {!! Form::open(['url' => '/detachprereq']) !!}
                                                    <div class="modal-body">
                                                        <p class="text-justify px-3 py-1">
                                                            Are you sure you want to detach <b>${pre_req.code} - ${pre_req.desc}</b> from the pre-requisites of <b>${subject.desc}</b>? 
                                                        </p>
                                                            {{Form::hidden('pre_req_id', '`+ pre_req.id +`', ['class' => 'form-control'])}}
                                                            {{Form::hidden('subj_id', '`+ subject.id +`', ['class' => 'form-control'])}}
                                                    </div>
                                                    <div class="modal-footer my-0 py-0">
                                                        <button type="submit" class="btn btn-danger">Yes</button>
                                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                    </div>
                                                </div>
                                                </div>`;
                                        });
                                    
                        const data = await fetch(`${APP_URL}/admin/view/prereqs/department/${subject.dept}/program/${subject.program_id}/level/${subject.level}/semester/${subject.semester}`);
                        const possible_prereqs = await data.json();
                                                                                                  
                                if(Object.keys(possible_prereqs).length > 0)
                                        output+= `<button class="btn btn-sm btn-light border-0 " data-toggle="modal" data-target="#add-prereq-form-${subject.id}"><i class="fa fa-plus-square-o" style="color: #044716; font-size: 2em;" aria-hidden="true"></i></button>`;

                                output+=`                                          
                                    </td>      
                                </tr>
                                <tr>`;
                                   

                                        if(subject.is_taken){
                                            if(subject.is_trashed)
                                                output+=`<td role="button" data-toggle="modal" data-target="#restore-${subject.id}" class="bg-light text-dark text-center"><b>RESTORE THIS SUBJECT</b></td>`;
                                            else
                                                output+=`<td role="button" data-toggle="modal" data-target="#disable-${subject.id}" class="bg-warning text-dark text-center"><b>DISABLE THIS SUBJECT</b></td>`;
                                        } else{
                                            output+=`<td class="bg-danger">
                                                <a href="/deletesubject/`+ subject.id +`" class="btn btn-danger btn-block text-white" > DELETE THIS SUBJECT</a>
                                            </td>`;
                                        }

                                    output+=`
                                    <td onclick="${subject.is_trashed ? `` : `showEdit(${subject.id},${subject.dept})`}" role="button" class="bg-info text-center" style="text-align: center; vertical-align: middle;" >
                                       <div class="my-auto text-white"> EDIT THIS SUBJECT</div>
                                    </td>
                                </tr>

                            </table> 

                            <div class="modal fade" id="disable-`+ subject.id +`" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-dark">Disable ${subject.desc}</span></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-justify">
                                                ${subject.desc} have already been taken by a student therefore you can't delete it.
                                                If editing the subject isn't an option, you must disable it instead.
                                                <br>
                                                <br>
                                                <b>Disabling</b> will not list the subject on subject automation, but will be still shown when viewing classes history.
                                                <div class="text-right text-muted">
                                                    <em>Note: you can't disable a pre-requisite subject.</em>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        {!!Form::open(['url' => "/disablesubject"])!!}
                                            <input type="hidden" name="id" value="${subject.id}">
                                            <button type="submit" class="btn btn-warning text-dark">Disable</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-light text-dark">Cancel</button>
                                        {!!Form::close()!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal fade" id="restore-`+ subject.id +`" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-dark">Restore ${subject.desc}</span></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="text-justify">
                                                
                                                <b>Restore</b> ${subject.desc}?
                                                <div class="text-right text-muted">
                                                    <em>Note: restoring a subject will be added to automation.</em>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                        {!!Form::open(['url' => "/restoresubject"])!!}
                                            <input type="hidden" name="id" value="${subject.id}">
                                            <button type="submit" class="btn btn-info text-white">Restore</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-light text-dark">Cancel</button>
                                        {!!Form::close()!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal fade" id="add-prereq-form-`+ subject.id +`" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header bg-success">
                                        <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">Add a Pre-Requisite to ${subject.desc}</span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {!! Form::open(['url' => '/attachprereq']) !!}
                                    <div class="modal-body">`;
                                        
                                        if(Object.keys(possible_prereqs).length > 0){
                                            output+=`<label>Add a Pre-Requisite for ${subject.desc}</label>
                                            <select name="pre_req_id" class="form-control"> `;
                                            for(let i in possible_prereqs){
                                                let valid = true;     

                                                if(subject.pre_reqs.length > 0){
                                                    subject.pre_reqs.forEach(prereq => {
                                                        if(prereq.id == possible_prereqs[i].id)    
                                                            valid = false;
                                                    });
                                                } 

                                                if(valid)
                                                    output+=`<option value="${possible_prereqs[i].id}"> ${possible_prereqs[i].desc} - ${possible_prereqs[i].code} </option>`;
                                            }
                                            output+=`</select>`;

                                        }                                                         
                         output += `    <input type="hidden" name="subj_id" value="${subject.id}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Save</button>
                                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                    </div>
                                    {!! Form::close() !!}                                        
                                    </div>
                                </div>
                            </div>
                        </div>`;
          

            subjectPanel.innerHTML = output;

        }

    }

    xhr.send();

}

function showEdit(id, dept){   

    editPanel.classList.remove('d-none');
    
    if(dept == 0){
        editLevelSHS.classList.remove('d-none');
        editLevelSHS.name = 'level';
    }else {
        editLevelCOL.classList.remove('d-none');
        editLevelCOL.name = 'level';
    }

    fillProgramSelect(dept);

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/'+ id, true)

    xhr.onload = function() {

        if(this.status == 200){

            let subject = JSON.parse(this.responseText);            

            editSubjectTitle.textContent = subject.code + '-' + subject.desc;  
            subjid.value = subject.id;  
            editCode.value = subject.code;
            editDesc.value = subject.desc;            
            editSem.value = subject.semester;            

            if(subject.dept == 0){                                
                for(let i=0; i<editLevelSHS.length; i++){
                    if(editLevelSHS.options[i].value == subject.level){
                        editLevelSHS.selectedIndex = i;
                        editLevelSHS.value = subject.level;
                    }
                }
            }else{
                for(let i=0; i<editLevelCOL.length; i++){
                    if(editLevelCOL.options[i].value == subject.level){
                        editLevelCOL.selectedIndex = i;
                        editLevelCOL.value = subject.level;
                    }
                }
            }                


            editDept.value = subject.dept;

            editDept.addEventListener('change', async () => {
                const res = await fetch(APP_URL + '/admin/view/programs/department/' + editDept.value);
                const programs = await res.json();

                for(i = 0; i < editProg.length; i++){
                    editProg.remove(i);
                }

                programs.forEach((program, i) => {
                    editProg.options[i] = new Option(program.abbrv + ' - ' + program.desc, program.id); 
                });          
            });

            editUnits.value = subject.units;   
            editProg.value = subject.program_id;            

            if(subject.program.is_tesda){
                editUnits.setAttribute('min', 10);
                editUnits.setAttribute('max', 500);               
                editUnits.setAttribute('step', 1);    
                document.getElementById('edit-is-tesda').value = 1;
            } else {
                editUnits.setAttribute('min', 3);
                editUnits.setAttribute('max', 12);
                editUnits.setAttribute('step', 3);
                document.getElementById('edit-is-tesda').value = 0;
            }

            editProg.addEventListener('change', async () => {
                const res = await fetch(APP_URL + '/admin/view/programs/' + editProg.value);
                const program = await res.json();                  

                if(program.is_tesda == 1){     
                    editUnits.value = 10;
                    editUnits.setAttribute('min', 10);
                    editUnits.setAttribute('max', 500);           
                    editUnits.setAttribute('step', 1);            
                    document.getElementById('edit-is-tesda').value = 1;
                } else {                            
                    editUnits.value = 3;
                    editUnits.setAttribute('min', 3);
                    editUnits.setAttribute('max', 12);
                    editUnits.setAttribute('step', 3);
                    document.getElementById('edit-is-tesda').value = 0;
                }

            });


        }

    }

    xhr.send();

    editPanel.scrollIntoView({behavior: 'smooth'});

}

function cancelSubjEdit(){          

    editPanel.classList.add('d-none');
    editLevelSHS.classList.add('d-none');
    editLevelCOL.classList.add('d-none');

}

function fillProgramSelect(dept){

    removeAllOptions(editProg);

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/'+ dept + '/true', true)

    xhr.onload = function() {

        if(this.status == 200){                               
                                 
            let programs = JSON.parse(this.responseText);                         
        
            for(let i in programs){                                                         
                    editProg.options[i] = new Option(programs[i].desc, programs[i].id); 
            }

        }

    }

    xhr.send();


}

function togglePreReq(){
    let preReqDiv = document.getElementById('addPreReq');
    if(preReqDiv.style.display == 'none') {
        preReqDiv.style.display = 'block';
        
    } else {
        preReqDiv.style.display =  'none';
      
    }
}

function changeSubjectSelect(){
    
    removeAllOptions(selectPreReq);

    dept = selectSubjDept.value;
    level = selectSubjLevel.value;
    prog = selectSubjProg.value;

    if(dept == 0){     
        selectSubjLevel.options[0] = new Option('Grade 11', '1');
        selectSubjLevel.options[1] = new Option('Grade 12', '2');
    } else {
        selectSubjLevel.options[0] = new Option('First Year', '11');
        selectSubjLevel.options[1] = new Option('Second Year', '12');                  
    } 

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept + '/true', true);

    xhr.onload = function() {
        if (this.status == 200) { 

            for(i = 0; i < selectSubjProg.length; i++){
                selectSubjProg.remove(i);
            }

            var programs = JSON.parse(this.responseText);                                

            for (let i in programs) {                                        
                selectSubjProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id); 
            }

        } else {
        
        }                

    }

    xhr.send(); 

}

function changePreReqList(){
    
    let dept = selectSubjDept.value;
    let program = selectSubjProg.value;
    let level = selectSubjLevel.value;
    let semester = selectSubjSem.value;   

    removeAllOptions(selectPreReq); 

    var xhr = new XMLHttpRequest();   
    xhr.open('GET', APP_URL
                    + '/admin/view/prereqs'
                    +'/department/' + dept 
                    + '/program/' + program 
                    + '/level/' + level 
                    + '/semester/' + semester, true);

    xhr.onload = function() {
        if (this.status == 200) {

            let subjects = JSON.parse(this.responseText); 
                                                            
            if( (level == 1 || level == 11) &&  (semester == 1) ){
                
            } else {                           

                let output = `<select id="selectPreReq" name="prereqList" class="form-control ml-2">
                <option value="" selected> Select a Pre-Req Subject </option>`;

                for(let i in subjects){  
                    output += `<option value="${subjects[i].id}"> ${subjects[i].code} - ${subjects[i].desc}</option>`;                    
                }

                output += `</select>`;
                   
                selectPreReq.innerHTML = output;

            }

        } 

    }

    xhr.send(); 

}


function removeAllOptions(select){

    for(i =  select.options.length; i >= 0 ; i--){
        select.remove(i);        
    }

    select.setAttribute("placeholder", "Select a Pre-Req Subject");
    
}

function preReqToAddList(){    
    
    let valid = true;

    let preReqListDiv = document.getElementById('pre-req-list');
    let preReqListUl = document.getElementById('preReqList');

    preReqListDiv.classList.remove('d-none');

    var li = document.createElement("li");
    var input = document.createElement("input");

    for(i=0; i < preReqListUl.children.length; i++){

        let listItemText = preReqListUl.children[i].textContent;

        let selectedInPreReq = selectPreReq.options[selectPreReq.selectedIndex];

        if(selectedInPreReq.value == ''){
            valid = false;
        }

        if(listItemText == selectedInPreReq.text){
            valid = false;  
        }
    }

    if(valid){

        li.appendChild(document.createTextNode(selectPreReq.options[selectPreReq.selectedIndex].text));    
        li.setAttribute("class","list-group-item bg-light"); 

        input.setAttribute("name", "preReqs[]");
        input.setAttribute("value", selectPreReq.value); 
        input.setAttribute("type", "hidden"); 


        preReqListUl.appendChild(li);
        preReqListUl.appendChild(input);

    } else {
        
    }    

}

function clearList(){

    let preReqListUl = document.getElementById('preReqList');
    let preReqListDiv = document.getElementById('pre-req-list');


    for(var i=0; i<preReqListUl.children.length; i++) {  
                            
        preReqListUl.children[i].removeAttribute("name");        
        preReqListUl.removeChild(preReqListUl.children[i]); 
        
    }

    for(var i=preReqListUl.children.length; i>=0; i--) {  
                                                
        if(preReqListUl.children[i] != undefined){            
            preReqListUl.removeChild(preReqListUl.children[i]); 
        }
        
    }

    preReqListDiv.classList.add('d-none');
}

async function changeToUnitsOrHours(id){
    const res = await fetch(APP_URL + '/admin/view/programs/' + id);
    const program = await res.json();    

    if(program.is_tesda == 1){        
        
        unitsDiv.innerHTML = `<div class="form-group" id="units-div">        
            {{Form::label('units', 'No. of Hours', ['class' => '', 'id'=> 'units-label'])}}
            <input type="number" name="units" value="10" id="subject-units" class="form-control w-25 rounded-0" step="1" min="10" max="500" placeholder="Number of Hours" required>
        </div>`;        
                
        document.getElementById('is-tesda-hidden').value = 1;

    } else {        
        unitsDiv.innerHTML = `<div class="form-group" id="units-div">
            {{Form::label('units', 'No. of Units', ['class' => '', 'id'=> 'units-label'])}}
            <input type="number" name="units" value="3" id="subject-units" class="form-control w-25 rounded-0" step="3" min="3" max="12" placeholder="Number of Units" required>
        </div>`; 
        document.getElementById('is-tesda-hidden').value = 0;
    }

}

</script>