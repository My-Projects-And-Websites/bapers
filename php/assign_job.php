<?php 
    $customer_id = $_POST['job-customer-id'];
    $instructions = $_POST['job-instructions'];
    $urgency = $_POST['job-urgency'];
    $deadline = "";
    $status = "Pending";
    $time_of_order = date('Y-m-d H:i:s');

    $task_set = $_POST['task'];
    $staff_set = $_POST['assign-task'];
    $staff_counter = 0;

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

    $get_job_id_result = mysqli_query($connect, 'SELECT job_id FROM Job ORDER BY job_id DESC LIMIT 1');
    $get_job_row = mysqli_fetch_assoc($get_job_id_result);
    
    if (!isset($get_job_row)) {
        $job_id = 1;
    }
    else {
        $job_id = $get_job_row['job_id'];
    }

    $job_price = 0;
    foreach ($task_set as $task) {
        $staff_id = $staff_set[$staff_counter];

        $sql_insert_job_task = "INSERT INTO Job_Task (JobTaskID, Jobjob_id, Tasktask_id, start_time, task_date, task_status, Staffstaff_id)
        VALUES (null, '$job_id', '$task', null, null, 'Pending', '$staff_id')";
        $job_task_result = mysqli_query($connect, $sql_insert_job_task);

        $task_sql = "SELECT task_price FROM Task WHERE task_id = ?";
        $task_query = $connect->prepare($task_sql);
        $task_query->bind_param("i", $task);
        $task_query->execute();
        $task_result = $task_query->get_result();
        $task_row = mysqli_fetch_row($task_result);
        
        $job_price += $task_row[0];

        $staff_counter++;
    }

    $sql = "INSERT INTO Job (job_id, job_urgency, job_deadline, special_instructions, job_status, expected_finish, actual_finish, order_time, total_price, discount_amount, alert_flag, Customercust_id) 
    VALUES (null, '$urgency', '$deadline' , '$instructions', '$status', null, null, '$time_of_order', $job_price, null, 0, '$customer_id')"; // insert the new job.
    $job_result = mysqli_query($connect, $sql); //run the insert query

    $paym_sql = "INSERT INTO Payment (payment_id, payment_total, payment_late, payment_alert, payment_discount, discount_rate, payment_status, Customercust_id)
    VALUES (null, ?, 0, 0, null, 0, 'Pending', ?)";
    $paym_query = $connect->prepare($paym_sql);
    $paym_query->bind_param("di", $job_price, $customer_id);
    $paym_query->execute();

    if (!$job_result){
        die('Error: ' . mysqli_error($connect)); //if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Job assigned successfully!");location.href="../accept_job.php";</script>;';
    }

    mysqli_close($connect); //close the db
?>