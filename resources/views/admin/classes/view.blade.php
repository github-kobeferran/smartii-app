
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
            

        </div>

    </div>    

</div>

<div id="edit-panel" class="row mt-2 text-center">

    <div class="col-sm d-flex justify-content-center">

        {!! Form::open(['url' => '/updatesched']) !!}
    
        <div class="form-group">

            {{Form::select('day', 
            ['mon' => 'Monday',
             'tue'=> 'Tuesday',
             'wed'=> 'Wednesday',
             'thu'=> 'Thursday',
             'fri'=> 'Friday',
             'sat'=> 'Saturday',                
            ], null,
            [ 
                'name' => 'day', 
                'placeholder' => 'Epie Custodio', 
                'class' => 'custom-select bg-light text-dark border-secondary', 
                'id' => 'editInstructor'
            ])}}            

        </div>
        <div class="form-group d-flex justify-content-center">

            {{Form::text('class_name', 'BSIT 1-F1',['class' => 'form-control w-50'])}}

        </div>
        <div class="form-group">

            {{Form::select('day', 
            ['mon' => 'Monday',
             'tue'=> 'Tuesday',
             'wed'=> 'Wednesday',
             'thu'=> 'Thursday',
             'fri'=> 'Friday',
             'sat'=> 'Saturday',                
            ], null,
            [ 
                'name' => 'day', 
                'placeholder' => 'Pick a Day', 
                'class' => 'custom-select bg-light text-dark border-secondary', 
                'id' => 'editDay'
            ])}}            

        </div>

        {!! Form::close() !!}

    </div>

</div>

<div class="row mt-2">

    <div id="view-panel" class="col-sm d-flex d-none">
          

    </div>

</div>


<script>

let programList = document.getElementById('program-list');
let subjectsList = document.getElementById('subjects-list');
let shsOption = document.getElementById('shsOption');
let collegeOption = document.getElementById('collegeOption');

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

    currentProgram = id;

    let programbuttons = document.getElementsByClassName('program-button');

    let btn = document.getElementById('prog-' + id);

    for(i=0; i<programbuttons.length; i++){
        programbuttons[i].classList.remove('active');           
        programbuttons[i].classList.remove('text-white');           
    }  

    btn.classList.add('active');
    btn.classList.add('text-white');    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/department/' + dept + '/program/' + id , true);

    xhr.onload = function() {
        if (this.status == 200) {

            let subjects = JSON.parse(this.responseText);
    
        output = `<div id="subjects-list" style="max-height: 25vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">`;
        output = `<ul class="list-group mt-2">`;

            for(let i in subjects){

                output+='<li role="button" id="subj-'+ subjects[i].id +'" onclick="subjectSelect('+ subjects[i].id +')" class="subject-button list-group-item list-group-item-action">'+ subjects[i].desc +'</li>';

            }    

        output +=`</ul>`;       
        output +=`</div>`;   
        

        subjectsList.innerHTML = output;



        }
    }

    xhr.send();

}


function subjectSelect(subjid){    

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

            let output = `<div id="view-panel" class="col-sm d-flex">`;
    
            for(let i in classes){

                let counter = 1;
                
                if(i % 2 == 0){

                    output+= `<div class="card text-white bg-success m-2" style="min-width: 18rem; max-width: 18rem;">
                                <div class="card-header">`+ classes[i].class_name +`</div>
                                <div class="card-body">
                                <h5 class="card-title text-white">`+classes[i].faculty_name +`</h5>`;
                                    classes[i].schedules.forEach(sched => {

                                        output+=`<ul class="list-group bg-success list-group-flush text-center">`;
                                            output+=`<li class="list-group-item list-group-item-success">`+ sched.day_name +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-success">`+ sched.formatted_start +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-success">`+ sched.formatted_until +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-success">`+ sched.room_name +`</li>`;
                                            output+=`<li class="list-group-item list-group-item-success"><button class="btn btn-light">Edit this Sched</button></li>`;
                                        output+=`</ul> 
                                            <hr>
                                        `;
                                        ++counter;
                                    });
                        output+=`</div>
                            </div>`;

                }else{
                    output+= `<div class="card text-secondary bg-warning m-2" style="min-width: 18rem; max-width: 18rem;">
                                <div class="card-header">`+ classes[i].class_name +`</div>
                                <div class="card-body">
                                <h5 class="card-title">`+classes[i].faculty_name +`</h5>`;

                                classes[i].schedules.forEach(sched => {

                                    output+=`<ul class="list-group bg-warning list-group-flush text-center">`;
                                        output+=`<li class="list-group-item list-group-item-warning">`+ sched.day_name +`</li>`;
                                        output+=`<li class="list-group-item list-group-item-warning">`+ sched.formatted_start +`</li>`;
                                        output+=`<li class="list-group-item list-group-item-warning">`+ sched.formatted_until +`</li>`;
                                        output+=`<li class="list-group-item list-group-item-warning">`+ sched.room_name +`</li>`;
                                        output+=`<li class="list-group-item list-group-item-warning"><button class="btn btn-light">Edit this Sched</button></li>`;
                                        
                                    output+=`</ul> 
                                        <hr>
                                    `;
                                    ++counter;
                                    });
                                    
                    output+=`</div>
                            </div> `;

                }

            }

            output+=`</div>`;


            viewPanel.innerHTML = output;


        }
    }

    xhr.send();

}

</script>



