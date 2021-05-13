$(".nav-link").on("click", function() {
    $(".nav-link").find(".active").removeClass("active");
    $(this).parent().addClass("active");
    $("#object").text($(this).text());

});



$(".tab-pane").on("click", function() {
    $(".tab-pane").find(".active").removeClass("active");
    $(this).parent().addClass("active");
});

// ---------------------------------------------> FILL TABLE WHEN ADMIN-NAV-LINK is CLICKED
document.getElementById('admins-view-tab').addEventListener('click', () => {
    //console.log('clicked');

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/admins', true);

    xhr.onload = function() {
        if (this.status == 200) {
            var admins = JSON.parse(this.responseText);

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

            for (var i in admins) {
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

        } else {
            var output = 'loading...';
            document.getElementById('admins-table').innerHTML = output;
        }
    }

    xhr.send();
});



// ---------------------------------------------> FILL ADMIN TABLE WHEN PER KEYUP SEARCH INPUT
document.querySelector('#admin-search').addEventListener('keyup', (e) => {
    console.log('keyup');

    txt = document.querySelector('#admin-search').value;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://smartii-app.test/admin/view/search/admins/' + txt, true);

    document.getElementsByClassName('lds-dual-ring')[0].style.display = 'inline-block';
    xhr.onload = function() {

        if (this.status == 200) {
            var admins = JSON.parse(this.responseText);


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

            for (var i in admins) {
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
            document.getElementsByClassName('lds-dual-ring')[0].style.display = 'none';
            document.getElementById('admins-table').innerHTML = output;


        } else {
            var output = 'not found';
            document.getElementById('admins-table').innerHTML = output;
        }
    }

    xhr.send();
});