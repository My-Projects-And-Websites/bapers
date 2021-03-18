<?php 
    $customer_id = $_POST['job-customer-id'];
    $instructions = $_POST['job-instructions'];
    $urgency = $_POST['job-urgency'];
    $deadline = "";
    $status = "Pending";

    $tasks = $_POST['task'];

    date_default_timezone_set('Europe/London');

    if ($urgency == "Yes") {
        $deadline = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $urgency = 1;
    }
    else {
        $deadline = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $urgency = 0;
    }

    include('connection.php'); //connect db

    $sql = "INSERT INTO Job (job_id, job_urgency, job_deadline, special_instructions, job_status, expected_finish, actual_finish, Customercust_id) 
    VALUES (null, '$urgency', '$deadline' , '$instructions', '$status', null, null, '$customer_id')"; // insert the new job.
    $job_result = mysqli_query($connect, $sql); //run the insert query

    $get_job_id_result = mysqli_query($connect, 'SELECT job_id FROM Job ORDER BY job_id DESC LIMIT 1');
    $get_job_row = mysqli_fetch_assoc($get_job_id_result);
    $job_id = $get_job_row['job_id'];

    foreach ($tasks as $task) {
        $sql_insert_job_task = "INSERT INTO Job_Task (JobTaskID, Jobjob_id, Tasktask_id, start_time, task_date, task_status)
        VALUES (null, '$job_id', '$task', null, null, 'Pending')";
        $job_task_result = mysqli_query($connect, $sql_insert_job_task);
    }

    if (!$job_result){
        die('Error: ' . mysqli_error($connect)); //if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Job assigned successfully!");location.href="../accept_job.php";</script>;';
    }

    mysqli_close($connect); //close the db
?>