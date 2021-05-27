{!! Form::open(['url' => 'admin/create/admin', 'files' => true, 'id' => 'adminForm']) !!}

    <blockquote  class="blockquote text-center">Pending Classes, Assign Schedule and Instructor</blockquote >
        
    <div class="row no-gutters">    

        <div class="col-sm">
            
            
            <div class="form-group"> 
                                   
                {{Form::select('dept', 
                  ['0' => 'Senior High School Students',                              
                  '1' => 'College Students'], 0,
                  ['class' => 'custom-select m-1 w-50 float-right', 'id' => 'selectSubjDept'])}}                   
            </div>   

        </div>
        <div class="col-sm">
            
            <div class="form-group">                            
                {{Form::select('prog', 
                [], null,
                ['class' => 'custom-select m-1 w-50 float-left', 'id' => 'selectSubjProg'])}}          
            </div>                

        </div>
    </div>

    <div class="row">    

        <div class="col-sm">
            
            <div class="form-group text-center">        
                    
                {{Form::select('level', 
                [], null,
                ['class' => 'custom-select w-50 m-1', 'id' => 'selectSubjLevel'])}}            
            </div>    

        </div>
       
    </div>

    <div class="row mt-2">    

        <div class="col-sm">
            
            <div >
                <table id="admins-table" class="table table-striped table table-responsive-sm border-5 shadow" >
                    
                    <thead class="thead">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">First</th>
                          <th scope="col">Last</th>
                          <th scope="col">Handle</th>
                        </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                    </tr>
                    </tbody>
                                                                                
                </table>            
            </div>

        </div>         

    </div> 


    <div class="row mt-2 no-gutters">    

        <div class="col-sm">
            
            <p class="text-center"><strong>Day</strong></p>

            <div class="form-group text-center">        
                    
                {{Form::select('level', 
                [], null,
                ['class' => 'custom-select bg-dark text-white', 'id' => 'selectSubjLevel'])}}            
            </div>    

        </div>

        <div class="col-sm">
                        
            <p class="text-center"><strong>Time</strong></p>

            <div class="form-group text-center">        
                    
                {{Form::select('level', 
                [], null,
                ['class' => 'custom-select bg-dark text-white', 'id' => 'selectSubjLevel'])}}            
            </div>    

        </div>
        
        <div class="col-sm">

            <p class="text-center"><strong>Room</strong></p>
            
            <div class="form-group text-center">        
                    
                {{Form::select('level', 
                [], null,
                ['class' => 'custom-select bg-dark text-white', 'id' => 'selectSubjLevel'])}}            
            </div>    

        </div>

        <div class="col-sm">

            <p class="text-center"><strong>Instructor</strong></p>
            
            <div class="form-group text-center">        
                    
                {{Form::select('level', 
                [], null,
                ['class' => 'custom-select bg-dark text-white', 'id' => 'selectSubjLevel'])}}            
            </div>    

        </div>
       
    </div> 



    <hr class= ""/>
    

    <div class = "form-group mr-0 text-center">        
        {{Form::submit('Save',  ['class' => 'btn btn-success w-50 mt-3'])}}
    </div> 

    


{!! Form::close() !!}

<script>

function classesTable(){
    
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/pendingclasses', true);

    xhr.onload = function() {
        if (this.status == 200) {

            let admins = JSON.parse(this.responseText);

            output = '<table id="admins-table" class="table table-striped">' +
                '<thead>' +
                '<tr>' +
                '<th scope="col">Admin ID</th>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Email</th>' +
                '<th scope="col">Position</th>' +
                '<th scope="col">Action</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

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

            document.getElementById('admins-table').innerHTML = output;

        } else if (this.status == 404) {
            let output = 'not found...';
            document.getElementById('admins-table').innerHTML = output;
        }
    }

    xhr.send();

}

</script>