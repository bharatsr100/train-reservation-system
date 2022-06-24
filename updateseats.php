<?php

include 'database.php';
if($_POST['type']=="reset_seats" ){
    $result = array (
            "ttype"=> "",
            "ttytpe_desc"=> "",
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

?>