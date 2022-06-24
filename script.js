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
    // $("#tooltiptext1").toggle();
    // visibility: visible;
    // visibility: hidden;
    // console.log("Testing Tooltip");
    // var div = document.getElementById("tooltiptext1");
    // div.style.display = div.style.display == "none" ? "block" : "none";
});

// Get the modal
var modal = document.getElementById("myModal");


// Opens the reset modal 
$('#reset_b').on('click', function () {
    modal.style.display = "block";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);
});

// btn.onclick = function() {}


//Closes reset modal upon clicking span element(x)
$('#close1').on('click', function () {
    modal.style.display = "none";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);
    // $("#success_text_reset").hide();
});

$('#reset_no').on('click', function () {
    modal.style.display = "none";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);

    // $("#success_text_reset").hide();
});

// When the user clicks anywhere outside of the reset modal, close it
// window.onclick = function (event) {
//     if (event.target == modal) {
//         modal.style.display = "none";
//     }
// }


$(window).click(function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    // $("#success_text_reset").hide();
});

$('#reset_yes').on('click', function () {
    // modal.style.display = "none";
    // $("#success_text_reset").css("visibility", "visible");
    // $("#reset_yes").prop("readonly", true);

    var type = "reset_seats";

    // Ajax query to reset the seats
    $.ajax({
        url: "updateseats.php",
        type: "POST",
        data: {
            type: type

        },
        cache: false,
        success: function (dataResult) {
            // console.log(dataResult);
            var dataResult = JSON.parse(dataResult);
            // console.log(dataResult);
            if (dataResult.statuscode == "s") {


                $("#success_text_reset").show();
                $('#success_text_reset').html(dataResult.description);
                $("#reset_yes").prop("disabled", true);

            } else {
                $("#success_text_reset").show();
                $('#success_text_reset').html(dataResult.description);
            }

        }


    });



    // console.log("hello_resetyes");
});


// $("#tid3").prop("readonly", true);
// $("#tid3").prop("disabled", false);

// $("#tid3").attr("readonly", "readonly");
// $("#tid3").attr("disabled", "disabled");


// function fetchseats() {
//     const selectedSeats = JSON.parse(localStorage.getItem("selectedSeats"));
  
//     if (selectedSeats !== null && selectedSeats.length > 0) {
//       seats.forEach((seat, index) => {
//         if (selectedSeats.indexOf(index) > -1) {
//           // console.log(seat.classList.add("selected"));
//           seat.classList.add("selected");
//         }
//       });
//     }
  
//     const selectedMovieIndex = localStorage.getItem("selectedMovieIndex");
  
//     if (selectedMovieIndex !== null) {
//       movieSelect.selectedIndex = selectedMovieIndex;
//       // console.log(selectedMovieIndex)
//     }
//   }
//   // console.log(populateUI())
//   fetchseats();
