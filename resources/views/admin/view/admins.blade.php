 <div class="form-group">    
    <input id="admin-search" type="text" class="form-control" placeholder="Search here..">
</div>


<div >    
    <div class="table-responsive">
        <table  class="table table-striped table table-responsive-sm" >
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
            
            <div class="loader-parent">
                <div class="dual-ring" style=""></div>            
            </div>  
        </table>   </div>        
</div>

 

<script>    

document.getElementById('admins-view-tab').addEventListener('click', () => {
    viewAdmins();

    
});

function viewAdmins(){
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/admins', true);

    xhr.onload = function() {
        if (this.status == 200) {

            let admins = JSON.parse(this.responseText);

            output = '<tbody id="admins-table">';

            for (let i in admins) {
                output += `<tr>` +
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

                    output+=`>${admins[i].position}</td>` +
                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

            document.getElementById('admins-table').innerHTML = output;

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

        output = '<tbody id="admins-table">';

        for (let i in admins) {
            output += '<tr>' +
                '<th scope="row">' + admins[i].admin_id + '</th>' +
                '<td>' + admins[i].name + '</td>' +
                '<td>' + admins[i].email + '</td>' +
                '<td>' + admins[i].position + '</td>' +
                '<td><a href=\"admin/delete/' + admins[i].id + ' \" class="btn btn-primary">delete</a></td>' +
                '</tr>';
        }

        output += '</tbody>' +
            '</table>';

        document.getElementsByClassName('dual-ring')[0].style.display = 'none';
        document.getElementById('admins-table').innerHTML = output;


    } else if (this.status == 404) {
        let output = 'not found';
        document.getElementById('admins-table').innerHTML = output;
    }
}

xhr.send();

});


</script>



