@include('inc.messages')

<div class="form-group">    
    <input id="faculty-search" type="text" class="form-control" placeholder="Search Name here..">
</div>

<div >    
    <div>
        <div class="text-center">
            <div id="spinner-grow" class="spinner-grow d-none text-success" role="status">
                <span class="sr-only">Loading...</span>
              </div>
        </div>
    </div>
    <div class=" table-responsive" id="faculty-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">
        <table  class="table table-striped table-bordered" >
            <thead style="background: #ccffcc;">
                <tr>
                    <th scope="col">Faculty ID</th>
                    <th scope="col">Name</th>
                    <th  scope="col">More Details</th>            
                </tr>
            </thead>
            <tbody id="faculty-table">

            </tbody>
            
            <div class="loader-parent">
                <div class="dual-ring" style=""></div>            
            </div>  
        </table>   
        
        <div id="modals">

        </div>
    
    </div>
</div>

<script>  

let facultySearch = document.getElementById('faculty-search');
let spinner_grow = document.getElementById('spinner-grow');

facultySearch.addEventListener('keyup', searchFaculty);

document.getElementById('faculty-view-tab').addEventListener('click', function() {
    viewFaculty(this);
});

function viewFaculty(btn){      
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/facultywithtrashed', true);

    spinner_grow.classList.remove('d-none');
    btn.style.pointerEvents = "none";
  
    xhr.onload = async function() {
        if (this.status == 200) {          

            let faculty = JSON.parse(this.responseText);

            let output = '<tbody id="faculty-table">';
            let modals = `<div id="modals">`;

            for (let i in faculty) {
                output += `<tr class="${faculty[i].is_trashed ? 'text-muted': '' }">` +
                    '<th scope="row">' + faculty[i].faculty_id + '</th>' +
                    '<td>' + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + ', ' + ucfirst(faculty[i].middle_name) +'</td>';

                    if(faculty[i].is_trashed)
                        output += `<td><button data-toggle="modal" data-target="#restore-${faculty[i].id}" class="btn btn-secondary text-white"> Restore <i class="fa fa-recycle" aria-hidden="true"></i> </buton></td>`;
                    else
                        output +=  '<td><button data-toggle="modal" data-target="#modal' + faculty[i].faculty_id + `" class="btn ${Object.keys(faculty[i].active_classes).length  > 0 ? `btn-success`: `btn-info`} text-white"> Show </buton></td>`;                  

                output+= '</tr>';     

                modals += `<div class="modal fade" id="modal`+ faculty[i].faculty_id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="exampleModalLabel"><span class="text-white"> INSTRUCTOR ` + faculty[i].last_name + ', ' + faculty[i].first_name + `</span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">   `;

                    if(Object.keys(faculty[i].active_classes).length  > 0){
                        modals+=`<div class="text-right">
                                <span class="badge badge-success my-1"> Active Classes: ${Object.keys(faculty[i].active_classes).length} </span>
                                <br>
                                <b><span class=" d-block" id="message-${faculty[i].id}"> </span></b>
                                <div id="spinner-${faculty[i].id}" class="spinner-border text-warning d-none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <button id="btn-reminder-${faculty[i].id}" class="badge badge-warning my-1 text-dark my-1" onclick="remindToArchive(${faculty[i].id})"><i class="fa fa-bell" aria-hidden="true"></i> Remind to archive remaining classes.</button>
                            </div>`;
                    }

                    modals+=`<ul class="list-group">
                                <li class="list-group-item">                                    
                                    <div class="float-left">Email</div>
                                    <div class="float-right">${faculty[i].email}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">Contact</div>
                                    <div class="float-right">${(faculty[i].contact == null ? '--' :  faculty[i].contact)}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">Sex</div>
                                    <div class="float-right">${(faculty[i].gender == null ? '--' :  faculty[i].gender)}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">PROGRAM</div>
                                    <div class="float-right">${faculty[i].specialty} <i role="button" onclick="showChangeProgramPanel(${faculty[i].id})" class="text-info fa fa-pencil-square-o" aria-hidden="true"> </i></div>
                                </li>
                            </ul>    
                            <div class="text-center d-none" id="change-specialty-panel-${faculty[i].id}">
                                {!!Form::open(['url' => '/changefacultyspecialty']) !!}
                                    <label>Change to: </label>`;


                            const res = await fetch(`${APP_URL}/admin/view/programs`);
                            const programs = await res.json();
                            

                            modals += `<select name="prog" class="form-control">
                                    <option value="" ${faculty[i].program_id == null? `selected` : `` }>All Programs</option>`;
                            for(let j in programs){
                                modals += `<option value="${programs[j].id}" ${faculty[i].program_id == programs[j].id ? 'selected' : ''}> ${programs[j].abbrv} - ${programs[j].desc} </option>`;
                            }


                            modals += `</select>
                                <input type="hidden" name="id" value="${faculty[i].id}">
                                <button type="submit" class="btn btn-info my-1">Save</button>
                                <button type="button" onclick="hideChangePanel(${faculty[i].id})" class="btn btn-light my-1">Cancel</button>
                             {!!Form::close()!!}
                            </div>
                        </div>
                       
                        </div>
                    </div>
                </div>
                
                <div class="modal fade" id="restore-${faculty[i].id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">Restore ${faculty[i].first_name} ${faculty[i].last_name}?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            {!!Form::open(['url' => '/restorefaculty']) !!}
                                <div class="modal-body text-justify">
                                    <h5><span class="text-white">Restoring ${faculty[i].first_name} ${faculty[i].last_name}</span></h5>
                                    <p>Restoring this instructor will make his/her available for schedules again.</p>
                                    <p>Continue?</p>
                                    <input type="hidden" name="id" value="${faculty[i].id}">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Yes, restore ${faculty[i].first_name} ${faculty[i].last_name} SMARTII Account</button>
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                </div>
                            {!!Form::close()!!}
                        </div>
                    </div>
                </div>
                `;  
            }

            output += '</tbody>';                             
            modals += '</div>';                             
            

            spinner_grow.classList.add('d-none');
            btn.style.pointerEvents = "auto";
            
            document.getElementById('faculty-table').innerHTML = output;
            document.getElementById('modals').innerHTML = modals;

        } else if (this.status == 404) {
            let output = 'not found...';
            document.getElementById('faculty-table').innerHTML = output;
        }
    }

    xhr.send();
}


function searchFaculty(){ 

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/search/faculty/' + facultySearch.value, true);

    spinner_grow.classList.remove('d-none');

    xhr.onload = async function() {
        if (this.status == 200) {

            let faculty = JSON.parse(this.responseText);            

            let output = '<tbody id="faculty-table">';
            let modals = `<div id="modals">`;

            for (let i in faculty) {
                output += '<tr>' +
                    '<th scope="row">' + faculty[i].faculty_id + '</th>' +
                    '<td>' + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + ', ' + ucfirst(faculty[i].middle_name) +'</td>' +                  
                    '<td><button data-toggle="modal" data-target="#modal' + faculty[i].faculty_id + `" class="btn ${Object.keys(faculty[i].active_classes).length  > 0 ? `btn-success`: `btn-info`} text-white"> Show </buton></td>` +                  
                '</tr>';     

                modals += `<div class="modal fade" id="modal`+ faculty[i].faculty_id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="exampleModalLabel"><span class="text-white"> INSTRUCTOR ` + faculty[i].last_name + ', ' + faculty[i].first_name + `</span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">   `;

                    if(Object.keys(faculty[i].active_classes).length  > 0){
                        modals+=`<div class="text-right">
                                <span class="badge badge-success my-1"> Active Classes: ${Object.keys(faculty[i].active_classes).length} </span>
                                <br>
                                <b><span class=" d-block" id="message-${faculty[i].id}"> </span></b>
                                <div id="spinner-${faculty[i].id}" class="spinner-border text-warning d-none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <button id="btn-reminder-${faculty[i].id}" class="badge badge-warning my-1 text-dark my-1" onclick="remindToArchive(${faculty[i].id})"><i class="fa fa-bell" aria-hidden="true"></i> Remind to archive remaining classes.</button>
                            </div>`;
                    }

                    modals+=`<ul class="list-group">
                                <li class="list-group-item">                                    
                                    <div class="float-left">Email</div>
                                    <div class="float-right">${faculty[i].email}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">Contact</div>
                                    <div class="float-right">${(faculty[i].contact == null ? '--' :  faculty[i].contact)}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">Sex</div>
                                    <div class="float-right">${(faculty[i].gender == null ? '--' :  faculty[i].gender)}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="float-left">PROGRAM</div>
                                    <div class="float-right">${faculty[i].specialty} <i role="button" onclick="showChangeProgramPanel(${faculty[i].id})" class="text-info fa fa-pencil-square-o" aria-hidden="true"> </i></div>
                                </li>
                            </ul>    
                            <div class="text-center d-none" id="change-specialty-panel-${faculty[i].id}">
                                {!!Form::open(['url' => '/changefacultyspecialty']) !!}
                                    <label>Change to: </label>`;


                            const res = await fetch(`${APP_URL}/admin/view/programs`);
                            const programs = await res.json();
                            

                            modals += `<select name="prog" class="form-control">
                                    <option value="" ${faculty[i].program_id == null? `selected` : `` }>All Programs</option>`;
                            for(let j in programs){
                                modals += `<option value="${programs[j].id}" ${faculty[i].program_id == programs[j].id ? 'selected' : ''}> ${programs[j].abbrv} - ${programs[j].desc} </option>`;
                            }


                            modals += `</select>
                                <input type="hidden" name="id" value="${faculty[i].id}">
                                <button type="submit" class="btn btn-info my-1">Save</button>
                                <button type="button" onclick="hideChangePanel(${faculty[i].id})" class="btn btn-light my-1">Cancel</button>
                             {!!Form::close()!!}
                            </div>
                        </div>
                       
                        </div>
                    </div>
                </div>`;  
            }

            output += '</tbody>';                             
            modals += '</div>';        

        spinner_grow.classList.add('d-none');        

        document.getElementById('faculty-table').innerHTML = output;
        document.getElementById('modals').innerHTML = modals;

        }
    }

    xhr.send();
}

async function remindToArchive(id){
    let message = document.getElementById('message-' + id);
    let btn = document.getElementById('btn-reminder-' + id);
    let spinner = document.getElementById('spinner-' + id);    

    spinner.classList.remove('d-none');
    btn.classList.add('d-none');

    const res = await fetch(`${APP_URL}/remindtoarchive/${id}`);
    const data = await res.json();

    let output = ``;
    if(data > 0)
        output = `<span class="text-success" id="message-${id}">Reminder Sent!</span>`;
    else
        output = `<span class="text-danger" id="message-${id}">Error Sending Reminder</span>`;

    spinner.classList.add('d-none');
    btn.classList.remove('d-none');
    message.innerHTML = output;
}

function showChangeProgramPanel(id) {
    let panel = document.getElementById('change-specialty-panel-' + id);
    panel.classList.remove('d-none');
}

function hideChangePanel(id){
    let panel = document.getElementById('change-specialty-panel-' + id);
    panel.classList.add('d-none');
}

</script>



