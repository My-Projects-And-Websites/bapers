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

    // $staff_query = "SELECT DISTINCT job_task.Staffstaff_id, job_task.task_date, staff.staff_fname, staff_sname
    // FROM Job_Task
    // INNER JOIN Staff ON job_task.Staffstaff_id = staff.staff_id
    // WHERE job_task.task_date >= '$from_date' AND job_task.task_date <= '$to_date'";
    // $staff_result = $connect->query($staff_query);

    $staff_query = "SELECT job_task.Staffstaff_id, job_task.task_date, staff.staff_fname, staff_sname
    FROM Job_Task
    INNER JOIN Staff ON job_task.Staffstaff_id = staff.staff_id
    WHERE job_task.task_date >= '$from_date' AND job_task.task_date <= '$to_date' AND job_task.task_status = 'Completed'
    GROUP BY job_task.Staffstaff_id";
    $staff_result = $connect->query($staff_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Individual Performance Report</title>

    <link rel="stylesheet" href="../css/report_templates/indi_performance/indi_performance.css">
    <link rel="stylesheet" href="../css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <h1>Individual Performance Report</h1>
            <p>
                Report for performances of all BIPL staff

                <?php
                    if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                        echo ' | ' . $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                    }
                ?>
            </p>
        </div>
        <div class="body-section">
            <div class="body-section-tags">
                <span>Name</span>
                <span>Task ID</span>
                <span>Department</span>
                <span>Date</span>
                <span>Start Time</span>
                <span>Time Taken</span>
                <span>Total</span>
            </div>
            <ul class="body-section-list">
                <?php
                    while ($staff_row = mysqli_fetch_assoc($staff_result)) {
                        echo '<li><span class="staff-name">' . $staff_row['Staffstaff_id'] . ' | ' . $staff_row['staff_fname'] . ' ' . $staff_row['staff_sname'] . '</span>';

                        $staff_id = $staff_row['Staffstaff_id'];

                        $task_sql = "SELECT job_task.Tasktask_id, job_task.start_time, job_task.finish_time, job_task.task_date, task.task_location, job_task.Staffstaff_id
                        FROM Job_Task
                        INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                        WHERE job_task.task_date >= ? AND job_task.task_date <= ?
                        AND job_task.Staffstaff_id = ? AND job_task.task_status = 'Completed'
                        ORDER BY job_task.Tasktask_id";
                        $task_query = $connect->prepare($task_sql);
                        $task_query->bind_param("ssi", $from_date, $to_date, $staff_id);
                        $task_query->execute();
                        $task_result = $task_query->get_result();

                        $total_time = 0;
                        $loop_count = 0;

                        while ($task_row = $task_result->fetch_assoc()) {
                            $start_time_convert = new DateTime($task_row['start_time']);
                            $finish_time_convert = new DateTime($task_row['finish_time']);

                            $time_taken = $start_time_convert->diff($finish_time_convert);

                            echo '<span class="grid-place-1">' . $task_row['Tasktask_id'] . '</span>' .
                            '<span class="grid-place-2">' . $task_row['task_location'] . '</span>' .
                            '<span class="grid-place-3">' . $task_row['task_date'] . '</span>' .
                            '<span class="grid-place-4">' . $task_row['start_time'] . '</span>' .
                            '<span class="grid-place-5">' . $time_taken->format("%i") . ' min</span>';

                            $total_time += (int)$time_taken->format("%i");
                            $hour_count = 0;

                            $loop_count++;

                            if ($loop_count == mysqli_num_rows($task_result)) {
                                if ($total_time > 60) {
                                    $total_time = $total_time - 60;
                                    $hour_count++;

                                    echo '<span class="grid-place-6">' . $hour_count . ' h ' . $total_time . ' min</span>';
                                }
                                else {
                                    echo '<span class="grid-place-6">' . $total_time . ' min</span>';
                                }
                            }
                        }

                        echo "</li>";
                    }
                ?>
            </ul>
        </div>
    </main>

    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php';">Return</button>
        <button class="print-btn">Print</button>
    </div>
    <script src="../js/print.js"></script>
</body>
</html>