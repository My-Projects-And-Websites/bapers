<?php
/*Requirement:*/
/*alert Shift and/or Office Manager (by, for example, displaying a visual cue with appropriate 
text) if the expected time to complete outstanding tasks for any job is likely to exceed the set time period, 
i.e. if the deadline for the job is not likely to be met; the alerts should be performed only for these two 
user roles.*/
include ('../php/connection.php');//connect to database

$job_status = "SELECT job_id,job_deadline,special_instructions,alert_flag from job where TIMESTAMPDIFF(MINUTE, job_deadline , CURRENT_TIMESTAMP())<=5"; //5 mins left from the deadline 


$result = $connect->query($job_status);
$row = mysqli_fetch_all($result,MYSQLI_ASSOC);
if ($row != null){ 
    $reciever = "SELECT staff_id from staff where staff_role = 'Office Manager' OR staff_role = 'Shift Manager'";// only message the 'Office Manager' and 'Shirft Manager'
    $result2 = $connect->query($reciever);
    $row2 = mysqli_fetch_all($result2,MYSQLI_NUM);
    $user = array_column($row2,'0');// the user group all in fetch_all array position '0',in this step change to array in column and can do the traverse.
    foreach($row as $info){
        if($info['alert_flag'] == 0){
            $update_flag = "UPDATE job SET alert_flag = 1 where job_id = '$info[job_id]' ";// in case that resend the message.
            mysqli_query($connect,$update_flag);
            $message = "Warning!The Job: ".$info['special_instructions']." with Job ID: ".$info['job_id']." only has 5mins left to the deadline.It is not likely to met the deadline, Please be notice.";// message template
         for($i=0;$i<=sizeof($user);$i++){ // this part use for test print out, after will do functions.
                $alert_query= "INSERT into `message`(message_id,message_desc,message_status,send_time,Staffstaff_id) values (null,'$message',0,now(),'$user[$i]')";
                mysqli_query($connect,$alert_query);
                }
        }
    }   
}
?>