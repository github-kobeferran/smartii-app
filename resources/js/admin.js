// navLinks = document.querySelectorAll('.nav-item');

// navLinks.forEach(e => {
//     e.addEventListener('click', () => {
//         e.style.backgroundColor = "#FFFFFF";
//     })
// });

$(".nav-link").on("click", function() {
    $(".nav-link").find(".active").removeClass("active");
    $(this).parent().addClass("active");
});