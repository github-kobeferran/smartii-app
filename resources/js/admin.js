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