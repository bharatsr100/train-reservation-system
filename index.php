<?php
include 'database.php';

//Initial insertion of data into database (all seats unbooked)
// for($i = 1; $i < 81; $i++) {
//     $r3= mysqli_query($conn, "INSERT INTO seats (seat_id,seat_no,seat_status)VALUES ('$i','$i',0)");
// }



?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2&family=Bree+Serif&display=swap"
        rel="stylesheet">
    <title>Train Reservation</title>
    <link rel="icon" type="image/png" href="logo.png" />
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.js"></script>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <!-- Bootstrap Libraries -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</head>

<body>
    <nav id="navbar">
        <div id="logo">
            <img src="logo.png" alt="www.trainreservation.ml" id="logoimg">
        </div>
        <div id="headers">
            <button class="help tooltip" id="help_b"><span class="iconify" data-icon="bx:help-circle"
                    style="color: blue;" data-width="25" data-height="25"></span>
                <p>Help</p>
                <div class="tooltiptext" id="tooltiptext1" >

                    <p style="color:white; margin:0px;padding: 6px 20px;border: 2px solid #3a3a3a;background-color: #141938;border-radius: 10px;width: 100px;">
                        Mode 1:
                    </p>

                    <p style="margin: 4px 0px;">
                        Book Seats by entering the number of seats to be booked.</p>
                    <br><br>
                    <p style="color:white; margin:0px;padding: 6px 20px;border: 2px solid #3a3a3a;background-color: #141938;border-radius: 10px;width: 100px;">
                        Mode 2:
                    </p>
                    <p style="margin: 4px 0px;">
                    Book Seats by manually selecting the seats to be booked. Just select the seats and
                    then click on 'Book' button to book them.
                    </p>
                    <br>
                    <p id="tooltip_reset"><span class="iconify" data-icon="bx:reset" style="color: red;" data-width="25" data-height="25">
                    </span>
                    <span> Reset</span>
                    </p> 
                    It will reset all of the 80 seats as available, i.e. all seats will be unbooked.<br><br>

                </div>
            </button>
            <button class="reset" id="reset_b"><span class="iconify" data-icon="bx:reset" style="color: red;" data-width="25"
                    data-height="25"></span>
                <p>Reset</p>
            </button>
        </div>

        <!-- <ul>
            <li class="item"><a href="#home">Home</a></li>
            <li class="item"><a href="#services-container">Services</a></li>
            <li class="item"><a href="#client-section">Our Clients</a></li>
            <li class="item"><a href="#contact">Contact Us</a></li>
        </ul> -->
    </nav>


    <!-- The Modal Form for Reset Functionality -->
    <div id="myModal" class="modal">

    <!-- Reset Modal content -->
    <div class="modal-content">
    <div class="modal-header">
        <span class="close" id="close1">&times;</span>
        <h3>Do you want to Reset all the 80 Seats ?</h3>
        <p class="modal_head">(All the seats will be unbooked)</p>
    </div>
    <div class="modal-body">
        <p class="success_text" id="success_text_reset" style="display: none;">Reset Successfull</p>
        <p class="failure_text" id="failure_text_reset" style="display: none;">Reset Not Successfull</p>
    </div>
    <div class="modal-footer">
        <!-- <h3>Modal Footer</h3> -->
        <button class="confirm" id="reset_yes">Yes</button>
        <button class="reject" id="reset_no">Close</button>
    </div>
    </div>

    </div>


    <h1 class="h-primary">Train Reservation System</h1>
    <div class="modes_switch">
        <div class="modes">
            <button class="btn btn_active" id="mode1">Mode 1</button>
            <button class="btn" id="mode2">Mode 2</button>
        </div>

        <div class="desc_mode" id="desc1"><h3>(<b>Mode 1:</b> Book Seats by entering the number of seats to be booked)</h3></div>
        <div class="desc_mode" id="desc2" style="display:none;"><h3>(<b>Mode 2:</b> Book Seats by manually selecting the seats to be booked)</h3></div>
        
    </div>

    <div class="index">

    <h3 class="center" style="margin:20px 5px;">Types of seats:</h3>

    <div class="seatmap">
    <ul class="showcase">
        <li>
        <div class="seat1"></div>
        <small>Available</small>
        </li>

        <li>
        <div class="seat1 selected"></div>
        <small>Selected</small>
        </li>

        <li>
        <div class="seat1 sold"></div>
        <small>Booked</small>
        </li>
    </ul>
    </div>

    </div>




    <div class="container" id="container">
        <div class="coach">
            <div class="row" id="row1">
                <div class="seat" id="1">1</div>
                <div class="seat" id="2">2</div>
                <div class="seat" id="3">3</div>
                <div class="seat" id="4">4</div>
                <div class="seat" id="5">5</div>
                <div class="seat" id="6">6</div>
                <div class="seat" id="7">7</div>
            </div>
            <div class="row" id="row2">
                <div class="seat" id="14">14</div>
                <div class="seat" id="13">13</div>
                <div class="seat" id="12">12</div>
                <div class="seat" id="11">11</div>
                <div class="seat" id="10">10</div>
                <div class="seat" id="9">9</div>
                <div class="seat" id="8">8</div>
                <!-- <div class="seat" id="8"></div>
                <div class="seat" id="9"></div>
                <div class="seat" id="10"></div>
                <div class="seat" id="11"></div>
                <div class="seat" id="12"></div>
                <div class="seat" id="13"></div>
                <div class="seat" id="14"></div> -->
            </div>
            <div class="row" id="row3">
                <div class="seat" id="15">15</div>
                <div class="seat" id="16">16</div>
                <div class="seat" id="17">17</div>
                <div class="seat" id="18">18</div>
                <div class="seat" id="19">19</div>
                <div class="seat" id="20">20</div>
                <div class="seat" id="21">21</div>
            </div>
            <div class="row" id="row4">
                <div class="seat" id="28">28</div>
                <div class="seat" id="27">27</div>
                <div class="seat" id="26">26</div>
                <div class="seat" id="25">25</div>
                <div class="seat" id="24">24</div>
                <div class="seat" id="23">23</div>
                <div class="seat" id="22">22</div>
                <!-- <div class="seat" id="22"></div>
                <div class="seat" id="23"></div>
                <div class="seat" id="24"></div>
                <div class="seat" id="25"></div>
                <div class="seat" id="26"></div>
                <div class="seat" id="27"></div>
                <div class="seat" id="28"></div> -->
            </div>
            <div class="row" id="row5">
                <div class="seat" id="29">29</div>
                <div class="seat" id="30">30</div>
                <div class="seat" id="31">31</div>
                <div class="seat" id="32">32</div>
                <div class="seat" id="33">33</div>
                <div class="seat" id="34">34</div>
                <div class="seat" id="35">35</div>
            </div>
            <div class="row" id="row6">
                <div class="seat" id="42">42</div>
                <div class="seat" id="41">41</div>
                <div class="seat" id="40">40</div>
                <div class="seat" id="39">39</div>
                <div class="seat" id="38">38</div>
                <div class="seat" id="37">37</div>
                <div class="seat" id="36">36</div>
            </div>
            <div class="row" id="row7">
                <div class="seat" id="43">43</div>
                <div class="seat" id="44">44</div>
                <div class="seat" id="45">45</div>
                <div class="seat" id="46">46</div>
                <div class="seat" id="47">47</div>
                <div class="seat" id="48">48</div>
                <div class="seat" id="49">49</div>
            </div>
            <div class="row" id="row8">
                <div class="seat" id="56">56</div>
                <div class="seat" id="55">55</div>
                <div class="seat" id="54">54</div>
                <div class="seat" id="53">53</div>
                <div class="seat" id="52">52</div>
                <div class="seat" id="51">51</div>
                <div class="seat" id="50">50</div>
            </div>
            <div class="row" id="row9">
                <div class="seat" id="57">57</div>
                <div class="seat" id="58">58</div>
                <div class="seat" id="59">59</div>
                <div class="seat" id="60">60</div>
                <div class="seat" id="61">61</div>
                <div class="seat" id="62">62</div>
                <div class="seat" id="63">63</div>
            </div>
            <div class="row" id="row10">
                <div class="seat" id="70">70</div>
                <div class="seat" id="69">69</div>
                <div class="seat" id="68">68</div>
                <div class="seat" id="67">67</div>
                <div class="seat" id="66">66</div>
                <div class="seat" id="65">65</div>
                <div class="seat" id="64">64</div>
            </div>
            <div class="row" id="row11">
                <div class="seat" id="71">71</div>
                <div class="seat" id="72">72</div>
                <div class="seat" id="73">73</div>
                <div class="seat" id="74">74</div>
                <div class="seat" id="75">75</div>
                <div class="seat" id="76">76</div>
                <div class="seat" id="77">77</div>

            </div>
            <div class="row" id="row12">
                <div class="seat" id="80">80</div>
                <div class="seat" id="79">79</div>
                <div class="seat" id="78">78</div>

            </div>

        </div>

    </div>
    
    <div id="lower_message">
        <p class="success_text" id="success_text_book" style="display:none;">Booking Successfull</p>
        <p class="failure_text" id="failure_text_book" style="display:none;">Booking Failed</p>
    </div>

    <div id="mode1_lower_div" style="display:block;">


        <!-- <p class="text"> You have selected <span id="count">0</span> seats</p> -->
        <form id="mode1_form" name="mode1_form" method="post" >

        <div  class="form-group">
            <label  for="num_seats">Enter no of seats to be booked:
            </label>
            <input type="number" id="num_seats" min="0" step="1" oninput="validity.valid||(value='');" name="num_seats" class="form-control" placeholder="No of Seats">
        </div>

            <button type="button" class="confirm" id="book_mode1">Book </button>
        </form>


    </div>
    <div id="mode2_lower_div" style="display:none;">


        <p class="text"> You have selected <span id="count">0</span> seats</p>
        <div id="mode2_lower_btn">
            <button class="confirm" id="book_yes_2">Book </button>
            <button class="reject" id="book_cancel_2">Cancel</button>
        </div>

    </div>


    <footer>
        <div class="center">
            <!-- Copyright &copy; www.trainreservation.ml All Rights Reserved;-->
            Made with &#10084;&#65039; by Bharat Singh Rajpurohit
        </div>
    </footer>

    <script src="script.js"></script>

</body>

</html>