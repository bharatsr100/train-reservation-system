<?php

include 'database.php';
// Resets all the seats
if($_POST['type']=="reset_seats" ){

    // Result array to store the result
    $result = array (

            "statuscode"=>"e",
            "description"=>"Error while Resetting Seats"
  
        );

        $s1= mysqli_query($conn,"UPDATE seats SET seat_status=0 WHERE seat_status=1");
        if($s1){
            $result['description']="Seat Resetting Successfull";
            $result['statuscode']="s";

        }
        // convert PHP array or object into JSON format
        echo json_encode($result);
  
  }

//  Fetch status of all seats from database
  else if($_POST['type']=="fetch_seats"){

    // Result array to store all results
    $result = array (
        "seats_info"=>"",
        "statuscode"=>"e",
        "description"=>"Error while Fetching data of Seats"

    );

    // Array to store data of all seats
    $allseats=array();

    // Array to store data of each seat
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

    // Returns result in json format
    echo json_encode($result);
  }

//   Books the selected seats by user in Mode 2
  else if($_POST['type']=="book_selected_seats"){
    // Array consisted of seat numbers which the user wants to book
    $selected_seats= $_POST['selected_seats'];
    $seats_count= count($selected_seats);

    $result = array (
        "seats_info"=>"",
        "statuscode"=>"e",
        "description"=>"You need to select at least one seat to book it"
        
    );
    
    // Success Text to store in result array if there are no issues
    $b = " seat booked successfully";
    $success_text= $seats_count.$b;

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

//   Books the number of seats entered by user in Mode 1
  else if($_POST['type']=="book_num_seats"){
      
    $num_seats= $_POST['num_seats'];
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

    // Will store the number of empty seats empty in each row
    $row_status= array();
    // variable to track count of empty seats in a row
    $row_avl=0;
    // Will store the indexes of all empty seats
    $seats_status = array();
    // Will store status of all seats....0 if available and 1 if  booked
    $all_seats_status = array();
    // Total empty seats
    $total_empty=0;
    // Variable for index
    $i=0;
    $r2= mysqli_query($conn,"select * from seats order by seat_no");

    // To store status of all seats and id of empty seats
    while($row=mysqli_fetch_assoc($r2)){
        if(!$row['seat_status']){
            $seats_status[]=$row['seat_id'];
        }
        
        $all_seats_status[]= $row['seat_status'];
        // echo "\n inloop i:{$i}  seats_id: {$seats_status[$i]}";
        
    }

    
    // To store number of seats empty in each row...This loop could have been merged wih upper loop...but wanted to do it seperately to avoid mess
    $r3= mysqli_query($conn,"select * from seats order by seat_no");
    while($row3=mysqli_fetch_assoc($r3)){
        $total_empty=$total_empty+ !$row3['seat_status'];
        if(($row3['seat_id']-1)%7!=0){
            $row_avl= $row_avl+ !$row3['seat_status'];
        }
        else{
            $row_status[]=$row_avl;
            $row_avl= !$row3['seat_status'];
        }
    }

    // Store number of empty seats in last row
    $row_status[]= $row_avl;

    // To check if there are n (number of seats required by user) empty seats availale in a single row or not
    $flag2=0;
    $row_index=0;


    for($i1=0;$i1<count($row_status);$i1++){
        // echo "\n row: {$i1}  seats_avl: {$row_status[$i1]}";
        if($row_status[$i1]>=$num_seats){
            $row_index=$i1;
            $flag2=1;
            break;
        }
    }

    // Success text if everything goes right
    $b = " seat booked successfully but not in a row";
    $success_text= $num_seats.$b;

    // In case enough number of seats are not available
    if($num_seats>$total_empty) {
        $result['statuscode']="e";
        $result['description']="Enough number of seats not available";
        echo json_encode($result);
    }
    // If there are n (number of seats demanded by user) empty seats in a single row
    else if($flag2==1){
        $seats_required= $num_seats;
        $k= ($row_index-1)*7+1;
        for($i=$k; $i<$k+7; $i++){
            if($all_seats_status[$i-1]==0){
                $seats_required--;
                $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$i'");
                if($s1){
                    $seat['seat_id']= $i;
                    $seat['seat_no']= $i;
                    $seat['seat_status']= 1;
                    $seat['statuscode']= "s";
                    $seat['description']= "Seat Booked Successfully";
                }
                else{
                    $seat['statuscode']= "e";
                    $seat['description']= mysqli_error($conn);
                }
    
                $allseats[]=$seat;

            }

            if($seats_required==0) break;
        }

        $result['seat_info']=$allseats;
        $result['statuscode']="s";
        $result['description']= $success_text;
        echo json_encode($result);

    }

    // To book the nearest n seats...as they are not available in a single row
    // Based on the concept of sliding window
    else{

        $size= count($seats_status);
        $start_i=0;

        $i=0;
        $j=0;
        $k= $num_seats;

        $min_diff= 100;
        while($j<$size){
            $diff= $seats_status[$j]-$seats_status[$i]+1;
            if($j-$i+1< $k){
                $j++;
            }
            else if($j-$i+1==$k){
                if($min_diff>$diff){
                    $min_diff=$diff;
                    $start_i=$i; 
                }
                $i++;
                $j++;
            }
        }

        for($i=0;$i<$k;$i++){
            $id= $seats_status[$start_i+$i];
            $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$id'") ;

            if($s1){
                $seat['seat_id']= $id;
                $seat['seat_no']= $id;
                $seat['seat_status']= 1;
                $seat['statuscode']= "s";
                $seat['description']= "Seat Booked Successfully";
            }
            else{
                $seat['statuscode']= "e";
                $seat['description']= mysqli_error($conn);
            }

            $allseats[]=$seat;
        }
  


    
        $result['seat_info']=$allseats;
        $result['statuscode']="s";
        $result['description']= $success_text;

        // Returns the result object in json format
        echo json_encode($result);


    }


    }
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
///////////////////////////// IGNORE the below code //////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // //Array index initialization
    // $i=1;
    // //starting of sliding window
    // $st=0;
    // //Ending of sliding window
    // $en=0;
    // //flag to check if there any continous n seats available in a single row or not 
    // $flag=0;
    // //starting index of continous window of n seats in a row
    // $first_slot_i=0;
    // //total seats to be booked
    // $seats_count=$num_seats;
    // //total empty seats
    // $total_empty=0;
    // 
    // $r1= mysqli_query($conn,"select * from seats order by seat_no");
    // $n1= mysqli_num_rows($r1);
    // // echo "\n n1: ${n1}";
    // while($row=mysqli_fetch_assoc($r1)){

    //     if(($row['seat_no']-1)%7!=0){
    //         $isempty= !$row['seat_status'];

    //         $en= $i;
            
    //             if($isempty){
    //                 $total_empty++;
    //                 $slot= $en-$st;


    //                 if($slot>=$num_seats){
    //                     $flag=1;
    //                     $first_slot_i=$st+1;

    //                     // echo "\n i: ".$i."  st:".$st."  en:".$en. " slot:".$slot."  first_slot_i: ".$first_slot_i." num_seats:".$num_seats;
    //                     break;

    //                 }
                    

    //             }
    //             else{
    //                 $st=$i;
    //             }

    //             // echo "\nUPPER i: ".$i."  st:".$st."  en:".$en. " slot:".$slot;

    //     }
    //     else{
    //         $isempty= !$row['seat_status'];

    //         $en=$i;
    //         $st= $i-1;
    //         // echo "\n st_after {$st} i_after: {$i}  i-1: {$v}";
    //         if($isempty){
    //             $total_empty++;
                
    //             $slot= $en-$st;
    //             if($slot>=$num_seats){
    //                 $flag=1;
    //                 $first_slot_i=$st+1;
    //                 // echo "\n i: ".$i."  st:".$st."  en:".$en. " slot:".$slot."  first_slot_i: ".$first_slot_i." num_seats:".$num_seats;
    //                 break;

    //             }
                


    //         }
    //         else{
    //             $st=$i;
    //         }

    //         // echo "\nLOWER i: ".$i."  st:".$st."  en:".$en. " slot:".$slot;
    //     }


    //     $i++;


    // }


    // else if($flag==1){
    //     // echo "slot: ".$slot."  first_slot_i: ".$first_slot_i;
    //     $temp=$seats_count;
    //     while($seats_count>0){
    //         $s1= mysqli_query($conn,"UPDATE seats SET seat_status=1 WHERE seat_id= '$first_slot_i'");
            
    //         if($s1){
    //             $seat['seat_id']= $first_slot_i;
    //             $seat['seat_no']= $first_slot_i;
    //             $seat['seat_status']= 1;
    //             $seat['statuscode']= "s";
    //             $seat['description']= "Seat Booked Successfully";
    //         }
    //         else{
    //             $seat['statuscode']= "e";
    //             $seat['description']= "Error while booking seat";
    //         }

    //         $allseats[]=$seat;
    //         $first_slot_i++;
    //         $seats_count--;
    //     }

    //     $b = " seat booked successfully in a row";
    //     $success_text= $temp.$b;

    //     $result['seat_info']=$allseats;
    //     $result['statuscode']="s";
    //     $result['description']= $success_text;
    //     echo json_encode($result);


    // }

?>