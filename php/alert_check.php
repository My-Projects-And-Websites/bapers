<?php
include('../php/connection.php');

$message_status = 'SELECT * FROM `message` where message_status = 0';// get all the message that havent(status = 0) pop out.
$message = $connect->query($message_status);
$row = mysqli_fetch_all($message,MYSQLI_ASSOC);
print_r($row);
/* TO DO */
/*foreach($row as $send){
    POP OUT(WITH MESSGAE IN 'alert.php')
    
    and update the status
    $update_status = "UPDATE message SET massage = 1 where message_id = '$send[job_id]' ";// in case that re-pop the message.
    mysqli_query($connect,$update_flag);
}
// HERE NEED A WINDOW OR A PAGE TO SHOW OUT THE MESSAGE.
?>