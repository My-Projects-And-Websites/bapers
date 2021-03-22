<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    include '../php/connection.php';

    $customer = $_SESSION['cust_id'];
    $from = $_SESSION['from_date'];
    $to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($_SESSION['to_date'])));

    if (empty($_SESSION['from_date']) || empty($_SESSION['to_date'])) {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer'";
    }
    else {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer' AND order_time >= '$from' AND order_time <= '$to'";
    }

    $find_customer_result = $connect->query($find_customer);
    
    $customer_record = "SELECT cust_fname, cust_sname FROM Customer WHERE cust_id = " . $_SESSION['cust_id'];
    $customer_record_result = $connect->query($customer_record);
    $customer_record_row = mysqli_fetch_row($customer_record_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Report | <?php echo $customer_record_row[0] . ' ' . $customer_record_row[1]; ?> </title>

    <link rel="stylesheet" href="../css/report_templates/customer_report/customer_report.css">
    <link rel="stylesheet" href="../css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <h1>Customer Report</h1>
            <p>
                Report for <?php echo $customer_record_row[0] . ' ' . $customer_record_row[1]; ?> | 

                <?php
                    if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                        echo $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                    }
                ?>
            </p>
        </div>
        <div class="body-section">
            <div class="body-section-tags">
                <span>Job ID</span>
                <span>Urgent</span>
                <span>Deadline</span>
                <span>Expected Finish</span>
                <span>Actual Finish</span>
            </div>
            <ul class="list-of-jobs-ordered">
                <?php
                    while ($find_customer_row = mysqli_fetch_assoc($find_customer_result)) {
                        $job_id_join_task = $find_customer_row['job_id'];

                        if ($find_customer_row['job_urgency'] == 0) {
                            $find_customer_row['job_urgency'] = "No";
                        }
                        else {
                            $find_customer_row['job_urgency'] = "Yes";
                        }

                        $task_join_query = "SELECT job_task.Tasktask_id, job_task.task_status, task.task_id, task.task_desc, task.task_location, task.task_price, task.task_duration 
                        FROM Job_Task
                        INNER JOIN task 
                        ON job_task.Tasktask_id = task.task_id
                        WHERE job_task.Jobjob_id = $job_id_join_task";
                        $task_join_query_results = $connect->query($task_join_query);

                        echo '<li class=job-' . $job_id_join_task . '>' .
                        '<span>' . $job_id_join_task .'</span>' . 
                        '<span>' . $find_customer_row['job_urgency'] . '</span>' .
                        '<span>' . $find_customer_row['job_deadline'] . '</span>' .
                        '<span>' . $find_customer_row['expected_finish'] . '</span>' .
                        '<span>' . $find_customer_row['actual_finish'] . '</span>' .
                        '</li>' .
                        '<div class=task-for-each-job>';

                        while ($task_join_query_row = mysqli_fetch_assoc($task_join_query_results)) {
                            echo '<span>' . $task_join_query_row['Tasktask_id'] . '</span>' .
                                 '<span>' . $task_join_query_row['task_desc'] . '</span>' .
                                 '<span>' . $task_join_query_row['task_status'] . '</span>' .
                                 '<span>' . $task_join_query_row['task_location'] . '</span>' .
                                 '<span>£' . $task_join_query_row['task_price'] . '</span>' .
                                 '<span>' . $task_join_query_row['task_duration'] . ' minutes</span>';
                        }

                        echo '</div>';
                    }
                ?>
            </ul>
        </div>
    </main>

    <button class="print-btn">Print</button>

    <script src="../js/print.js"></script>
</body>
</html>