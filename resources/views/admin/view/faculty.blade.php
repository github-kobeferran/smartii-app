<div class="form-group">    
    <input id="faculty-search" type="text" class="form-control" placeholder="Search Name here..">
</div>


<div >    
    <div class=" table-responsive" id="faculty-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;">
        <table  class="table table-striped table-bordered" >
            <thead>
                <tr>
                    <th class="bg-light" scope="col">Faculty ID</th>
                    <th  class="bg-light"  scope="col">Name</th>
                    <th  class="bg-light"  scope="col">More Details</th>            
                </tr>
            </thead>
            <tbody id="faculty-table">

            </tbody>
            
            <div class="loader-parent">
                <div class="dual-ring" style=""></div>            
            </div>  
        </table>           
    
    </div>
</div>

 

<script>  

let facultySearch = document.getElementById('faculty-search');

document.getElementById('faculty-view-tab').addEventListener('click', () => {
    viewFaculty();    
});

function viewFaculty(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/faculty', true);

    xhr.onload = function() {
        if (this.status == 200) {

            let faculty = JSON.parse(this.responseText);

            output = '<tbody id="faculty-table">';

            for (let i in faculty) {
                output += '<tr>' +
                    '<th scope="row">' + faculty[i].faculty_id + '</th>' +
                    '<td>' + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + ', ' + ucfirst(faculty[i].middle_name) +'</td>' +                  
                    '<td><button data-toggle="modal" data-target="#modal' + faculty[i].faculty_id + `" class="btn ${Object.keys(faculty[i].active_classes).length  > 0 ? `btn-success`: `btn-info`} text-white"> Show </buton></td>` +                  
                '</tr>'; 

        output += `<div class="modal fade" id="modal`+ faculty[i].faculty_id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        output+=`<div class="text-right">
                                <span class="badge badge-success my-1"> Active Classes: ${Object.keys(faculty[i].active_classes).length} </span>
                                <br>
                                <b><span class=" d-block" id="message-${faculty[i].id}"> </span></b>
                                <div id="spinner-${faculty[i].id}" class="spinner-border text-warning d-none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <button id="btn-reminder-${faculty[i].id}" class="badge badge-warning my-1 text-dark my-1" onclick="remindToArchive(${faculty[i].id})">Remind to archive remaining classes.</button>
                            </div>`;
                    }

                    output+=`<ul class="list-group">
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
                                    <div class="float-right">${faculty[i].specialty}</div>
                                </li>
                            </ul>                                          
                        </div>
                       
                        </div>
                    </div>
                </div>`;  
            }

            output += '</tbody>';         

            document.getElementById('faculty-table').innerHTML = output;

        } else if (this.status == 404) {
            let output = 'not found...';
            document.getElementById('faculty-table').innerHTML = output;
        }
    }

    xhr.send();
}

facultySearch.addEventListener('keyup', searchFaculty);

function searchFaculty(){

    let xhr = new XMLHttpRequest();

    xhr.open('GET', APP_URL + '/admin/search/faculty/' + facultySearch.value, true);

    xhr.onload = function() {
        if (this.status == 200) {

            let faculty = JSON.parse(this.responseText);

            console.log(faculty);

            output = '<tbody id="faculty-table">';

                for (let i in faculty) {
                
                output += '<tr>' +
                              '<th scope="row">' + faculty[i].faculty_id + '</th>' +
                              '<td>' + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + ', ' + ucfirst(faculty[i].middle_name) +'</td>' +                  
                              '<td><button data-toggle="modal" data-target="#modal' + faculty[i].faculty_id + '" class="btn btn-info text-white"> Show </buton></td>' +                  
                          '</tr>';
          
              output += `<div class="modal fade" id="modal`+ faculty[i].faculty_id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                  <div class="modal-header bg-info">
                                      <h5 class="modal-title" id="exampleModalLabel"><span class="text-white"> INSTRUCTOR ` + faculty[i].last_name + ', ' + faculty[i].first_name + `</span></h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body"> `;

                            if(Object.keys(faculty[i].active_classes).length  > 0){
                                output+=`<div class="text-right">                                                                             
                                        <span class="badge badge-success my-1"> Active Classes: ${Object.keys(faculty[i].active_classes).length} </span>
                                        <br>
                                        <b><span class=" d-block" id="message-${faculty[i].id}"> </span></b>
                                        <div id="spinner-${faculty[i].id}" class="spinner-border text-warning d-none" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <button id="btn-reminder-${faculty[i].id}" class="badge badge-warning my-1 text-dark my-1" onclick="remindToArchive(${faculty[i].id})">Remind to archive remaining classes.</button>
                                    </div>`;
                            }

                            output+=` <ul class="list-group">
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
                                              <div class="float-right">${faculty[i].specialty}</div>
                                          </li>
                                      </ul>                                          
                                  </div>
                                 
                                  </div>
                              </div>
                          </div>`;
                      
                      }
                      
            output += '</tbody>' +
                '</table>';

        document.getElementById('faculty-table').innerHTML = output;

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

</script>



