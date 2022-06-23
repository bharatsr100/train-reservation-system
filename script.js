$('#logoimg').on('click', function () {
    window.location.reload();
});

$('#mode1').on('click', function () {
    $("#desc1").show();
    $("#desc2").hide();

    $("#mode1").addClass("btn_active");
    $("#mode2").removeClass("btn_active");
});
$('#mode2').on('click', function () {
    $("#desc1").hide();
    $("#desc2").show();

    $("#mode1").removeClass("btn_active");
    $("#mode2").addClass("btn_active");
});
$('#help_b').on('click', function () {
    // $("#register_form").show();
    // $("#login_form").hide();
    // $("#tooltiptext1").toggle();
    // visibility: visible;
    // visibility: hidden;

    var div = document.getElementById("tooltiptext1");
    div.style.display = div.style.display == "none" ? "block" : "none";


});


