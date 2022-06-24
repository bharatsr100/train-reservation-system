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
        "description"=>"Error while fetching seat detail"

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
  else if($_POST['type']=="book_selected_seats"){
    $selected_seats= $_POST['selected_seats'];
    $seats_count= count($selected_seats);
    $b = " seat booked successfully";

    $success_text= $seats_count.$b;
    $result = array (
        "seats_info"=>"",
        "statuscode"=>"e",
        "description"=>"You need to select at least one seat to book it"

    );

    $allseats=array();
    $seat = array (

        "seat_id"=>"",
        "seat_no"=> "",
        "seat_status"=> "",

        "statuscode"=>"e",
        "description"=>"Error while booking seat"

    );

    if($seats_count==0){
        echo json_encode($result);
    }
    else{

        for($i = 0; $i < count($selected_seats); $i++)
        {
            $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$selected_seats[$i]'");
            if($s1){
                $seat['seat_id']= $selected_seats[$i];
                $seat['seat_no']= $selected_seats[$i];
                $seat['seat_status']= 1;
                $seat['statuscode']= "s";
                $seat['description']= "Seat Booked Successfully";
            
                $allseats[]=$seat;

            }
        }

        $result['seats_info']=$allseats;

        if(count($allseats)>0){
            $result['description']=$success_text;
            $result['statuscode']="s"; 
        }
        echo json_encode($result);
    }

    


  }
  else if($_POST['type']=="book_num_seats"){
      
    $num_seats= $_POST['num_seats'];
    // echo $num_seats;
    $result = array (
        "seat_info"=>"",
        "statuscode"=>"e",
        "description"=>"Error occured while booking seats"

    );
    

    $allseats=array();
    $seat = array (

        "seat_id"=>"",
        "seat_no"=> "",
        "seat_status"=> "",

        "statuscode"=>"e",
        "description"=>"Error while booking seat"

    );

    
    //Array index initialization
    $i=1;
    //starting of sliding window
    $st=0;
    //Ending of sliding window
    $en=0;
    //flag to check if there any continous n seats available in a single row or not 
    $flag=0;
    //starting index of continous window of n seats in a row
    $first_slot_i=0;
    //total seats to be booked
    $seats_count=$num_seats;
    //total empty seats
    $total_empty=0;
    
    $r1= mysqli_query($conn,"select * from seats order by seat_no");
    $n1= mysqli_num_rows($r1);
    // echo "\n n1: ${n1}";
    while($row=mysqli_fetch_assoc($r1)){

        if(($row['seat_no']-1)%7!=0){
            $isempty= !$row['seat_status'];

            $en= $i;
            
                if($isempty){
                    $total_empty++;
                    $slot= $en-$st;


                    if($slot>=$num_seats){
                        $flag=1;
                        $first_slot_i=$st+1;

                        // echo "\n i: ".$i."  st:".$st."  en:".$en. " slot:".$slot."  first_slot_i: ".$first_slot_i." num_seats:".$num_seats;
                        break;

                    }
                    

                }
                else{
                    $st=$i;
                }

                // echo "\nUPPER i: ".$i."  st:".$st."  en:".$en. " slot:".$slot;

        }
        else{
            $isempty= !$row['seat_status'];

            $en=$i;
            $st= $i-1;
            // echo "\n st_after {$st} i_after: {$i}  i-1: {$v}";
            if($isempty){
                $total_empty++;
                
                $slot= $en-$st;
                if($slot>=$num_seats){
                    $flag=1;
                    $first_slot_i=$st+1;
                    // echo "\n i: ".$i."  st:".$st."  en:".$en. " slot:".$slot."  first_slot_i: ".$first_slot_i." num_seats:".$num_seats;
                    break;

                }
                


            }
            else{
                $st=$i;
            }

            // echo "\nLOWER i: ".$i."  st:".$st."  en:".$en. " slot:".$slot;
        }


        $i++;


    }

    if($num_seats>$total_empty) {
        $result['statuscode']="e";
        $result['description']="Enough number of seats not available";
        echo json_encode($result);
    }
    else if($flag==1){
        // echo "slot: ".$slot."  first_slot_i: ".$first_slot_i;
        $temp=$seats_count;
        while($seats_count>0){
            $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$first_slot_i'");
            
            if($s1){
                $seat['seat_id']= $first_slot_i;
                $seat['seat_no']= $first_slot_i;
                $seat['seat_status']= 1;
                $seat['statuscode']= "s";
                $seat['description']= "Seat Booked Successfully";
            }
            else{
                $seat['statuscode']= "e";
                $seat['description']= "Error while booking seat";
            }

            $allseats[]=$seat;
            $first_slot_i++;
            $seats_count--;
        }

        $b = " seat booked successfully in a row";
        $success_text= $temp.$b;

        $result['seat_info']=$allseats;
        $result['statuscode']="s";
        $result['description']= $success_text;
        echo json_encode($result);


    }
    else{

        //Starting of sliding window
        $st= -1;
        // Ending of sliding window
        $en= -1;
        //Length of Minimum slot found
        $min_slot=100;
        //Starting index of minimum slot
        $min_slot_i=0;
        //Count of empty seats starting from min_slot_i
        $count=0;

        $seats_status = array();
        $i=0;
        
        $r2= mysqli_query($conn,"select * from seats order by seat_no");
        $n2= mysqli_num_rows($r1);

        while($row=mysqli_fetch_assoc($r2)){
            $seats_status[$i]= $row['seat_status'];

            $i++;
        }

        for($i=0;$i<80;$i++){
            $en=$i;
            if($seats_status[$i]==0){
                $count++;
                $slot=$en-$st;
                if($count==$seats_count){
                    if($slot<$min_slot){
                        $min_slot= $slot;
                        $min_slot_i= $st+1;
                    }

                    while($count==$seats_count){
                        
                        $st++;
                        if($seats_status[$st]==0) {
                            $count--;}
                        else{
                            $slot=$en-$st;

                            $min_slot= $slot;
                            $min_slot_i= $st+1;
                        }
                    }
                }
            }
            

        }
        $temp=$seats_count;
        while($seats_count>0 && $min_slot_i<80){
            if($seats_status[$min_slot_i]==0){
                $seats_count--;
                $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$min_slot_i'");
                
                if($s1){
                    $seat['seat_id']= $min_slot_i;
                    $seat['seat_no']= $min_slot_i;
                    $seat['seat_status']= 1;
                    $seat['statuscode']= "s";
                    $seat['description']= "Seat Booked Successfully";
                }
                else{
                    $seat['statuscode']= "e";
                    $seat['description']= "Error while booking seat";
                }
    
                $allseats[]=$seat;
                $min_slot_i++;
            }
        }

        $b = " seat booked successfully but not in a row";
        $success_text= $temp.$b;

        $result['statuscode']="s";
        $result['description']= $success_text;
        echo json_encode($result);


    }


    }  

?>