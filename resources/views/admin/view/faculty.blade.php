<div class="form-group">    
    <input id="faculty-search" type="text" class="form-control" placeholder="Search Name here..">
</div>


<div >    
    <div id="faculty-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">
        <table  class="table table-striped table-bordered table-responsive-sm" >
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
                    '<td><button data-toggle="modal" data-target="#modal' + faculty[i].faculty_id + '" class="btn btn-info text-white"> Show </buton></td>' +                  
                '</tr>';

    output += `<div class="modal fade" id="modal`+ faculty[i].faculty_id +`" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">` + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + `</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>email : `+ faculty[i].email +`</p>
                            <p>contact : `+ faculty[i].contact +`</p>
                            <p>sex : `+ faculty[i].gender +`</p>
                        </div>
                       
                        </div>
                    </div>
                </div>`;
            
            }

            output += '</tbody>' +
                '</table>';

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
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">` + ucfirst(faculty[i].last_name) + ', ' + ucfirst(faculty[i].first_name) + `</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>email : `+ faculty[i].email +`</p>
                            <p>contact : `+ faculty[i].contact +`</p>
                            <p>sex : `+ faculty[i].gender +`</p>
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


</script>



