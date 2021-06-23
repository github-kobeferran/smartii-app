

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

        <div class ="table-responsive border shadow bg-light" style="max-height: 500px; overflow: auto; display:inline-block;">
            <table class="table table-striped bg-light table-responsive-sm border" >
                <thead class="thead bg-light">
                    <tr>                        
                        <th class="bg-light" scope="col">Room Name</th>
                        <th class="bg-light" scope="col">Status</th>
                        <th class="bg-light" scope="col" colspan="2">Action</th>
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
                                    <tr>
                                        <a href="{{url('editsched/`+ sched.id +`')}}" class="float-right btn btn-light btn-block border border-dark" >Edit Schedule</a>
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

  