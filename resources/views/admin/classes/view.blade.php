

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
    
    <div class="col-sm-6">

        <h4 class="mb-3">Add a Room</h4>

        {!! Form::open(['url' => 'admin/create/room','id' => 'roomForm']) !!}

        <div class="input-group w-50 mb-3">

            <input id="room-name" name="room_name" type="text" class="form-control" placeholder="Room name" aria-describedby="basic-addon2" required>

            <div class="input-group-append">
              <button type="submit" id="add-room" data-toggle="tooltip" data-placement="right" title="Add" class="btn btn-outline-success" ><i class="fa fa-plus" aria-hidden="true"></i></button>
            </div>

        </div>                

        {!! Form::close() !!}

        {!! Form::open(['url' => 'admin/update/room', 'id' => 'updateRoomForm']) !!}

        <div class ="table-responsive border shadow" style="max-height: 500px; overflow: auto; display:inline-block;">
            <table class="table table-striped table-responsive-sm border" >
                <thead class="thead bg-light">
                    <tr>                        
                        <th scope="col">Room Name</th>
                        <th scope="col">Status</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead> 
                <tbody id="rooms-table" >
                    
                </tbody>                        
            </table>
        </div>

        {!! Form::close() !!}

        

    </div>

</div>

<script>

let selectViewSubj = document.getElementById('selectViewSubj');
let selectViewDept = document.getElementById('selectViewDept');
let selectViewProg = document.getElementById('selectViewProg');
let secondColumn = document.getElementById('second-column');
let roomsTable = document.getElementById('rooms-table');

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

            let row = document.createElement("DIV");
            row.className = "row m-2";                              

            for (let i in schedules[0]) {                                        

                let divCard = document.createElement("DIV");
                                
                divCard.className = "card bg-light border-warning mb-3 shadow-lg";
                divCard.style.maxWidth = "18rem";

                let cardHeader = document.createElement("DIV");

                cardHeader.className = "card-header";
                cardHeader.textContent = selectViewSubj.options[selectViewSubj.selectedIndex].textContent;

                let cardBody = document.createElement("DIV");
                cardBody.className = "card-body";

                let cardTitle = document.createElement("H5");
                cardTitle.className = "card-title border bg-warning h4";
                cardTitle.textContent = "Instructor: " + schedules[0][i].faculty_name;   

                let day = document.createElement("P");
                day.className = "card-text h5";

                let dayText = "";

                switch(schedules[0][i].day){
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
                from.className = "card-text h5";
                from.textContent = "from: " + schedules[0][i].start_time;   

                let until = document.createElement("P");
                until.className = "card-text h5";
                until.textContent = "until: " + schedules[0][i].until;   

                let room = document.createElement("P");
                room.className = "card-text h5";
                room.textContent = "at: " + schedules[0][i].room_name;  

                cardBody.appendChild(cardTitle);
                cardBody.appendChild(day);
                cardBody.appendChild(from);
                cardBody.appendChild(until);
                cardBody.appendChild(room);

                divCard.appendChild(cardHeader);
                divCard.appendChild(cardBody);

            
                row.appendChild(divCard);                
            } 

            secondColumn.appendChild(row);       

        } else {
            secondColumn.innerHTML = `<p> no classes available </p>`
        }       

    }

    xhr.send(); 

}

function fillRoomTable(){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/rooms', true);

    xhr.onload = function() {

        if (this.status == 200) {

            let rooms = JSON.parse(this.responseText);

            output = '<tbody id="rooms-table">';

            for(let i in rooms){
                output+= '<tr id="room-row-' + rooms[i].id + '">';
                output+= '<input id="room-hidden"  type="hidden" value="' + rooms[i].id + '">';
                output+= '<td id="room-name" >' + rooms[i].name + '</td>';

                if(rooms[i].enable == 1)
                    output+= '<td>Enabled</td>';
                else
                    output+= '<td>Disabled</td>';

                output+= `<td id="button-`+ rooms[i].id + `"><button onclick="changeToEdit(document.getElementById('room-row-` + rooms[i].id + `'), document.getElementById('button-` + rooms[i].id + `'))" type="button" class="btn btn-info text-white">Edit</button></td>`;
                output+= `<td><a class="btn btn-info text-white" href="/admin/delete/room/`+ rooms[i].id+`">Delete</a></td>`;
                output+= '</tr>';
            }  

            output+= '</tbody>';

            roomsTable.innerHTML = output;

        } 

    }

    xhr.send();

}

function changeToEdit(roomRow, source){    
    
    clickedButton = source;

    roomID = roomRow.children[0].value;
    roomName = roomRow.children['room-name'];
    roomNameText = roomRow.children['room-name'].textContent;
    hiddenInput = roomRow.children['room-hidden'];

    hiddenInput.name = "room_id";
    output = '<td id="room-name"><input type="text" name="room_update_name" class="form-control-sm" value="' + roomNameText + '" required/><button type="submit" data-toggle="tooltip" data-placement="right" title="Update" class="btn btn-sm btn-outline-primary" ><i class="fa fa-check" aria-hidden="true"></i></button></td>';       
    btnOutput = `<td id="button-`+ clickedButton.id + `" ><button onclick="cancelEdit(document.getElementById('`+ roomRow.id +`'))" type="button" class="btn btn-primary text-white">Cancel</button></td>`;       

    roomName.innerHTML = output;
    source.innerHTML = btnOutput;

}

function cancelEdit(row){

    row.children[0].name = '';
      
    row.children[1].innerHTML = '<td id="room-name" >' + row.children[1].children[0].value + '</td>';
    row.children[3].innerHTML = '<td id="'+ row.children[3].id + '"><button onclick="changeToEdit(document.getElementById(\''+ row.id +'\'), document.getElementById(\'' + row.children[3].id + '\'))" type="button" class="btn btn-info text-white">Edit</button></td>';   

}

function removeAllOptions(select){

    for(i =  select.options.length; i >= 0 ; i--){
        select.remove(i);
    }
}

</script>