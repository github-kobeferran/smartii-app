 <div class="form-group">    
    <input id="admin-search" type="text" class="form-control" placeholder="Search here..">
</div>




<div>    
    <div class="loader-parent">
        <div class="dual-ring" style=""></div>            
    </div> 
    <div class="table-responsive">
        <table class="table table-striped ">
            <thead class="bg-dark text-white">
                <tr>
                    <th scope="col">Admin ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Position</th>
                </tr>
            </thead>
            <tbody id="admins-table">
    
            </tbody>                         
        </table>    
        <div id="admin-modals">

        </div>  
             
    </div>             
             
    </div>            
</div>




 

<script>    

document.getElementById('admins-view-tab').addEventListener('click', () => {
    viewAdmins();    
});

viewAdmins();

function viewAdmins(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/adminswithtrashed', true);

    xhr.onload = function() {
        if (this.status == 200) {

            let admins = JSON.parse(this.responseText);

            let output = '<tbody id="admins-table">';
            let modals = ` <div id="admin-modals">`;

            for (let i in admins) {                
                output += `<tr class="${admins[i].is_trashed ? 'text-muted' : ``}">` +
                                '<th scope="row">' + admins[i].admin_id + '</th>' +
                                '<td>' + admins[i].name + '</td>' +
                                '<td>' + admins[i].email + '</td>';
                    output+= `<td`;

                        if(admins[i].position == 'superadmin')
                            output+= ` style="background: #b3dbff;"`;
                        else if(admins[i].position == 'registrar')
                            output+= ` style="background: #ccffdd;"`;
                        else
                            output+= ` style="background: #ffe6b3;"`;

                        output+=`>${admins[i].position} `;

                    if(admins[i].is_trashed){
                        output += `<button type="button" data-toggle="modal" data-target="#admin-restore-${admins[i].id}" class="badge badge-pill badge-secondary"> 
                                        Restore 
                                        <i class="fa fa-recycle" aria-hidden="true"></i>
                                   </button>`;

                        modals += `<div class="modal fade" id="admin-restore-${admins[i].id}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">RESTORE ${admins[i].name}</span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                {!!Form::open(['url' => '/restoreadmin'])!!}
                                                <div class="modal-body text-justify">
                                                    <h5><span class="text-info">YOU ARE ABOUT TO RESTORE <u>${admins[i].name}</u></span></h5>
                                                    <input type="hidden" value="${admins[i].id}" name="id">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-info text-white">Restore ${admins[i].name} <i class="fa fa-recycle" aria-hidden="true"></i></button>
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                </div>
                                                {!!Form::close()!!}
                                            </div>
                                        </div>
                                    </div>`;
                    }
                    
                    output+=`</td>
                </tr>`;
            }

            output += '</tbody>';
            modals += `</div>`;

            document.getElementById('admins-table').innerHTML = output;
            document.getElementById('admin-modals').innerHTML = modals;

        } else if (this.status == 404) {
            let output = 'not found...';
            document.getElementById('admins-table').innerHTML = output;
        }
    }

    xhr.send();
}

document.querySelector('#admin-search').addEventListener('keyup', (e) => {


txt = document.querySelector('#admin-search').value;

let xhr = new XMLHttpRequest();

xhr.open('GET', APP_URL + '/admin/search/admins/' + txt, true);

document.getElementsByClassName('dual-ring')[0].style.display = 'inline-block';

xhr.onload = function() {

    if (this.status == 200) {

        let admins = JSON.parse(this.responseText);

        let output = '<tbody id="admins-table">';
            let modals = ` <div id="admin-modals">`;

            for (let i in admins) {                
                output += `<tr class="${admins[i].is_trashed ? 'text-muted' : ``}">` +
                                '<th scope="row">' + admins[i].admin_id + '</th>' +
                                '<td>' + admins[i].name + '</td>' +
                                '<td>' + admins[i].email + '</td>';
                    output+= `<td`;

                        if(admins[i].position == 'superadmin')
                            output+= ` style="background: #b3dbff;"`;
                        else if(admins[i].position == 'registrar')
                            output+= ` style="background: #ccffdd;"`;
                        else
                            output+= ` style="background: #ffe6b3;"`;

                        output+=`>${admins[i].position} `;

                    if(admins[i].is_trashed){
                        output += `<button type="button" data-toggle="modal" data-target="#admin-restore-${admins[i].id}" class="badge badge-pill badge-secondary"> 
                                        Restore 
                                        <i class="fa fa-recycle" aria-hidden="true"></i>
                                   </button>`;

                        modals += `<div class="modal fade" id="admin-restore-${admins[i].id}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info">
                                                    <h5 class="modal-title" id="exampleModalLongTitle"><span class="text-white">RESTORE ${admins[i].name}</span></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                {!!Form::open(['url' => '/restoreadmin'])!!}
                                                <div class="modal-body text-justify">
                                                    <h5><span class="text-info">YOU ARE ABOUT TO RESTORE <u>${admins[i].name}</u></span></h5>
                                                    <input type="hidden" value="${admins[i].id}" name="id">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-info text-white">Restore ${admins[i].name} <i class="fa fa-recycle" aria-hidden="true"></i></button>
                                                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                </div>
                                                {!!Form::close()!!}
                                            </div>
                                        </div>
                                    </div>`;
                    }
                    
                    output+=`</td>
                </tr>`;
            }

            output += '</tbody>';
            modals += `</div>`;

            document.getElementsByClassName('dual-ring')[0].style.display = 'none';
            document.getElementById('admins-table').innerHTML = output;
            document.getElementById('admin-modals').innerHTML = modals;


    } else if (this.status == 404) {
        let output = 'not found';
        document.getElementById('admins-table').innerHTML = output;
    }
}

xhr.send();

});


</script>



