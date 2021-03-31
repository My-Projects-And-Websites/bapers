<?php 
    //initalize of assign job
    $customer_id = $_POST['job-customer-id'];
    $instructions = $_POST['job-instructions'];
    $urgency = $_POST['job-urgency'];
    $hours = $_POST['urgency-hours'];// how urgen that customer need
    if (isset($_POST['times-amount'])) {
        $times = $_POST['times-amount'];//the quantity of the tasks.
    } 
    $deadline = "";
    $status = "Pending";
    $time_of_order = date('Y-m-d H:i:s');
    $task_set = $_POST['task'];
    $staff_set = $_POST['assign-task'];
    $staff_counter = $times_counter = 0;

    date_default_timezone_set('Europe/London');
    //set urgency status
    if ($urgency == "Yes") {

        if (empty($hours)) {
            $deadline = date('Y-m-d H:i:s', strtotime('+6 hours'));
        }
        else {
            $deadline = date('Y-m-d H:i:s', strtotime('+' . $hours . ' hours'));
        }

        $urgency = 1;
    }
    else {
        $deadline = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $urgency = 0;
    }

    include('connection.php'); //connect db

    $job_price = 0;
    $i=0;
    foreach ($task_set as $t) {//get the single price for each task
        $task_sql = "SELECT task_price FROM Task WHERE task_id = ?";
        $task_query = $connect->prepare($task_sql);
        $task_query->bind_param("i", $t);
        $task_query->execute();
        $task_result = $task_query->get_result();
        $task_row = mysqli_fetch_row($task_result);
        $job_price += $task_row[0]*$times[$i];//times quantities values
        $i++;
    }

    $job_sql_num_rows = "SELECT job_id FROM Job";
    $job_query_num_rows = $connect->prepare($job_sql_num_rows);
    $job_query_num_rows->execute();
    $job_result_num_rows = $job_query_num_rows->get_result();

    $num_rows = mysqli_num_rows($job_result_num_rows) + 1; //auto increments
    $job_id_char = "JOB#" . strval($num_rows); //the style of the job_id
    $paym_id_char = "PAY#" . strval($num_rows);// the varchr style of payment ID

    $sql = "INSERT INTO Job (job_id, job_id_char, job_urgency, job_deadline, special_instructions, job_status, expected_finish, actual_finish, order_time, total_price, discount_amount, alert_flag, Customercust_id) 
    VALUES (null, '$job_id_char', '$urgency', '$deadline' , '$instructions', '$status', null, null, '$time_of_order', $job_price, null, 0, '$customer_id')"; // insert the new job.
    $job_result = mysqli_query($connect, $sql); //run the insert query

    $get_job_id_result = mysqli_query($connect, 'SELECT job_id FROM Job ORDER BY job_id DESC LIMIT 1');
    $get_job_row = mysqli_fetch_assoc($get_job_id_result);
    $job_id = $get_job_row['job_id'];

    foreach ($task_set as $task) {//assign job information
        $staff_id = $staff_set[$staff_counter];

        for ($j = 0; $j < $times[$times_counter]; $j++) {
            $sql_insert_job_task = "INSERT INTO Job_Task (JobTaskID, Jobjob_id, Tasktask_id, start_time, task_date, task_status, Staffstaff_id)
            VALUES (null, '$job_id', '$task', null, null, 'Pending', '$staff_id')";
            $job_task_result = mysqli_query($connect, $sql_insert_job_task);
        }

        $staff_counter++;
        $times_counter++;
    }

    $paym_sql = "INSERT INTO Payment (payment_id, payment_id_char, payment_total, payment_late, payment_alert, payment_discount, discount_rate, payment_status, Customercust_id)
    VALUES (null, ?, ?, 0, 0, null, 0, 'Pending', ?)";
    $paym_query = $connect->prepare($paym_sql);
    $paym_query->bind_param("sdi", $paym_id_char, $job_price, $customer_id);
    $paym_query->execute(); //push all details to databse

    if (!$job_result){
        die('Error: ' . mysqli_error($connect)); //if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Job assigned successfully!");location.href="../accept_job.php";</script>;';
        // echo gettype(mysqli_num_rows($job_result_num_rows));
    }

    mysqli_close($connect); //close the db
?>