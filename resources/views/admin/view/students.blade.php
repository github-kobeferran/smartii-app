<div class="form-group">    
    <input id="students-search" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Search here..">
</div>


<div>
    <table id="students-table" class="table table-striped">
        
    </table>            
</div>

<div id="student-profile" class="card" style="width: 18rem;">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Cras justo odio</li>
        <li class="list-group-item">Dapibus ac facilisis in</li>
        <li class="list-group-item">Vestibulum at eros</li>
    </ul>
</div>

<div class="loader-parent">
        <div class="lds-dual-ring" style=""></div>            
</div>

<script>

function viewStudent(id) {    

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/students/' + id, true);

    xhr.onload = function() {

        if (this.status == 200) {
            var student = JSON.parse(this.responseText);

            output = '<div id="student-profile" class="card" style="width: 18rem;">';
            output += '<ul class="list-group list-group-flush">';           
            output += '<li class="list-group-item">' + student.first_name + '</li>';
            output += '<li class="list-group-item">' + student.last_name + '</li>';
            output += '<li class="list-group-item">' + student.program_desc + '</li>';
            output += '<li class="list-group-item">' + student.email + '</li>';            
            output += '</ul>';
            output += '</div>';
            
            document.getElementById('student-profile').innerHTML = output;
            document.getElementById('student-profile').style.display = 'block';
            document.getElementById('students-table').style.display = 'none';
           

        } else {
            var output = 'loading...';
            // document.getElementById('students-table').innerHTML = output;
        }


    }

    xhr.send();

}
</script>
