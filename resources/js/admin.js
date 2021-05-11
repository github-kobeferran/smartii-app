// navLinks = document.querySelectorAll('.nav-item');

// navLinks.forEach(e => {
//     e.addEventListener('click', () => {
//         e.style.backgroundColor = "#FFFFFF";
//     })
// });

$object = document.querySelector('object');

$(".nav-link").on("click", function() {
    $(".nav-link").find(".active").removeClass("active");
    $(this).parent().addClass("active");
    $("#object").text($(this).text());

});

$(".tab-pane").on("click", function() {
    $(".tab-pane").find(".active").removeClass("active");
    $(this).parent().addClass("active");
});


// ADMINS VIEW AJAX REQUEST
document.querySelector('#admins-view-tab').addEventListener('click', () => {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'https://smartii.cc/admin/view/admins', true);

    xhr.onload = function() {
        var output = 'loading...';

        if (this.status == 200) {
            var admins = JSON.parse(this.responseText);


            output = '<table id="admins-table" class="table table-striped">' +
                '<thead>' +
                '<tr>' +
                '<th scope="col">USER ID</th>' +
                '<th scope="col">Name</th>' +
                '<th scope="col">Email</th>' +
                '<th scope="col">Position</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            for (var i in admins) {
                output += '<tr>' +
                    '<th scope="row">' + admins[i].user_id + '</th>' +
                    '<td>' + admins[i].name + '</td>' +
                    '<td>' + admins[i].email + '</td>' +
                    '<td>' + admins[i].position + '</td>' +
                    '</tr>';
            }

            output += '</tbody>' +
                '</table>';

            document.getElementById('admins-table').innerHTML = output;

        } else {
            console.log('failed');
        }
    }

    xhr.send();
});