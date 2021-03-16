<?php 
    $customer_id = $_POST['job-customer-id'];
    $instructions = $_POST['job-instructions'];
    $urgency = $_POST['job-urgency'];
    $deadline = "";
    $status = "Pending";

    date_default_timezone_set('Europe/London');

    if ($urgency == "Yes") {
        $deadline = date('Y-m-d H:i:s', strtotime('+6 hours'));
    }
    else {
        $deadline = date('Y-m-d H:i:s', strtotime('+24 hours'));
    }

    include('../php/connection.php'); //connect db

    $sql = "INSERT INTO Job (job_id, job_urgency, job_deadline, special_instructions, job_status, expected_finish, actual_finish, Customercust_id) 
    VALUES (null, '$urgency', '$deadline' , '$instructions', '$status', null, null, '$customer_id')"; //insert the new job.
    $job_result = mysqli_query($connect, $sql); //run the insert query

    if (!$job_result){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Job assigned successfully!");location.href="../accept_job.php";</script>;';
    }

    mysqli_close($connect); //close the db
?>