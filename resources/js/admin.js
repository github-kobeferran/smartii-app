$(".nav-link").on("click", function() {
    $(".nav-link").find(".active").removeClass("active");
    $(this).parent().addClass("active");
    $("#object").text($(this).text());

});


$(".tab-pane").on("click", function() {
    $(".tab-pane").find(".active").removeClass("active");
    $(this).parent().addClass("active");
});

// $(document).ready(function() {
//     $(".nav-link").find(".active").removeClass("active");
//     $(this).parent().addClass("active");
//     $("#object").text($(".nav-link").text());
// });
// $(document).ready(function() {
//     $(".tab-pane").find(".active").removeClass("active");
//     $(this).parent().addClass("active");
// });





window.addEventListener('load', (event) => {


    changeSelect();

});



// #################          VIEW
// ---------------------------------------------> FILL TABLE WHEN ADMIN-NAV-LINK is CLICKED


document.getElementById('admins-view-tab').addEventListener('click', () => {
    //console.log('clicked');

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/admins', true);

    xhr.onload = function() {
        if (this.status == 200) {

            let admins = JSON.parse(this.responseText);

            output = '<table id="admins-table" class="table table-striped">' +
                '<thead>' +
                '<tr>' +
                '<th scope="col">USER ID</th>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Email</th>' +
                '<th scope="col">Position</th>' +
                '<th scope="col">Action</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            for (let i in admins) {
                output += '<tr>' +
                    '<th scope="row">' + admins[i].user_id + '</th>' +
                    '<td>' + admins[i].name + '</td>' +
                    '<td>' + admins[i].email + '</td>' +
                    '<td>' + admins[i].position + '</td>' +
                    '<td><a href=\"admin/delete/' + admins[i].user_id + ' \" class="btn btn-primary">delete</a></td>' +
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
});

// ---------------------------------------------> FILL ADMIN TABLE WHEN PER KEYUP SEARCH INPUT
document.querySelector('#admin-search').addEventListener('keyup', (e) => {


    txt = document.querySelector('#admin-search').value;

    let xhr = new XMLHttpRequest();

    xhr.open('GET', 'http://smartii-app.test/admin/search/admins/' + txt, true);

    document.getElementsByClassName('admin-dual-ring')[0].style.display = 'inline-block';

    xhr.onload = function() {

        // console.log(typeof this.responseText);

        if (this.status == 200) {

            let admins = JSON.parse(this.responseText);

            output = '<table id="admins-table" class="table table-striped">' +
                '<thead>' +
                '<tr>' +
                '<th scope="col">USER ID</th>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Email</th>' +
                '<th scope="col">Position</th>' +
                '<th scope="col">Action</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            for (let i in admins) {
                output += '<tr>' +
                    '<th scope="row">' + admins[i].user_id + '</th>' +
                    '<td>' + admins[i].name + '</td>' +
                    '<td>' + admins[i].email + '</td>' +
                    '<td>' + admins[i].position + '</td>' +
                    '<td><a href=\"admin/delete/' + admins[i].user_id + ' \" class="btn btn-primary">delete</a></td>' +
                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

            document.getElementsByClassName('admin-dual-ring')[0].style.display = 'none';
            document.getElementById('admins-table').innerHTML = output;


        } else if (this.status == 404) {
            let output = 'not found';
            document.getElementById('admins-table').innerHTML = output;
        }
    }

    xhr.send();
});



document.getElementById('students-view-tab').addEventListener('click', () => {

    studentsAjax();

    document.getElementById('student-profile').style.display = 'none';

});



function studentsAjax() {
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/students', true);

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
                '<th scope="col">Balance</th>' +

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





// #################   CREATE