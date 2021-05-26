{!! Form::open(['url' => 'admin/create/subject', 'files' => true, 'id' => 'subjectForm']) !!}    

    <div class="row">    

        <div class="col">                            
            
                <div class="form-group">
                    {{Form::label('code', 'Subject Code', ['class' => 'mt'])}}
                    {{Form::text('code', '', ['class' => 'form-control w-25', 'placeholder' => 'Subject Code'])}}                 
                </div>
                
                <div class = "form-group">        
                    {{Form::label('desc', 'Subject Description', ['class' => 'mt'])}}
                    {{Form::text('desc', '', ['class' => 'form-control', 'placeholder' => 'Subject Description'])}}
                </div> 

        </div>

        <div class="col">                

        </div>

    </div>
    
    <hr class= "w-75 ml-0"/>
    

    <div class="row">    
        
        <div class="col-4"> 

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
            
            <div class="form-group">
                {{Form::label('units', 'No. of Units', ['class' => 'mt'])}}
                {{Form::number('units', '', ['class' => 'form-control w-25', 'step' => '3', 'min' => '3', 'max' => '12', 'required' => 'required', 'placeholder' => 'Units'])}}                 
            </div>

           

        </div>


        <div class="col-8"> 
            
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
    
    <hr class= "w-75 ml-0"/>
    
    

    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}   
    <div class = "form-group mr-0">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-25 mt-3'])}}
    </div> 
    <hr class=""/> 

    {{-- {{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{SUBMIT}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}}} --}}

{!! Form::close() !!}

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


selectSubjDept.addEventListener('change', () => {    
    changeSubjectSelect();    
    changePreReqList();
    clearList();
});

selectSubjLevel.addEventListener('change', () => {    
    changePreReqList();        
    clearList();
});

selectSubjProg.addEventListener('change', () => {    
    changePreReqList();    
    clearList();
});

selectSubjSem.addEventListener('change', () => {    
    changePreReqList();    
    clearList();
});

selectPreReq.addEventListener('change', () => {    
    preReqToAddList();
    
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

    xhr.open('GET', 'http://smartii-app.test/admin/view/programs/department/' + dept + '/true', true);

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
    xhr.open('GET', 'http://smartii-app.test/admin/view/prereqs'
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


</script>
