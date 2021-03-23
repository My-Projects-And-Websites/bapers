<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    include '../php/connection.php';

    date_default_timezone_set('Europe/London');

    $_SESSION['from_date'] = $_POST['from-date'];
    $_SESSION['to_date'] = $_POST['to-date'];
    $_SESSION['datetime_of_generate'] = date('Y-m-d H:i:s');

    $from_date = $_SESSION['from_date'];
    $to_date = $_SESSION['to_date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Summary Performance Report</title>

    <link rel="stylesheet" href="../css/report_templates/sum_performance/sum_performance.css">
    <link rel="stylesheet" href="../css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <h1>Summary Performance Report</h1>
            <p>
                Report for day and night shifts of BIPL staff

                <?php
                    if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                        echo ' | ' . $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                    }
                ?>
            </p>
        </div>
        <div class="body-section">
            <div class="body-section-tags">
                <span>Date</span>
                <span>Copy Room</span>
                <span>Development</span>
                <span>Finishing</span>
                <span>Packing</span>
            </div>
            <ul class="body-section-shift-one">
                <?php
                    // $shift_one_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                    // FROM Job_Task
                    // INNER JOIN Task ON job_task.Tasktask_id
                    // WHERE job_task.task_date >= ? AND job_task.task_date <= ?
                    // AND job_task.start_time >= '05:00' AND job_task.start_time <= '14:30'";

                    $shift_one_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                    $shift_one_query = $connect->prepare($shift_one_sql);
                    $shift_one_query->bind_param("ss", $from_date, $to_date);
                    $shift_one_query->execute();

                    $shift_one_result = $shift_one_query->get_result();

                    while ($shift_one_row = $shift_one_result->fetch_assoc()) {
                        echo $shift_one_row['task_date'];
                    }
                ?>
            </ul>
        </div>
    </main>

    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php'">Return</button>
        <button class="print-btn">Print</button>
    </div>
    <script src="../js/print.js"></script>
</body>
</html>