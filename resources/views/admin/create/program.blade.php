{!! Form::open(['url' => 'admin/create/program', 'id' => 'programForm']) !!}    

    <div class="row">   
        
        <div class="col-xl-6">
            <b>{{Form::label('department', 'Department')}}</b>
            <div class="input-group">     

                {{Form::select('dept', 
                ['0' => 'Senior High School',                              
                '1' => 'College'], 0,
                ['class' => 'custom-select',
                 'id' => 'selectDeptForProgram',
                 'required' => 'required'])}}      

                <div class="p-2 d-none" id="is-tesda-div">
                    <label class="px-2 pb-4" for="">Is this a Tesda Program? Check if yes</label>
                    <input type="checkbox" name="is_tesda" style="width: 20px; height: 20px;">                        
                    <i type="button" class="fa fa-info-circle text-info ml-2" data-toggle="tooltip" title="TESDA courses are not affected by CHED rules like unit pricing, unit scheduling and general subjects" aria-hidden="true"></i>                        
                </div>
                
            </div>
            
            <div class = "form-group">        
                <b>{{Form::label('desc', 'Program Description', ['class' => 'mt-2'])}}</b>
                {{Form::text('desc', '', ['class' => 'form-control',
                    'placeholder' => 'Course/Strand Description',
                    'required' => 'required'])}}
            </div> 

            <div class = "form-group">        
                <b>{{Form::label('abbrv', 'Program Abbreviation', ['class' => 'mt-2'])}}</b>
                {{Form::text('abbrv', '', ['class' => 'form-control w-50',
                                           'placeholder' => 'Course/Strand Abbreviation',
                                           'required' => 'required'])}}
            </div>   
            
            <div class = "form-group ">        
                {{Form::submit('Save',  ['class' => 'btn btn-success btn-block'])}}
            </div>
        </div>


    </div>     
    

{!! Form::close() !!}

    {{----------------------------------------- VIEW SECTION --}}   

    
<div class="row no-gutters vh-100 border-top">

    <div class="col-5 border-right pr-2 mt-1">
    
        <h5>VIEW PROGRAMS</h5>
        
        <div class="btn-group btn-group-toggle border" data-toggle="buttons">
            <label class="btn btn-light active">
                <input type="radio" name="options" id="shsOptionForProg" autocomplete="off" checked> SHS
            </label>        
            <label class="btn btn-light">
                <input type="radio" name="options" id="collegeOptionForProg" autocomplete="off"> College
            </label>
        </div>
     
        <div class="form-group has-search mt-2 mb-0">
            <span class="fa fa-search form-control-feedback"></span>
            <input id="program-search" type="text" class="form-control" placeholder="Search Program">
        </div>

        <div id="program-list" style="max-height: 75%; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">                               
    

        </div>
        

    </div>

    <div class="col text-center">

        <div id="showProgram" class="d-none">

            <h5 id="dept"></h5>
            <p>Department</p>
            <h5 id="abbrv"></h5>
            <p>Program Abbreviation</p>
            <h5 id="desc"></h5>
            <p>Program Description</p>

            <button onclick="progEdit()" type="button" class="btn btn-info text-white">Edit</button>
            <button href="" class="btn btn-danger text-white" disabled>Delete</button>          

        </div>

        <div id="editProgram" class="d-none border-top mt-2">

            <h5 id="title">EDIT PROGRAM</h5>

            {!!Form::open(['url' => '/updateprogram',  'class' => 'p-2' ]) !!}
                Department
                {{Form::select('dept', ['0' => 'SHS', '1' => 'College'], '', ['class' => 'mb-2 form-control' , 'id' => 'edit-progdept'])}}            

                <div id="edit-tesda-div" class="d-none">
                    <label>Check if TESDA</label>
                    <input type="checkbox" class="" name="is_tesda" id="edit-is-tesda" style="width: 20px; height: 20px;">
                </div>

                Abbreviation
                {{Form::text('abbrv' , '', ['class' => 'mb-2 form-control' , 'id' => 'edit-abbrv' ])}}
                Description
                {{Form::text('desc' , '', ['class' => 'mb-2 form-control' , 'id' => 'edit-progdesc' ])}}
                {{Form::hidden('id' , '', ['id' => 'prog-id'])}}

                <button id="submit-button" data-toggle="modal" data-target="#update-modal" type="button" class="btn btn-primary">Update</button>
                

                <button onclick="cancelEdit()" class="btn btn-warning">Cancel</button>

                <div class="modal fade" id="update-modal" tabindex="-1" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title"><span class="text-white" id="modal-title"></span></h5>
                                <button aria-hidden="true" data-dismiss="modal" class="close">&times;</button>
                            </div>
                            <div class="modal-body text-justify">
                                <p>This program has <b><span id="student-count"></span></b> students, are you sure you want to edit their program?</p>
                                <p><span id="other-msg"></span></p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" data-dismiss="close" class="btn btn-light">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            {!!Form::close()!!}

        </div>

    </div>

<script>

let selectDeptForProgram = document.getElementById('selectDeptForProgram');
let isTesdaDiv = document.getElementById('is-tesda-div');

let programList = document.getElementById('program-list');
let editProgDept = document.getElementById('edit-progdept');
let editIsTesdaDiv = document.getElementById('edit-tesda-div');
let editIsTesda = document.getElementById('edit-is-tesda');
let editProgAbbrv = document.getElementById('edit-abbrv');
let editProgDesc = document.getElementById('edit-progdesc');
let progid = document.getElementById('prog-id');
let showProgram = document.getElementById('showProgram');
let editProgram = document.getElementById('editProgram');
let title = document.getElementById('title');
let programSearch = document.getElementById('program-search');
let submit_button = document.getElementById('submit-button');

let currentDept = 0;

selectDeptForProgram.addEventListener('change', () => {
    if(selectDeptForProgram.value != 0)
        isTesdaDiv.classList.remove('d-none');
    else
        isTesdaDiv.classList.add('d-none');

    console.log(selectDeptForProgram.value);

});

shsOptionForProg.onclick = () => {
    fillProgramList(0);
    currentDept = 0;
    showProgram.classList.add('d-none');
}

collegeOptionForProg.onclick = () => {
    fillProgramList(1);
    currentDept = 1;
    showProgram.classList.add('d-none');
}

programSearch.addEventListener('keyup', async () => {
    const res = await fetch(APP_URL + '/admin/searchby/programs/department/' + currentDept + '/' + programSearch.value);
    const programs = await res.json();

    output = `<div id="program-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">
                <ul class="list-group mt-2">`;
                    programs.forEach(program => {
                        output+= `<li id="prog-${program.id}" onclick="programsSelect('${program.id}')" class="list-group-item program-button">${program.abbrv} - ${program.desc}`;
                    });    
      output +=`</ul>
              </div>`; 

    programList.innerHTML = output;

});

function cancelEdit(){

    editProgram.classList.add('d-none')

}

function programsSelect(id){
    showProgram.classList.remove('d-none');
    editProgram.classList.add('d-none')

    let programbuttons = document.getElementsByClassName('program-button');

    btn = document.getElementById('prog-' + id);

    for(i=0; i<programbuttons.length; i++){
        programbuttons[i].classList.remove('active');           
        programbuttons[i].classList.remove('text-white');           
    }  

    btn.classList.add('active');
    btn.classList.add('text-white');    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/' + id , true);

    xhr.onload = function() {
        if (this.status == 200) {
            let program = JSON.parse(this.responseText);
            let output = `<div id="showProgram" class="mt-2">                                    
                            <h5 id="dept">${program.dept_desc.toUpperCase()} ` + (program.is_tesda ? `| <span class="text-info">TESDA</span>`: ``) +`</h5>
                            <p>Department</p>
                            <h5 id="abbrv">${program.abbrv.toUpperCase()}</h5>
                            <p>Program Abbreviation</p>
                            <h5 id="desc">${program.desc.toUpperCase()}</h5>
                            <p>Program Description</p>
                            <button onclick="progEdit(${program.id})" type="button" class="btn btn-primary text-white" >Edit</button>`;

                            if(program.student_count < 1)
                                output+=`<button class="btn btn-danger text-white mx-2" >Delete</button>`;                    

                    output+= `<div class="text-right">
                                <a target="_blank" href="${APP_URL}/viewprogramcourses/export/${program.abbrv}">view course outline</a>
                            </div>

                        </div>`;
            showProgram.innerHTML = output;   
        }   
    }
    xhr.send();
}

function progEdit(id){

    editProgram.classList.remove('d-none')

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/' + id , true);

    xhr.onload = function() {
        if (this.status == 200) {

            let program = JSON.parse(this.responseText);
            title.textContent = 'EDIT ' + program.desc.toUpperCase();

            progid.value = program.id;

            document.getElementById('modal-title').textContent = 'UPDATE ' + program.desc.toUpperCase();
            document.getElementById('student-count').textContent = program.student_count;            

            editProgDept.value = program.department;

            editProgDept.addEventListener('change', () => {
                if(editProgDept.value == 1){
                    editIsTesdaDiv.innerHTML = `<div id="edit-tesda-div" class="">
                        <label><b>Check if TESDA</b></label>
                        <input type="checkbox" class="" name="is_tesda" id="edit-is-tesda" style="width: 20px; height: 20px;" checked>
                    </div>`;
                } else {
                    editIsTesdaDiv.classList.add('d-none');            
                }
            });   

            if(program.department == 1){
                editIsTesda.type = 'checkbox';
                editIsTesdaDiv.classList.remove('d-none');    
                document.getElementById('other-msg').textContent = 'also, Changing to TESDA will SET all of these program subjects units to default 80 hours, Change to NOT TESDA will SET all of these program hours to default 3 units.';
                if(program.is_tesda == 1){
                    editIsTesdaDiv.innerHTML = `<div id="edit-tesda-div" class="">
                        <label><b>Check if TESDA</b></label>
                        <input type="checkbox" class="" name="is_tesda" id="edit-is-tesda" style="width: 20px; height: 20px;" checked>
                    </div>`;
                } 
            }

            if(program.student_count < 1){
                editProgDept.disabled = false;                
                submit_button.removeAttribute('data-toggle');
                submit_button.removeAttribute('data-target');
                submit_button.setAttribute('type', 'submit');
            } else {
                editProgDept.disabled = true;
                submit_button.setAttribute('data-toggle', 'modal');
                submit_button.setAttribute('data-target', '#update-modal');
                submit_button.setAttribute('type', 'button');
            }
            
            editProgAbbrv.value = program.abbrv;
            editProgDesc.value = program.desc;

        }   
            
    }

    xhr.send();


}


function fillProgramList(dept){

let xhr = new XMLHttpRequest();

xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept , true);

xhr.onload = function() {
    if (this.status == 200) {

    let programs = JSON.parse(this.responseText);

    
    
output = `<div id="program-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
output = `<ul class="list-group mt-2">`;

    for(let i in programs){

        output += ` <li id="prog-`+ programs[i].id +`" onclick="programsSelect(\'`+ programs[i].id + `\')" class="list-group-item program-button">`+ programs[i].abbrv + ' - ' + programs[i].desc  +`</li>`;

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

window.onbeforeunload = function(event)
{
    return '';
};

document.getElementById("programForm").onsubmit = function(e) {
    window.onbeforeunload = null;
    return true;
};

</script>
