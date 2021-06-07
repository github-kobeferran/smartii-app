<div class="form-group">    
    <input id="students-search" type="text" class="form-control" placeholder="Search here..">
</div>

<div>
    <table id="students-table" class="table table-striped table table-responsive-sm">
        
    </table>            
</div>

<div id="student-profile" class="card" style="width: 18rem;">
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Cras justo odio</li>
        <li class="list-group-item">Dapibus ac facilisis in</li>
        <li class="list-group-item">Vestibulum at eros</li>
    </ul>
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
            output += '<li class="list-group-item">' + student.first_name + ' ' + student.last_name  + '</li>';
            output += '<li class="list-group-item">' + student.email + '</li>';
            output += '<li class="list-group-item">' + student.program_desc + '</li>';
            output += '<li class="list-group-item">₱ ' + student.balance_amount + '</li>';            
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


document.getElementById('students-view-tab').addEventListener('click', () => {

studentsAjax();

document.getElementById('student-profile').style.display = 'none';

});


function studentsAjax() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/students', true);

    xhr.onload = function() {
        if (this.status == 200) {
            let results = JSON.parse(this.responseText);

            output = '<table id="students-table" class="table table-striped">' +
                '<thead>' +
                '<tr>' +

                '<th scope="col">View</th>' +
                '<th scope="col">Student ID</th>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Department</th>' +
                '<th scope="col">Program</th>' +
                '<th scope="col">Permanent Address</th>' +

                '</tr>' +
                '</thead>' +
                '<tbody>';

            for (let i = 0; i < results['students'].length; i++) {
                $department = (results['students'][i].department == 0) ? "SHS" : "College";
                output += '<tr>' +

                    '<td><button type="button"  onclick="viewStudent(' + results['students'][i].id + ')" class="btn btn-light border">View Details</button></td>' + //substring below
                    '<td>' + results['students'][i].student_id + '</td>' +
                    '<td>' + results['students'][i].last_name + ', ' + results['students'][i].first_name + ', ' + results['students'][i].middle_name.charAt(0).toUpperCase() + '</td>' +
                    '<td>' + $department + '</td>' +
                    '<td>' + results['programs'][i].abbrv + '</td>' +
                    '<td>' + results['students'][i].permanent_address + '</td>' +

                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

            document.getElementById('students-table').innerHTML = output;

        } else {
            let output = 'loading...';
            document.getElementById('students-table').innerHTML = output;
        }
    }

    xhr.send();
}



</script>
