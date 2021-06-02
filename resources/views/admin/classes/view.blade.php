

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
            
            let schedules = JSON.parse(this.responseText);                                

            for (let i in schedules) {                                        

                let divCard = document.createElement("DIV");
                                
                divCard.className = "card bg-light mb-3";
                divCard.style.maxWidth = "18rem";

                let cardHeader = document.createElement("DIV");

                cardHeader.className = "card-header";
                cardHeader.textContent = selectViewSubj.options[selectViewSubj.selectedIndex].textContent;

                let cardBody = document.createElement("DIV");
                cardBody.className = "card-body";

                let cardTitle = document.createElement("H5");
                cardTitle.className = "card-title";
                cardTitle.textContent = "Instructor: " + schedules[i][0].faculty_name;   

                let day = document.createElement("P");
                day.className = "card-text";

                let dayText = "";

                switch(schedules[i][0].day){
                    case 'mon':
                        dayText = "Monday";
                    break;
                    case 'tue':
                        dayText = "Tuesday";
                    break;
                    case 'wed':
                        dayText = "Wednesday";
                    break;
                    case 'thu':
                        dayText = "Thursday";
                    break;
                    case 'fri':
                        dayText = "Friday";
                    break;
                    case 'sat':
                        dayText = "Saturday";
                    break;                 
                }

                day.textContent = "every: " + dayText;

                let from = document.createElement("P");
                from.className = "card-text";
                from.textContent = "from: " + schedules[i][0].from;   

                let until = document.createElement("P");
                until.className = "card-text";
                until.textContent = "until: " + schedules[i][0].until;   

                let room = document.createElement("P");
                room.className = "card-text";
                room.textContent = "at: " + schedules[i][0].room_name;  

                cardBody.appendChild(cardTitle);
                cardBody.appendChild(day);
                cardBody.appendChild(from);
                cardBody.appendChild(until);
                cardBody.appendChild(room);

                divCard.appendChild(cardHeader);
                divCard.appendChild(cardBody);

                secondColumn.appendChild(divCard);
            }        

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