$('#logoimg').on('click', function () {
    window.location.reload();
});

$('#mode1').on('click', function () {
    $("#desc1").show();
    $("#desc2").hide();
    $("#mode2_lower_div").hide();
    $("#mode1_lower_div").show();
    $("#success_text_book").hide();
    $("#failure_text_book").hide();

    $("#mode1").addClass("btn_active");
    $("#mode2").removeClass("btn_active");
    cleanselection();


});
$('#mode2').on('click', function () {
    $("#desc1").hide();
    $("#desc2").show();
    $("#mode2_lower_div").show();
    $("#mode1_lower_div").hide();
    $("#success_text_book").hide();
    $("#failure_text_book").hide();

    $("#mode1").removeClass("btn_active");
    $("#mode2").addClass("btn_active");


});



// Get the modal
var modal = document.getElementById("myModal");


// Opens the reset modal 
$('#reset_b').on('click', function () {
    modal.style.display = "block";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);
});


//Closes reset modal upon clicking span element(x)
$('#close1').on('click', function () {
    modal.style.display = "none";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);

    $("#success_text_book").hide();
    $("#failure_text_book").hide();
});

$('#reset_no').on('click', function () {
    modal.style.display = "none";
    $("#success_text_reset").hide();
    $("#reset_yes").prop("disabled", false);

});

// When the user clicks anywhere outside of the reset modal, close it
$(window).click(function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
        $("#success_text_book").hide();
        $("#failure_text_book").hide();
    }
});

//Function to fetch status of all seats and update the UI
function fetchseats() {
    var type = "fetch_seats";

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
                const seats_info = dataResult.seats_info;
                // console.log(seats_info);

                localStorage.setItem("all_seats_info", JSON.stringify(seats_info));
                $(seats_info).each(function (index, item) {
                    var seat_div = document.getElementById(item.seat_id);
                    if (item.seat_status == 1) {
                        $(seat_div).addClass("sold");
                    }
                    else {
                        $(seat_div).removeClass("sold");
                    }
                });
            }
        }
    });

}

//Function to update the selected seats by user when Mode 2 is active 
function updateSelectedCount() {
    const count = document.getElementById("count");
    const selectedSeats = document.querySelectorAll(".seat.selected");
    var seatsindex = new Array();

    selectedSeats.forEach((element, index) => {
        seatsindex[index] = element.id;

    });
    // console.log(seatsindex);

    localStorage.setItem("selected_seats", JSON.stringify(seatsindex));
    // const selected_seats = JSON.parse(localStorage.getItem("selected_seats"));
    const selectedSeatsCount = selectedSeats.length;
    count.innerText = selectedSeatsCount;

}


fetchseats();

const container = document.querySelector(".container");

//Adding event listener so that user can manually select and book seats when switched to mode2
container.addEventListener("click", (e) => {
    if (
        e.target.classList.contains("seat") && !e.target.classList.contains("sold") && $("#mode2").hasClass("btn_active")
    ) {
        e.target.classList.toggle("selected");

        updateSelectedCount();
    }
});

// Function to clean the selected seats ....i.e. everything will be unselected
function cleanselection() {
    var seatsindex = new Array();
    const count = document.getElementById("count");
    var selected_seats = JSON.parse(localStorage.getItem("selected_seats"));
    selected_seats.forEach((element, index) => {

        var el = document.getElementById(element);
        // console.log(el);
        $(el).removeClass("selected");
    });

    localStorage.setItem("selected_seats", JSON.stringify(seatsindex));
    const selectedSeatsCount = 0;
    count.innerText = selectedSeatsCount;
}

// Function to clean selection whenever user clicks on cancel button in Mode 2
$('#book_cancel_2').on('click', function () {
    cleanselection();

});

// This function resets all the seats as available i.e. all seats will be unbooked
$('#reset_yes').on('click', function () {

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
                $("#failure_text_reset").hide();

                $('#success_text_reset').html(dataResult.description);
                $("#reset_yes").prop("disabled", true);

            } else {
                $("#success_text_reset").hide();
                $("#failure_text_reset").show();
                $('#failure_text_reset').html(dataResult.description);
            }

            cleanselection();
            fetchseats();

        }


    });
});


//Book the selected seats in Mode 2
$('#book_yes_2').on('click', function () {


    var type = "book_selected_seats";
    var selected_seats = JSON.parse(localStorage.getItem("selected_seats"));
    // console.log(selected_seats);
    // Ajax query to reset the seats

    if (selected_seats.length > 0) {
        $.ajax({
            url: "updateseats.php",
            type: "POST",
            data: {
                type: type,
                selected_seats: selected_seats

            },
            cache: false,
            success: function (dataResult) {
                // console.log(dataResult);
                var dataResult = JSON.parse(dataResult);
                // console.log(dataResult);
                if (dataResult.statuscode == "s") {


                    $("#success_text_book").show();
                    $("#failure_text_book").hide();
                    var bookedseats = dataResult.seats_info;
                    $("#success_text_book").html(bookedseats.length);

                    if (bookedseats.length == 1) {
                        $("#success_text_book").append(` seat booked successfully. Booked Seat is`);
                    }
                    else {
                        $("#success_text_book").append(` seats booked successfully. Booked Seats are`);
                    }

                    bookedseats.forEach((element, index) => {



                        if (index == bookedseats.length - 1) {
                            $("#success_text_book").append(` ${element.seat_id}.`);
                        }
                        else {
                            $("#success_text_book").append(` ${element.seat_id},`);
                        }


                    });

                } else {
                    $("#success_text_book").hide();
                    $("#failure_text_book").show();
                    $('#failure_text_book').html(dataResult.description);
                }

                cleanselection();
                fetchseats();

            }


        });

    }
    else {
        $("#success_text_book").hide();
        $("#failure_text_book").show();
        $('#failure_text_book').html("You need to select at least one seat to book it");
    }
});

// Function to book the number of seats entered by user in Mode 1
$('#book_mode1').on('click', function () {

    var num_seats = $('#num_seats').val();
    var type = "book_num_seats"
    if (num_seats == 0 || num_seats == "") {
        $("#success_text_book").hide();
        $("#failure_text_book").show();
        $('#failure_text_book').html("You need to enter at least one seat to book it");
    }
    else if (num_seats > 7) {
        $("#success_text_book").hide();
        $("#failure_text_book").show();
        $('#failure_text_book').html("Can not book more than 7 seats at once");
    }
    else {
        $.ajax({
            url: "updateseats.php",
            type: "POST",
            data: {
                type: type,
                num_seats: num_seats

            },
            cache: false,
            success: function (dataResult) {
                // console.log(dataResult);
                var dataResult = JSON.parse(dataResult);
                // console.log(dataResult);
                if (dataResult.statuscode == "s") {


                    $("#success_text_book").show();
                    $("#failure_text_book").hide();
                    var bookedseats = dataResult.seat_info;
                    $("#success_text_book").html(bookedseats.length);

                    if (bookedseats.length == 1) {
                        $("#success_text_book").append(` seat booked successfully. Booked Seat is`);
                    }
                    else {
                        $("#success_text_book").append(` seats booked successfully. Booked Seats are`);
                    }

                    bookedseats.forEach((element, index) => {



                        if (index == bookedseats.length - 1) {
                            $("#success_text_book").append(` ${element.seat_id}.`);
                        }
                        else {
                            $("#success_text_book").append(` ${element.seat_id},`);
                        }


                    });


                } else {
                    $("#success_text_book").hide();
                    $("#failure_text_book").show();
                    $("#failure_text_book").html(dataResult.description);
                }

                // cleanselection();
                fetchseats();

            }


        });
    }


});

//Prevent form resubmission on refreshing
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}