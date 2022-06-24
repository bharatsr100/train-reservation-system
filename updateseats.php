<?php

include 'database.php';
if($_POST['type']=="reset_seats" ){
    $result = array (

            "statuscode"=>"e",
            "description"=>"Error while Resetting Seats"
  
        );

        $s1= mysqli_query($conn,"UPDATE seats SET seat_status=0 WHERE seat_status=1");
        if($s1){
            $result['description']="Seat Resetting Successfull";
            $result['statuscode']="s";

        }

       
        // $r2= mysqli_query($conn,"select * from task_types where ttype='$ttype'");
        // $n2= mysqli_num_rows($r2);
        // if($n2){
        //   $tasktype['description']="Task Type ID already exist";
        //   while($row=mysqli_fetch_assoc($r2)){

        // }}
   
        echo json_encode($result);
  
  }
  else if($_POST['type']=="fetch_seats"){

    $result = array (
        "seats_info"=>"",
        "statuscode"=>"e",
        "description"=>"Error while Fetching data of Seats"

    );

    
    $allseats=array();
    $seat = array (

        "seat_id"=>"",
        "seat_no"=> "",
        "seat_status"=> "",

        "statuscode"=>"e",
        "description"=>"Error while fertching seat detail"

    );


    $r1= mysqli_query($conn,"select * from seats");
    $n1= mysqli_num_rows($r1);
    while($row=mysqli_fetch_assoc($r1)){
    $seat['seat_id']= $row['seat_id'];
    $seat['seat_no']= $row['seat_no'];
    $seat['seat_status']= $row['seat_status'];
    $seat['statuscode']= "s";
    $seat['description']= "seat detail fetched successfully";

    $allseats[]=$seat;


    }
    if($r1){
        $result['description']="Seat Data Fetched Successfully";
        $result['statuscode']="s";

    }
    $result['seats_info']=$allseats;

    echo json_encode($result);
  }

?>