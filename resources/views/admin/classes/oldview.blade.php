

<div class="row " >

    <div class="col-sm-5">
        
        <div class="form-group">

            <label class="h5" for="exampleFormControlSelect1">Subject</label>

            <select class="form-control form-control-lg bg-dark text-white" id="selectViewSubj">
            
            </select>

        </div>

        <div class="form-group">                                    
            {{Form::select('dept', 
              ['0' => 'Senior High School Students',                              
              '1' => 'College Students'], 0,
              ['class' => 'custom-select border-dark', 'id' => 'selectViewDept'])}}                   
        </div>   

        <div class="form-group">                            
            {{Form::select('prog', 
            [], null,
            ['class' => 'custom-select border-dark', 'id' => 'selectViewProg'])}}          
        </div>   
    
    </div>

    <div class="col-sm-7" id="second-column">        
    
    </div>

</div>


<div class="row " >    
    
    <div class="col-sm-5">

       

        

    </div>

</div>

<script>

let selectViewSubj = document.getElementById('selectViewSubj');
let selectViewDept = document.getElementById('selectViewDept');
let selectViewProg = document.getElementById('selectViewProg');
let secondColumn = document.getElementById('second-column');


selectViewDept.addEventListener('change', () => {
    changeViewSelects();
});

selectViewProg.addEventListener('change', () => {
    changeViewSelects(true);
});

selectViewSubj.addEventListener('change', () => {
    viewSchedules();
});

function changeViewSelects($isSelectProg = false){

    removeAllOptions(selectViewSubj);    

    dept = selectViewDept.value;
    prog = selectViewProg.value;    

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/programs/department/' + dept + '/', true);

    xhr.onload = function() {
        if (this.status == 200) { 

            if(!$isSelectProg){
                for(i = 0; i < selectViewProg.length; i++){
                    selectViewProg.remove(i);
                }

                var programs = JSON.parse(this.responseText);                                

                for (let i in programs) {                                        
                    selectViewProg.options[i] = new Option(programs[i].abbrv + ' - ' + programs[i].desc, programs[i].id); 
                }

                changeViewSubjects();

            } else {
                changeViewSubjects();
            }

        } else {
        
        }                

    }

    xhr.send(); 
}

function changeViewSubjects(){
    dept = selectViewDept.value;
    prog = selectViewProg.value;
        
    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/subjects/department/' + dept + '/program/' + prog, true);

    xhr.onload = function() {
        if (this.status == 200) { 

            for(i = 0; i < selectViewSubj.length; i++){
                selectViewSubj.remove(i);
            }

            var subjects = JSON.parse(this.responseText);                                

            for (let i in subjects) {                                        
                selectViewSubj.options[i] = new Option(subjects[i].code + ' - ' + subjects[i].desc, subjects[i].id); 
            }

            viewSchedules();           

        } else {

        }       

    }

    xhr.send(); 

}

function viewSchedules(){

    secondColumn.innerHTML = '';
    
    prog = selectViewProg.value;
    subj = selectViewSubj.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/schedules/' + prog + '/' + subj, true);

    xhr.onload = function() {
        if (this.status == 200) {          
            
            let classes = JSON.parse(this.responseText);

            let output = `<div class="col-sm-7" id="second-column">`;

            for(let i in classes){
                
            output += `<div id="sched-`+ classes[i].id +`" class="card bg-light mb-3 sched-card" >
                      <div class="card-header">` + selectViewSubj.options[selectViewSubj.selectedIndex].textContent + `[`+ classes[i].class_name +`]</div>
                         <div class="card-body">
                             <h5 class="card-title">Instructor `+ classes[i].faculty_name + `</h5>
                                <table class="table">
                             
                             `;
                            classes[i].schedules.forEach(sched => {

                            output+=`
                                    <tr>
                                        <td>`+ sched.day_name +`</td>
                                        <td>`+ sched.formatted_start  +` - `+ sched.formatted_until +`</td>
                                        <td>`+ sched.room_name +`</td>                                                                                                  
                                    </tr>
                                    
                                `;                                
                            });

                             
                output+=`</table>
                        </div>
                    
                </div>

                     `;             
            } 

            output += `</div>`;

            secondColumn.innerHTML = output;     

        } else {
            secondColumn.innerHTML = `<p> no classes available </p>`
        }       

    }

    xhr.send(); 

}




function removeAllOptions(select){

    for(i =  select.options.length; i >= 0 ; i--){
        select.remove(i);
    }
}


</script>

  