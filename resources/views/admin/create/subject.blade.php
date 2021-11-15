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

                <div id="pre-req-list" class="card mt-2 w-75 d-none" >
                    <h5 class="card-header bg-info text-white">Added Subjects</h5>
                    <ul id="preReqList" class="list-group list-group-flush">
                        
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

            <div id="edit-panel" class="d-none">

                {!! Form::open(['url' => '/updatesubject', 'class' => 'p-2 m-2']) !!}

                <h5 class=""> Edit <i class="fa fa-caret-right"></i> <span id="edit-subj-title"> </span></h5>                
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
                    <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" onclick="cancelSubjEdit()" class="btn btn-danger">Cancel</button>

                </div>

                {!! Form::close() !!}

            </div>

        </div>
        
    </div>

</div>




<script>

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

    xhr.onload = function() {

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
                                            output+=`<button class="text-info border-0" data-toggle="modal" data-target="#prereq-`+ pre_req.id +`">`+ pre_req.code +`</button>
                                            
                                            <div class="modal fade" id="prereq-`+ pre_req.id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Change `+ pre_req.desc +` [`+ pre_req.code +`]</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    {!! Form::open(['url' => '/updateprereq']) !!}
                                                    <div class="modal-body">
                                                        

                                                            Input the Subject Code of the Subject you want to replace
                                                            {{Form::text('subj_code', '`+ pre_req.code +`', ['class' => 'form-control'])}}
                                                            {{Form::hidden('pre_req_id', '`+ pre_req.id +`', ['class' => 'form-control'])}}
                                                            {{Form::hidden('subj_id', '`+ subject.id +`', ['class' => 'form-control'])}}
                                                            
                                                        
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                            {!! Form::close() !!}
                                                      
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            
                                            `;
                                        });
                                        
                                output+=`
                                        
                                    </td>                                    

                                </tr>

                                <tr>

                                    <td role="button" class="bg-danger">
                                        <a href="/deletesubject/`+ subject.id +`" class="btn btn-danger btn-block text-white" > DELETE THIS SUBJECT</a>
                                    </td>
                                    <td onclick="showEdit(`+ subject.id +`,`+ subject.dept+`)" role="button" class="bg-info text-center">
                                       <div class="my-auto text-white"> EDIT THIS SUBJECT</div>
                                    </td>

                                </tr>

                            </table> 
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
let preReqDiv = document.getElementById('addPreReq');
    if(preReqDiv.style.display == 'none') {
        preReqDiv.style.display = 'block';
        
    } else {
        preReqDiv.style.display =  'none';
      
    }
}

let selectSubjDept = document.getElementById('selectSubjDept');
let selectSubjLevel = document.getElementById('selectSubjLevel');
let selectSubjProg = document.getElementById('selectSubjProg');
let selectSubjSem = document.getElementById('selectSubjSem');
let selectPreReq = document.getElementById('selectPreReq');
let subjectSearch = document.getElementById('subject-search');


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


function changeSubjectSelect(){
    
    removeAllOptions(selectPreReq);

    dept = selectSubjDept.value;
    level = selectSubjLevel.value;
    prog = selectSubjProg.value;

    // console.log(dept);

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

    removeAllOptions(selectPreReq);
    
    let dept = selectSubjDept.value;
    let program = selectSubjProg.value;
    let level = selectSubjLevel.value;
    let semester = selectSubjSem.value;

   

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

               for(let i in subjects){                                                         
                    selectPreReq.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc, subjects[i].id); 
               }

               let option = new Option('Select a Pre-Req Subject', '', true, true);
               selectPreReq.insertBefore(option, selectPreReq.options[0]);                             

           }

        } else {
        
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

    preReqListDiv.className = 'card mt-2 w-75';

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

    preReqListDiv.className = 'card mt-2 d-none';
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