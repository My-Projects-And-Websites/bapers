<?php
/*Requirement 1:*/
/*alert Shift and/or Office Manager (by, for example, displaying a visual cue with appropriate 
text) if the expected time to complete outstanding tasks for any job is likely to exceed the set time period, 
i.e. if the deadline for the job is not likely to be met; the alerts should be performed only for these two 
user roles.*/
function deadline_alert(){
    include ('connection.php');//connect to database
    $job_status = "SELECT job_id,job_deadline,special_instructions,alert_flag,job_status,order_time,Customercust_id from job where TIMESTAMPDIFF(MINUTE, job_deadline , CURRENT_TIMESTAMP())<=5"; //5 mins left from the deadline 


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
        $EMAIL_reciever = "SELECT username_login from staff where staff_role = 'Office Manager' OR staff_role = 'Shift Manager'";// send the email to office manager   
        $reuslt3 = $connect->query($EMAIL_reciever);
        $row_email = mysqli_fetch_all($reuslt3,MYSQLI_ASSOC);
        $email_user = array_column($row_email,'username_login');
        include('send_email.php');
        job_deadline_email($email_user,$row[0]['job_id'],$row[0]['job_deadline'],$row[0]['special_instructions'],$row[0]['Customercust_id'],$row[0]['order_time'],$row[0]['job_status']);}
    
}

/*Requirement 2:*/
/*The late payments should be detected automatically by the system and Office Manager should be 
alerted. If the Office Manager is logged in, the system should generate alert as pop-up window, warning 
at regular intervals of 15 minutes until the Office Manager acknowledges the receipt of the warning. 
No refunds are to be implemented in BAPERS.*/
function late_payment_alert(){
    include ('connection.php');//connect to database
    $payment_status = "SELECT payment.payment_id,payment.payment_total,payment_alert,customer.cust_id,customer.cust_fname,cust_sname,cust_email,cust_address,cust_mobile,payment.payment_type from 
    payment,customer where payment.payment_late = 1 and customer.cust_id = payment.	Customercust_id"; //The flag of payment is late.
    $payment_result = $connect->query($payment_status);
    $payment_row = mysqli_fetch_all($payment_result,MYSQLI_ASSOC);
    if ($payment_row != null){ 
        $reciever = "SELECT staff_id from staff where staff_role = 'Office Manager' ";// only message the 'Office Manager'
        $result = $connect->query($reciever);
        $row2 = mysqli_fetch_all($result,MYSQLI_NUM);
        $user = array_column($row2,'0');// the user group all in fetch_all array position '0',in this step change to array in column and can do the traverse.
        foreach($payment_row as $payment_info){
                $message = "Late Payment Alert!\nThe Payment ID: ".$payment_info['payment_id']." has been late!\nOrder by CustomerID: ".$payment_info['cust_id']."\n 
                 Customer Details:\nName: ".$payment_info['cust_fname']." ".$payment_info['cust_sname']."\nEmail: ".$payment_info['cust_email']."\nAddress: ".$payment_info['cust_address']."\nPhone No: ".$payment_info['cust_mobile']."\nTotal amount due: ￡".$payment_info['payment_total'];// message template
                 if($payment_info['payment_alert'] == 0){
                    $update_flag2 = "UPDATE payment SET payment_alert = 1 where payment_id = '$payment_info[payment_id]' ";// in case that resend the message.
                    mysqli_query($connect,$update_flag2); 
                    for($i=0;$i<=sizeof($user);$i++){ // this part use for test print out, after will do functions.
                    $alert_query= "INSERT into `message`(message_id,message_desc,message_status,send_time,Staffstaff_id) values (null,'$message',0,now(),'$user[$i]')";
                    mysqli_query($connect,$alert_query);
                    }
                }
            }
            $EMAIL_reciever = "SELECT username_login from staff where staff_role = 'Office Manager' ";// send the email to office manager   
            $reuslt3 = $connect->query($EMAIL_reciever);
            $row_email = mysqli_fetch_all($reuslt3,MYSQLI_ASSOC);
            $email_user = array_column($row_email,'username_login');
            include('send_email.php'); 
            late_payment_email($email_user,$payment_row[0]['payment_id'],$payment_row[0]['cust_id'],$payment_row[0]['cust_fname']." ".$payment_row[0]['cust_sname'],$payment_row[0]['cust_email'],$payment_row[0]['cust_mobile']
           ,$payment_row[0]['cust_address'],$payment_row[0]['payment_total'],date("d/m/Y"),$payment_row[0]['payment_type']);
        }
    }
?>