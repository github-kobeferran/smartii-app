{!! Form::open(['url' => 'admin/create/program', 'id' => 'programForm']) !!}    

    <div class="row">    

        <div class="col-sm"> 
            {{Form::label('department', 'Department')}}
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
                        <i type="button" class="fa fa-info-circle text-info ml-2" data-toggle="tooltip" title="TESDA courses are not affected by unit pricing" aria-hidden="true"></i>                        
                    </div>
                    
                </div>
                
                <div class = "form-group">        
                    {{Form::label('desc', 'Program Description', ['class' => 'mt'])}}
                    {{Form::text('desc', '', ['class' => 'form-control',
                        'placeholder' => 'Course/Strand Description',
                        'required' => 'required'])}}
                </div> 

                <div class = "form-group">        
                    {{Form::label('abbrv', 'Program Abbreviation', ['class' => 'mt'])}}
                    {{Form::text('abbrv', '', ['class' => 'form-control w-50',
                                               'placeholder' => 'Course/Strand Abbreviation',
                                               'required' => 'required'])}}
                </div>   
                
                <div class = "form-group text-right">        
                    {{Form::submit('Save',  ['class' => 'btn btn-success btn-block'])}}
                </div>

        </div>

        <div class="col">                

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
            <input id="program-search" type="text" class="form-control" placeholder="Search (not functioning)">
        </div>

        <div id="program-list" style="max-height: 75%; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">                               
    

        </div>
        

    </div>

    <div class="col text-center">

        <div id="showProgram" class="d-none">

            <h5 id="dept">SENIOR HIGH SCHOOL</h5>
            <p>Department</p>
            <h5 id="abbrv">TVL</h5>
            <p>Program Abbreviation</p>
            <h5 id="desc">TECHINICAL VOCATIONAL LIVELIHOOD</h5>
            <p>Program Description</p>

            <button onclick="progEdit()" type="button" class="btn btn-info text-white">Edit</button>
            <a href="" class="btn btn-danger text-white">Delete</a>

        </div>

        <div id="editProgram" class="d-none border-top mt-2">

            <h5 id="title">EDIT PROGRAM</h5>

            {!!Form::open(['url' => '/updateprogram',  'class' => 'p-2' ]) !!}
                Department
                {{Form::select('dept', ['0' => 'SHS', '1' => 'College'], '', ['class' => 'mb-2 form-control' , 'id' => 'edit-progdept'])}}            

                <div class="d-none text-left"  id="edit-is-tesda-div" >
                    Check if TESDA
                    <input type="checkbox" class="" name="is_tesda" id="edit-is-tesda" style="width: 20px; height: 20px;">
                </div>
                Abbreviation
                {{Form::text('abbrv' , '', ['class' => 'mb-2 form-control' , 'id' => 'edit-abbrv' ])}}
                Description
                {{Form::text('desc' , '', ['class' => 'mb-2 form-control' , 'id' => 'edit-progdesc' ])}}
                {{Form::hidden('id' , '', ['id' => 'prog-id'])}}

                <button type="submit" class="btn btn-primary">Update</button>
                <button onclick="cancelEdit()" class="btn btn-warning">Cancel</button>
            {!!Form::close()!!}

        </div>

    </div>

<script>

let selectDeptForProgram = document.getElementById('selectDeptForProgram');
let isTesdaDiv = document.getElementById('is-tesda-div');

let programList = document.getElementById('program-list');
let editProgDept = document.getElementById('edit-progdept');
let editIsTesdaDiv = document.getElementById('edit-is-tesda-div');
let editIsTesda = document.getElementById('edit-is-tesda');
let editProgAbbrv = document.getElementById('edit-abbrv');
let editProgDesc = document.getElementById('edit-progdesc');
let progid = document.getElementById('prog-id');
let showProgram = document.getElementById('showProgram');
let editProgram = document.getElementById('editProgram');
let title = document.getElementById('title');

selectDeptForProgram.addEventListener('change', () => {
    if(selectDeptForProgram.value != 0)
        isTesdaDiv.classList.remove('d-none');
    else
        isTesdaDiv.classList.add('d-none');

    console.log(selectDeptForProgram.value);

});

shsOptionForProg.onclick = () => {
    fillProgramList(0);
}

collegeOptionForProg.onclick = () => {
    fillProgramList(1);
}

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

                        <button onclick="progEdit(${program.id})" type="button" class="btn btn-info text-white">Edit</button>
                        <a href="/deleteprogram/${program.id}" class="btn btn-danger text-white">Delete</a>

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

        editProgDept.value = program.department;
        editProgDept.addEventListener('change', () => {
            if(editProgDept.value == 1)
                editIsTesdaDiv.classList.remove('d-none');
            else
                editIsTesdaDiv.classList.add('d-none');
        });

        if(program.department == 1){
            editIsTesdaDiv.classList.remove('d-none');
            if(program.is_tesda != 0){
                editIsTesda.value = 'on';
                editIsTesda.setAttribute('checked', 'checked');
            }
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
