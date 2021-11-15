<h4 class="mb-3">Add a Room</h4>

{!! Form::open(['url' => 'admin/create/room','id' => 'roomForm']) !!}

<div class="input-group w-50 mb-3">

    <input id="room-name" name="room_name" type="text" class="form-control" placeholder="Room name" aria-describedby="basic-addon2" required>

    <div class="input-group-append"> 

      <button type="submit" id="add-room" data-toggle="tooltip" data-placement="right" title="Add" class="btn btn-outline-success" ><i class="fa fa-plus" aria-hidden="true"></i></button>

    </div>

</div>                

{!! Form::close() !!}

<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" id="basic-addon1"><i class="fa fa-search" aria-hidden="true"></i></span>
    </div>
    <input id="roomSearch" type="text" class="form-control" placeholder="Room name" aria-label="Username" aria-describedby="basic-addon1">
  </div>

{!! Form::open(['url' => 'admin/update/room', 'id' => 'updateRoomForm']) !!}

<div class ="table-responsive border shadow bg-light" style="max-height: 500px; overflow: auto; display:inline-block;">
    <table class="table table-striped bg-light table-responsive-sm border" >
        <thead class="thead bg-light">
            <tr>                        
                <th class="bg-light" scope="col">Room Name</th>
                {{-- <th class="bg-light" scope="col">Status</th> --}}
                <th class="bg-light" scope="col" colspan="2">Action</th>
            </tr>
        </thead> 
        <tbody id="rooms-table" >
            
        </tbody>                        
    </table>
</div>

{!! Form::close() !!}

<script>
let roomsTables = document.getElementById('rooms-table');
let roomSearch = document.getElementById('roomSearch');

roomSearch.addEventListener('input', () => {

    searchRoom();

});


function fillRoomTable(){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/view/rooms', true);

    xhr.onload = function() {

        if (this.status == 200) {

            let rooms = JSON.parse(this.responseText);

            output = '<tbody id="rooms-table">';

            for(let i in rooms){
                output+= '<tr id="room-row-' + rooms[i].id + '">';
                output+= '<input id="room-hidden-' + rooms[i].id + '"  type="hidden" value="' + rooms[i].id + '">';
                output+= '<td id="room-name" >' + rooms[i].name + '</td>';

                // if(rooms[i].enable == 1)
                //     output+= '<td>Enabled</td>';
                // else
                //     output+= '<td>Disabled</td>';

                output+= `<td id="button-`+ rooms[i].id + `"><button onclick="changeToEdit(`+  rooms[i].id + `)" type="button" class="btn btn-info text-white">Edit</button></td>`;
                output+= `<td><a class="btn btn-info text-white" href="/admin/delete/room/`+ rooms[i].id+`">Delete</a></td>`;
                output+= '</tr>';
            }  

            output+= '</tbody>';

            roomsTables.innerHTML = output;

        } 

    }

    xhr.send();

}

function searchRoom(){

    let txt = roomSearch.value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/search/rooms/' + txt, true);

    xhr.onload = function() {

        if (this.status == 200) {

            let rooms = JSON.parse(this.responseText);

            output = '<tbody id="rooms-table">';

            for(let i in rooms){
                output+= '<tr id="room-row-' + rooms[i].id + '">';
                output+= '<input id="room-hidden-' + rooms[i].id + '"  type="hidden" value="' + rooms[i].id + '">';
                output+= '<td id="room-name" >' + rooms[i].name + '</td>';

                if(rooms[i].enable == 1)
                    output+= '<td>Enabled</td>';
                else
                    output+= '<td>Disabled</td>';

                output+= `<td id="button-`+ rooms[i].id + `"><button onclick="changeToEdit(`+  rooms[i].id + `)" type="button" class="btn btn-info text-white">Edit</button></td>`;
                output+= `<td><a class="btn btn-info text-white" href="/admin/delete/room/`+ rooms[i].id+`">Delete</a></td>`;
                output+= '</tr>';
            }  

            output+= '</tbody>';

            roomsTables.innerHTML = output;

        } 

    }
    
    xhr.send();

}


function changeToEdit(id){        

    let roomRow = document.getElementById('room-row-' + id);
    source = document.getElementById('button-' + id);

    let oldRow = roomRow;    
    
    clickedButton = source;    

    roomID = roomRow.children[0].value;
    roomName = roomRow.children['room-name'];
    roomNameText = roomRow.children['room-name'].textContent;
    hiddenInput = roomRow.children['room-hidden-' + id];

    hiddenInput.name = "room_id";
    output = '<td id="room-name"><input type="text" name="room_update_name" class="form-control-sm" value="' + roomNameText + '" required/><button type="submit" data-toggle="tooltip" data-placement="right" title="Update" class="btn btn-sm btn-outline-primary" ><i class="fa fa-check" aria-hidden="true"></i></button></td>';       
    btnOutput = `<td id="button-`+ clickedButton.id + `" ><button onclick="cancelEdit(`+ id +`)" type="button" class="btn btn-primary text-white">Cancel</button></td>`;       

    roomName.innerHTML = output;
    source.innerHTML = btnOutput;

}

function cancelEdit(id){

    let row = document.getElementById('room-row-' + id);
        
    row.children[0].name = '';    
      
    row.children[1].innerHTML = '<td id="room-name" >' + row.children[1].children[0].value + '</td>';
    row.children[2].innerHTML = '<td id="'+ row.children[2].id + '"><button onclick="changeToEdit('+ id +')" type="button" class="btn btn-info text-white">Edit</button></td>';   

}


</script>