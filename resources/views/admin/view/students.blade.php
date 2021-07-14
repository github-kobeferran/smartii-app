<div class="row no-gutters vh-100">

    <div class="col-5 border-right">

        <div class="btn-group btn-group-toggle border" data-toggle="buttons">
            <label class="btn btn-light active">
                <input type="radio" name="options" id="shsOption" autocomplete="off" checked> SHS
            </label>        
            <label class="btn btn-light">
                <input type="radio" name="options" id="collegeOption" autocomplete="off"> College
            </label>
        </div>
        
        <button data-toggle="modal" data-target="#exportStudent" class="btn-sm btn-success float-right mt-1">
            Export to Excel
        </button>

        <div class="modal fade" id="exportStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">SELECT REPORT</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                  <div class="container">

                    <div class="row">

                        <div class="col text-center border-right">

                            <a href="/allstudents/export" class="d-block btn-primary mb-2">

                                All-Time Students

                            </a>                            
                            
                            
                        </div>

                        <div class="col text-center">
                            
                            <a href="/allactivestudents/export" class="d-block btn-primary mb-2">

                                All Active Students

                            </a>                           

                        </div>

                    </div>                    

                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                  
                </div>
              </div>
            </div>
          </div>

        <div id="program-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                               
    

        </div>
        

    </div>

    <div class="col-7">

        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input id="student-search" type="text" class="form-control" placeholder="Search by Name">
        </div>
        
        <div id="student-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" >
           
                     

        </div>
        
    </div>

</div>

<script>    

let currentProgram = null;

let shsOption = document.getElementById('shsOption');
let collegeOption = document.getElementById('collegeOption');
let programList = document.getElementById('program-list');
let studentList = document.getElementById('student-list');
let studentSearch = document.getElementById('student-search');

shsOption.onclick = () => {
    fillProgramList(0);
}

collegeOption.onclick = () => {
    fillProgramList(1);
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

            output += ` <li id="prog-`+ programs[i].id +`" onclick="programSelect(\'`+ programs[i].id + `\')" class="list-group-item program-button">`+ programs[i].abbrv + ' - ' + programs[i].desc  +`</li>`;

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

    currentProgram = id;

    let programbuttons = document.getElementsByClassName('program-button');

    btn = document.getElementById('prog-' + id);

    for(i=0; i<programbuttons.length; i++){
        programbuttons[i].classList.remove('active');           
        programbuttons[i].classList.remove('text-white');           
    }  

    btn.classList.add('active');
    btn.classList.add('text-white');
    
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/students/program_id/' + id , true);

    xhr.onload = function() {
        if (this.status == 200) {

        let students = JSON.parse(this.responseText);
        let output = '';
        
        if(!isEmpty(students)){            

    output = `<div class="student-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" >`;       

        for(let i in students){  
            
            let level = '';
            switch(students[i].level){
                case 1:
                    level = "Grade 11";
                break;
                case 1:
                    level = "Grade 11";
                break;
                case 11:
                    level = "Freshman";
                break;
                case 1:
                    level = "Sophomore";
                break;
            }

 output+= `<button class="list-group-item list-group-item-action flex-column align-items-start">
              <div class="d-flex w-100 justify-content-left">`;
        output+= `<h5 class="mb-1 mr-0">`+ ucfirst(students[i].last_name) +`, `+ ucfirst(students[i].first_name) +`, `+ ucfirst(students[i].middle_name) +`</h5> 
        <a data-toggle="tooltip" data-placement="top" title="Visit Student Profile" class="ml-1 color-primary" href="/studentprofile/` + students[i].student_id+`">` + students[i].student_id.toUpperCase() +`</a>
        
        </div>`;
      output+=`<p class="">`+ level +` - `+ ucfirst(students[i].gender) +` - ` + students[i].age +` years old - ` + students[i].email +`</p>              
            </button>`; 

        }

    output+= `</div>`;

}else{

    output = `<h5 class="text-center"> NO STUDENTS IN THIS PROGRAM </h5>`;
}

    


            studentList.innerHTML = output;

        } else {
            output = '';
            studentList.innerHTML = output;
        }
    }

    xhr.send();

}

studentSearch.addEventListener('keyup', searchStudent);

function searchStudent(){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/searchby/students/program_id/'+ currentProgram + '/' + studentSearch.value, true);

    xhr.onload = function() {
        if (this.status == 200) {

        let students = JSON.parse(this.responseText);

    output = `<div class="student-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" >`;       

            for(let i in students){
                output+= `<button class="list-group-item list-group-item-action flex-column align-items-start">
              <div class="d-flex w-100 justify-content-left">`;
        output+= `<h5 class="mb-1 mr-0">`+ ucfirst(students[i].last_name) +`, `+ ucfirst(students[i].first_name) +`, `+ ucfirst(students[i].middle_name) +`</h5> 
        <a data-toggle="tooltip" data-placement="top" title="Visit Student Profile" class="ml-1 color-primary" href="/studentprofile/` + students[i].student_id+`">` + students[i].student_id.toUpperCase() +`</a>
        
        </div>`;
      output+=`<p class="">`+ students[i].level_desc +` - `+ ucfirst(students[i].gender) +` - ` + students[i].age +` years old - ` + students[i].email +`</p>              
            </button>`; 

            }
    output += `</div>`;
         

            studentList.innerHTML = output;

        } else {
            output = '';
            studentList.innerHTML = output;
        }
    }

    xhr.send();

    

}





</script>


