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
            <div class="day-shift-one">
                <h2>Day Shift One (5:00 AM - 14:30 PM)</h2>
                <div class="body-section-tags">
                    <span>Date</span>
                    <span>Copy Room</span>
                    <span>Development</span>
                    <span>Finishing</span>
                    <span>Packing</span>
                    <span>Total Hours</span>
                </div>
                <ul class="body-section-shift-one">
                    <?php
                        $shift_one_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_one_get_date_query = $connect->prepare($shift_one_get_date_sql);
                        $shift_one_get_date_query->bind_param("ss", $from_date, $to_date);
                        $shift_one_get_date_query->execute();
                        $shift_one_get_date_result = $shift_one_get_date_query->get_result();

                        $temp_array_one = [];

                        while ($shift_one_get_date_row = $shift_one_get_date_result->fetch_assoc()) {
                            echo '<li><span>' . $shift_one_get_date_row['task_date'] . '</span>';

                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            $shift_one_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '05:00' AND job_task.start_time <= '14:30'
                            AND job_task.task_status = 'Completed'";
                            $shift_one_get_times_query = $connect->prepare($shift_one_get_times_sql);
                            $shift_one_get_times_query->bind_param("s", $shift_one_get_date_row['task_date']);
                            $shift_one_get_times_query->execute();
                            $shift_one_get_times_result = $shift_one_get_times_query->get_result();

                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            while ($shift_one_get_times_row = $shift_one_get_times_result->fetch_assoc()) {
                                $start_time_convert = new DateTime($shift_one_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_one_get_times_row['finish_time']);

                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                if ($shift_one_get_times_row['task_location'] == "Copy Room") {
                                    $copy_room_total_time += (int)$time_taken->format("%i");
                                    $copy_room_hour_count = 0;
                                }
                                else if ($shift_one_get_times_row['task_location'] == "Development Area") {
                                    $development_total_time += (int)$time_taken->format("%i");
                                    $development_hour_count = 0;
                                }
                                else if ($shift_one_get_times_row['task_location'] == "Packing Departments") {
                                    $packing_total_time += (int)$time_taken->format("%i");
                                    $packing_hour_count = 0;
                                }
                                else if ($shift_one_get_times_row['task_location'] == "Finishing Room") {
                                    $finishing_total_time += (int)$time_taken->format("%i");
                                    $finishing_hour_count = 0;
                                }

                                $time_arr_one = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                $loop_count++;

                                if ($loop_count == mysqli_num_rows($shift_one_get_times_result)) {

                                    if ($copy_room_total_time >= 60) {
                                        while ($copy_room_total_time >= 60) {
                                            $copy_room_total_time = $copy_room_total_time - 60;
                                            $copy_room_hour_count++;
                                        }

                                        echo '<span class="grid-place-1">' . $copy_room_hour_count . ' hr ' . $copy_room_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-1">' . $copy_room_total_time . ' min</span>';
                                    }

                                    if ($development_total_time >= 60) {
                                        $development_total_time = $development_total_time - 60;
                                        $development_hour_count++;

                                        while ($development_total_time >= 60) {
                                            $development_total_time = $development_total_time - 60;
                                            $development_hour_count++;
                                        }

                                        echo '<span class="grid-place-2">' . $development_hour_count . ' hr ' . $development_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-2">' . $development_total_time . ' min</span>';
                                    }

                                    if ($packing_total_time >= 60) {
                                        $packing_total_time = $packing_total_time - 60;
                                        $packing_hour_count++;

                                        while ($packing_total_time >= 60) {
                                            $packing_total_time = $packing_total_time - 60;
                                            $packing_hour_count++;
                                        }

                                        echo '<span class="grid-place-3">' . $packing_hour_count . ' hr ' . $packing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-3">' . $packing_total_time . ' min</span>';
                                    }

                                    if ($finishing_total_time >= 60) {
                                        $finishing_total_time = $finishing_total_time - 60;
                                        $finishing_hour_count++;

                                        while ($finishing_total_time >= 60) {
                                            $finishing_total_time = $finishing_total_time - 60;
                                            $finishing_hour_count++;
                                        }

                                        echo '<span class="grid-place-4">' . $finishing_hour_count . ' hr ' . $finishing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-4">' . $finishing_total_time . ' min</span>';
                                    }
                                }

                                for ($i = 0; $i < count($time_arr_one); $i++) {
                                    $total_time += $time_arr_one[$i];
                                }
                            }

                            array_push($temp_array_one, $total_time);

                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    $total_time_hour_count++;
                                }

                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-two">
                <h2>Day Shift Two (14:30 PM - 22:00 PM)</h2>
                <div class="body-section-tags">
                    <span>Date</span>
                    <span>Copy Room</span>
                    <span>Development</span>
                    <span>Finishing</span>
                    <span>Packing</span>
                    <span>Total Hours</span>
                </div>
                <ul class="body-section-shift-two">
                    <?php
                        $shift_two_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_two_get_date_query = $connect->prepare($shift_two_get_date_sql);
                        $shift_two_get_date_query->bind_param("ss", $from_date, $to_date);
                        $shift_two_get_date_query->execute();
                        $shift_two_get_date_result = $shift_two_get_date_query->get_result();

                        $temp_array_two = [];

                        while ($shift_two_get_date_row = $shift_two_get_date_result->fetch_assoc()) {
                            echo '<li><span>' . $shift_two_get_date_row['task_date'] . '</span>';

                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            $shift_two_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '14:30' AND job_task.start_time <= '22:00'
                            AND job_task.task_status = 'Completed'";
                            $shift_two_get_times_query = $connect->prepare($shift_two_get_times_sql);
                            $shift_two_get_times_query->bind_param("s", $shift_two_get_date_row['task_date']);
                            $shift_two_get_times_query->execute();
                            $shift_two_get_times_result = $shift_two_get_times_query->get_result();

                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            while ($shift_two_get_times_row = $shift_two_get_times_result->fetch_assoc()) {
                                $start_time_convert = new DateTime($shift_two_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_two_get_times_row['finish_time']);

                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                if ($shift_two_get_times_row['task_location'] == "Copy Room") {
                                    $copy_room_total_time += (int)$time_taken->format("%i");
                                    $copy_room_hour_count = 0;
                                }
                                else if ($shift_two_get_times_row['task_location'] == "Development Area") {
                                    $development_total_time += (int)$time_taken->format("%i");
                                    $development_hour_count = 0;
                                }
                                else if ($shift_two_get_times_row['task_location'] == "Packing Departments") {
                                    $packing_total_time += (int)$time_taken->format("%i");
                                    $packing_hour_count = 0;
                                }
                                else if ($shift_two_get_times_row['task_location'] == "Finishing Room") {
                                    $finishing_total_time += (int)$time_taken->format("%i");
                                    $finishing_hour_count = 0;
                                }

                                $time_arr_two = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                $loop_count++;

                                if ($loop_count == mysqli_num_rows($shift_two_get_times_result)) {

                                    if ($copy_room_total_time >= 60) {

                                        while ($copy_room_total_time >= 60) {
                                            $copy_room_total_time = $copy_room_total_time - 60;
                                            $copy_room_hour_count++;
                                        }

                                        echo '<span class="grid-place-1">' . $copy_room_hour_count . ' hr ' . $copy_room_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-1">' . $copy_room_total_time . ' min</span>';
                                    }

                                    if ($development_total_time >= 60) {
                                        $development_total_time = $development_total_time - 60;
                                        $development_hour_count++;

                                        while ($development_total_time >= 60) {
                                            $development_total_time = $development_total_time - 60;
                                            $development_hour_count++;
                                        }

                                        echo '<span class="grid-place-2">' . $development_hour_count . ' hr ' . $development_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-2">' . $development_total_time . ' min</span>';
                                    }

                                    if ($packing_total_time >= 60) {
                                        $packing_total_time = $packing_total_time - 60;
                                        $packing_hour_count++;

                                        while ($packing_total_time >= 60) {
                                            $packing_total_time = $packing_total_time - 60;
                                            $packing_hour_count++;
                                        }

                                        echo '<span class="grid-place-3">' . $packing_hour_count . ' hr ' . $packing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-3">' . $packing_total_time . ' min</span>';
                                    }

                                    if ($finishing_total_time >= 60) {
                                        $finishing_total_time = $finishing_total_time - 60;
                                        $finishing_hour_count++;

                                        while ($finishing_total_time >= 60) {
                                            $finishing_total_time = $finishing_total_time - 60;
                                            $finishing_hour_count++;
                                        }

                                        echo '<span class="grid-place-4">' . $finishing_hour_count . ' hr ' . $finishing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-4">' . $finishing_total_time . ' min</span>';
                                    }
                                }

                                for ($i = 0; $i < count($time_arr_two); $i++) {
                                    $total_time += $time_arr_two[$i];
                                }
                            }

                            array_push($temp_array_two, $total_time);


                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    $total_time_hour_count++;
                                }

                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-three">
                <h2>Night Shift One (22:00 PM - 5:00 AM)</h2>
                <div class="body-section-tags">
                    <span>Date</span>
                    <span>Copy Room</span>
                    <span>Development</span>
                    <span>Finishing</span>
                    <span>Packing</span>
                    <span>Total Hours</span>
                </div>
                <ul class="body-section-shift-three">
                    <?php
                        $shift_three_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_three_get_date_query = $connect->prepare($shift_three_get_date_sql);
                        $shift_three_get_date_query->bind_param("ss", $from_date, $to_date);
                        $shift_three_get_date_query->execute();
                        $shift_three_get_date_result = $shift_three_get_date_query->get_result();

                        $temp_array_three = [];

                        while ($shift_three_get_date_row = $shift_three_get_date_result->fetch_assoc()) {
                            echo '<li><span>' . $shift_three_get_date_row['task_date'] . '</span>';

                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            $shift_three_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '22:00' AND job_task.start_time <= '05:00'
                            AND job_task.task_status = 'Completed'";
                            $shift_three_get_times_query = $connect->prepare($shift_three_get_times_sql);
                            $shift_three_get_times_query->bind_param("s", $shift_three_get_date_row['task_date']);
                            $shift_three_get_times_query->execute();
                            $shift_three_get_times_result = $shift_three_get_times_query->get_result();

                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            while ($shift_three_get_times_row = $shift_three_get_times_result->fetch_assoc()) {
                                $start_time_convert = new DateTime($shift_three_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_three_get_times_row['finish_time']);

                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                if ($shift_three_get_times_row['task_location'] == "Copy Room") {
                                    $copy_room_total_time += (int)$time_taken->format("%i");
                                    $copy_room_hour_count = 0;
                                }
                                else if ($shift_three_get_times_row['task_location'] == "Development Area") {
                                    $development_total_time += (int)$time_taken->format("%i");
                                    $development_hour_count = 0;
                                }
                                else if ($shift_three_get_times_row['task_location'] == "Packing Departments") {
                                    $packing_total_time += (int)$time_taken->format("%i");
                                    $packing_hour_count = 0;
                                }
                                else if ($shift_three_get_times_row['task_location'] == "Finishing Room") {
                                    $finishing_total_time += (int)$time_taken->format("%i");
                                    $finishing_hour_count = 0;
                                }

                                $time_arr_three = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                $loop_count++;

                                if ($loop_count == mysqli_num_rows($shift_three_get_times_result)) {

                                    if ($copy_room_total_time >= 60) {

                                        while ($copy_room_total_time >= 60) {
                                            $copy_room_total_time = $copy_room_total_time - 60;
                                            $copy_room_hour_count++;
                                        }

                                        echo '<span class="grid-place-1">' . $copy_room_hour_count . ' hr ' . $copy_room_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-1">' . $copy_room_total_time . ' min</span>';
                                    }

                                    if ($development_total_time >= 60) {
                                        $development_total_time = $development_total_time - 60;
                                        $development_hour_count++;

                                        while ($development_total_time >= 60) {
                                            $development_total_time = $development_total_time - 60;
                                            $development_hour_count++;
                                        }

                                        echo '<span class="grid-place-2">' . $development_hour_count . ' hr ' . $development_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-2">' . $development_total_time . ' min</span>';
                                    }

                                    if ($packing_total_time >= 60) {
                                        $packing_total_time = $packing_total_time - 60;
                                        $packing_hour_count++;

                                        while ($packing_total_time >= 60) {
                                            $packing_total_time = $packing_total_time - 60;
                                            $packing_hour_count++;
                                        }

                                        echo '<span class="grid-place-3">' . $packing_hour_count . ' hr ' . $packing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-3">' . $packing_total_time . ' min</span>';
                                    }

                                    if ($finishing_total_time >= 60) {
                                        $finishing_total_time = $finishing_total_time - 60;
                                        $finishing_hour_count++;

                                        while ($finishing_total_time >= 60) {
                                            $finishing_total_time = $finishing_total_time - 60;
                                            $finishing_hour_count++;
                                        }

                                        echo '<span class="grid-place-4">' . $finishing_hour_count . ' hr ' . $finishing_total_time . ' min</span>';
                                    }
                                    else {
                                        echo '<span class="grid-place-4">' . $finishing_total_time . ' min</span>';
                                    }
                                }

                                for ($i = 0; $i < count($time_arr_three); $i++) {
                                    $total_time += $time_arr_three[$i];
                                }
                            }

                            array_push($temp_array_three, $total_time);

                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    $total_time_hour_count++;
                                }

                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-three">
                <h2> For period (
                    <?php
                        if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                            echo $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                        }
                    ?>
                    )
                </h2>
                <div class="body-section-tags-last">
                    <span>Date</span>
                    <span>Day Shift 1</span>
                    <span>Day Shift 2</span>
                    <span>Night Shift 1</span>
                    <span>Total Hours</span>
                </div>
                <ul class="period-date">
                    <?php
                        $period_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $period_date_query = $connect->prepare($period_date_sql);
                        $period_date_query->bind_param("ss", $from_date, $to_date);
                        $period_date_query->execute();
                        $period_date_result = $period_date_query->get_result();

                        echo '<div class=date-shift>';
                        while ($period_date_row = $period_date_result->fetch_assoc()) {
                            echo '<li>' . $period_date_row['task_date'] . '</li>';
                        }
                        echo '</div>';

                        $i = 0;
                        echo '<div class=grid-place-total>';
                        while ($i != mysqli_num_rows($period_date_result)) {
                            $shift_total = 0;
                            $shift_total_hour_count = 0;

                            $shift_total = $temp_array_one[$i] + $temp_array_two[$i] + $temp_array_three[$i];

                            if ($shift_total >= 60) {

                                while ($shift_total >= 60) {
                                    $shift_total = $shift_total - 60;
                                    $shift_total_hour_count++;
                                }

                                echo '<li class="grid-place-5">' . $shift_total_hour_count . ' hr ' . $shift_total . ' min</li>';
                            }
                            else {
                                echo '<li class="grid-place-5">' . $shift_total . ' min</li>';
                            }

                            $i++;
                        }
                        echo '</div>';

                        echo '<div class=grid-place-shift-1>';
                        for ($i = 0; $i < count($temp_array_one); $i++) {
                            if ($temp_array_one[$i] >= 60) {

                                $temp_array_one_hour_count = 0;

                                while ($temp_array_one[$i] >= 60) {
                                    $temp_array_one[$i] = $temp_array_one[$i] - 60;
                                    $temp_array_one_hour_count++;
                                }

                                echo '<li>' . $temp_array_one_hour_count . ' hr ' . $temp_array_one[$i] . ' min</li>';
                            }
                            else {
                                echo '<li>' . $temp_array_one[$i] . ' min</li>';
                            }
                        }
                        echo '</div>';

                        echo '<div class=grid-place-shift-2>';
                        for ($i = 0; $i < count($temp_array_two); $i++) {
                            if ($temp_array_two[$i] >= 60) {

                                $temp_array_two_hour_count = 0;

                                while ($temp_array_two[$i] >= 60) {
                                    $temp_array_two[$i] = $temp_array_two[$i] - 60;
                                    $temp_array_two_hour_count++;
                                }

                                echo '<li>' . $temp_array_two_hour_count . ' hr ' . $temp_array_two[$i] . ' min</li>';
                            }
                            else {
                                echo '<li>' . $temp_array_two[$i] . ' min</li>';
                            }
                        }
                        echo '</div>';

                        echo '<div class=grid-place-night-shift>';
                        for ($i = 0; $i < count($temp_array_three); $i++) {
                            if ($temp_array_three[$i] >= 60) {

                                $temp_array_three_hour_count = 0;

                                while ($temp_array_three[$i] >= 60) {
                                    $temp_array_three[$i] = $temp_array_three[$i] - 60;
                                    $temp_array_three_hour_count++;
                                }

                                echo '<li>' . $temp_array_three_hour_count . ' hr ' . $temp_array_three[$i] . ' min</li>';
                            }
                            else {
                                echo '<li>' . $temp_array_three[$i] . ' min</li>';
                            }
                        }
                        echo '</div>';
                    ?>
                </ul>
            </div>
        </div>
    </main>

    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php'">Return</button>
        <button class="print-btn">Print</button>
    </div>
    <script src="../js/print.js"></script>
</body>
</html>