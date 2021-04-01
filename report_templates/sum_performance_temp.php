<?php
    // start the session if not started yet
    if (!isset($_SESSION)) {
        session_start();
    }

    // include database connection
    include '../php/connection.php';

    // set the timezone to European time
    date_default_timezone_set('Europe/London');

    // get the dates from the form in the reports.php
    $_SESSION['from_date'] = $_POST['from-date'];
    $_SESSION['to_date'] = $_POST['to-date'];
    $_SESSION['datetime_of_generate'] = date('Y-m-d H:i:s');

    // assign these session variables into another variable
    $from_date = $_SESSION['from_date'];
    $to_date = $_SESSION['to_date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title -->
    <title>Summary Performance Report</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="../css/report_templates/sum_performance/sum_performance.css">
    <link rel="stylesheet" href="../css/global.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <!-- heading to explain what the report is -->
            <h1>Summary Performance Report</h1>
            <!-- small text to explain what the report content is -->
            <p>
                Report for day and night shifts of BIPL staff

                <?php
                    // if from or to date is not empty, display the date with the text
                    if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                        echo ' | ' . $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                    }
                ?>
            </p>
        </div>
        <div class="body-section">
            <div class="day-shift-one">
                <!-- first day shift -->
                <h2>Day Shift One (5:00 AM - 14:30 PM)</h2>
                <div class="body-section-tags">
                    <!-- column fields for the first day shift -->
                    <span>Date</span>
                    <span>Copy Room</span>
                    <span>Development</span>
                    <span>Finishing</span>
                    <span>Packing</span>
                    <span>Total Hours</span>
                </div>
                <ul class="body-section-shift-one">
                    <?php
                        // select all the distinct task_date from the query that are in between the specified date
                        $shift_one_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_one_get_date_query = $connect->prepare($shift_one_get_date_sql);
                        // bind the parameters to the query
                        $shift_one_get_date_query->bind_param("ss", $from_date, $to_date);
                        // execute the query
                        $shift_one_get_date_query->execute();
                        // store the result of the query to a variable
                        $shift_one_get_date_result = $shift_one_get_date_query->get_result();
#
                        // array to store the total time of one iteration 
                        $temp_array_one = [];

                        // loop through the results from the query
                        while ($shift_one_get_date_row = $shift_one_get_date_result->fetch_assoc()) {
                            // display a list item
                            echo '<li><span>' . $shift_one_get_date_row['task_date'] . '</span>';

                            // declare variables to contain the total time for each department
                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            // get the times spent by all staff members in the department, start_time must be between 5 AM and 2:30 PM and status of the task must be completed
                            $shift_one_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '05:00' AND job_task.start_time <= '14:30'
                            AND job_task.task_status = 'Completed'";
                            $shift_one_get_times_query = $connect->prepare($shift_one_get_times_sql);
                            // bind parameter to query
                            $shift_one_get_times_query->bind_param("s", $shift_one_get_date_row['task_date']);
                            // execute the query
                            $shift_one_get_times_query->execute();
                            // store the result into a variable
                            $shift_one_get_times_result = $shift_one_get_times_query->get_result();

                            // declare new variables
                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            // loop through the result of the query
                            while ($shift_one_get_times_row = $shift_one_get_times_result->fetch_assoc()) {
                                // convert start_time and finish_time to new datetime
                                $start_time_convert = new DateTime($shift_one_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_one_get_times_row['finish_time']);

                                if ($total_time != 0) {
                                    $total_time = 0;
                                    $total_time_hour_count = 0;
                                }

                                // store the difference between the two datetimes
                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                // depending on the department, add on to the corresponding total time and also declare a new variable for the total hour time
                                if ($shift_one_get_times_row['task_location'] == "Copy Room") {
                                    $copy_room_total_time += (int)$time_taken->format("%i");
                                    $copy_room_hour_count = 0;
                                }
                                else if ($shift_one_get_times_row['task_location'] == "Development Area") {
                                    // if the department is development Area then add the time taken to total time ind evelopment area
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

                                // increment the loop counter
                                $loop_count++;

                                // on the last iteration
                                if ($loop_count == mysqli_num_rows($shift_one_get_times_result)) {

                                    // check each of the total time for ech department and convert them into hours if the time taken is more than or equal to 60
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
                                        // increment hour count
                                        $development_hour_count++;

                                        // loop until total time is less than 60
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

                                        // after the loop, display the hour and minutes
                                        echo '<span class="grid-place-3">' . $packing_hour_count . ' hr ' . $packing_total_time . ' min</span>';
                                    }
                                    else {
                                        // if not more than 60, display the original total time
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

                                 // store the total time into the array
                                $time_arr_one = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                // loop through the array and add it to the main total time variable
                                for ($i = 0; $i < count($time_arr_one); $i++) {
                                    $total_time += $time_arr_one[$i];
                                }
                            }

                            // push this total time to the first empty array
                            array_push($temp_array_one, $total_time);

                            // convert the total time into hours
                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    // increment hour count
                                    $total_time_hour_count++;
                                }

                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            // reset the total time
                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-two">
                <!-- heading for 2nd day shift -->
                <h2>Day Shift Two (14:30 PM - 22:00 PM)</h2>
                <!-- column fields for the second day shift -->
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
                        // select all the unique dates specified in the query
                        $shift_two_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_two_get_date_query = $connect->prepare($shift_two_get_date_sql);
                        // bind the parameters to teh query
                        $shift_two_get_date_query->bind_param("ss", $from_date, $to_date);
                        // execute the query
                        $shift_two_get_date_query->execute();
                        // store the result of the query into a variable
                        $shift_two_get_date_result = $shift_two_get_date_query->get_result();

                        // declare an empty array
                        $temp_array_two = [];

                        // loop through the results from the date query
                        while ($shift_two_get_date_row = $shift_two_get_date_result->fetch_assoc()) {
                            // echo a list item
                            echo '<li><span>' . $shift_two_get_date_row['task_date'] . '</span>';

                            // declare variables for each department to add its total time
                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            // the query for shift two, get all job_task in whcih their start time are between 2:30 PM and 10:00PM and these tasks must be completed too
                            $shift_two_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '14:30' AND job_task.start_time <= '22:00'
                            AND job_task.task_status = 'Completed'";
                            $shift_two_get_times_query = $connect->prepare($shift_two_get_times_sql);
                            // bind the parameters to the query
                            $shift_two_get_times_query->bind_param("s", $shift_two_get_date_row['task_date']);
                            // execute the query
                            $shift_two_get_times_query->execute();
                            // store the variable into the result
                            $shift_two_get_times_result = $shift_two_get_times_query->get_result();

                            // declare a loop counter
                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            // loop through the array of results from the query
                            while ($shift_two_get_times_row = $shift_two_get_times_result->fetch_assoc()) {
                                // convert start time and finish time to new date time
                                $start_time_convert = new DateTime($shift_two_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_two_get_times_row['finish_time']);

                                // reset total time and total hour count to 0 every iteration
                                if ($total_time != 0) {
                                    $total_time = 0;
                                    $total_time_hour_count = 0;
                                }

                                // get the interval between the two date times
                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                // depending on the department, add to their total time and declare their hour count
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

                                // loop iteration will increment
                                $loop_count++;

                                // store into array the total time for each
                                $time_arr_two = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                // at the last iteration, this if statement will return true
                                if ($loop_count == mysqli_num_rows($shift_two_get_times_result)) {

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                // add to total time the value in the array
                                for ($i = 0; $i < count($time_arr_two); $i++) {
                                    $total_time += $time_arr_two[$i];
                                }
                            }

                            // push this total_time to the empty array in this block
                            array_push($temp_array_two, $total_time);

                            // convert total time to hours
                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    $total_time_hour_count++;
                                }

                                // display the total hour count and remaining total time
                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            // reset to 0
                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-three">
                <!-- heading for 1st night shift -->
                <h2>Night Shift One (22:00 PM - 5:00 AM)</h2>
                <!-- column fields for the first night shift -->
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
                        // select all the unique dates specified in the query
                        $shift_three_get_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $shift_three_get_date_query = $connect->prepare($shift_three_get_date_sql);
                        // bind parameters to the query
                        $shift_three_get_date_query->bind_param("ss", $from_date, $to_date);
                        // execute the query
                        $shift_three_get_date_query->execute();
                        // store the result of the query into a variable
                        $shift_three_get_date_result = $shift_three_get_date_query->get_result();

                        // declare an empty array
                        $temp_array_three = [];

                        while ($shift_three_get_date_row = $shift_three_get_date_result->fetch_assoc()) {
                            // echo a list item
                            echo '<li><span>' . $shift_three_get_date_row['task_date'] . '</span>';

                            // declare variables for each department to add its total time
                            $copy_room_total_time = 0;
                            $development_total_time = 0;
                            $finishing_total_time = 0;
                            $packing_total_time = 0;

                            // the query for shift two, get all job_task in whcih their start time are between 10:00PM and 05:00AM and these tasks must be completed too
                            $shift_three_get_times_sql = "SELECT job_task.task_date, job_task.start_time, job_task.finish_time, task.task_location 
                            FROM Job_Task 
                            INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                            WHERE job_task.task_date = ?
                            AND job_task.start_time >= '22:00' AND job_task.start_time <= '05:00'
                            AND job_task.task_status = 'Completed'";
                            $shift_three_get_times_query = $connect->prepare($shift_three_get_times_sql);
                            // bind parameters to the query
                            $shift_three_get_times_query->bind_param("s", $shift_three_get_date_row['task_date']);
                            // execute the query
                            $shift_three_get_times_query->execute();
                            // store the result of the query into a variable
                            $shift_three_get_times_result = $shift_three_get_times_query->get_result();

                            // declare a loop counter
                            $loop_count = 0;
                            $total_time = 0;
                            $total_time_hour_count = 0;

                            // loop through the results based on the query
                            while ($shift_three_get_times_row = $shift_three_get_times_result->fetch_assoc()) {
                                // convert start time and finish time to new date time
                                $start_time_convert = new DateTime($shift_three_get_times_row['start_time']);
                                $finish_time_convert = new DateTime($shift_three_get_times_row['finish_time']);

                                // reset total time and total hour count to 0 every iteration                                
                                if ($total_time != 0) {
                                    $total_time = 0;
                                    $total_time_hour_count = 0;
                                }

                                // get the interval between the two date times
                                $time_taken = $start_time_convert->diff($finish_time_convert);

                                // depending on the department, add to their total time and declare their hour count
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

                                // loop iteration will increment
                                $loop_count++;

                                // store into array the total time for each
                                $time_arr_three = [$copy_room_total_time, $development_total_time, $packing_total_time, $finishing_total_time];

                                // at the last iteration, this if statement will return true
                                if ($loop_count == mysqli_num_rows($shift_three_get_times_result)) {

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                    // check if total times are more than 60
                                    // if true, subtract 60 until the total time is no longer more than 60
                                    // for each iteration, increment the hour count and display the corresponding span tag
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

                                // add to total time the value in the array
                                for ($i = 0; $i < count($time_arr_three); $i++) {
                                    $total_time += $time_arr_three[$i];
                                }
                            }

                            // push this total_time to the empty array in this block
                            array_push($temp_array_three, $total_time);

                            // convert total time to hours
                            if ($total_time >= 60) {
                                $total_time = $total_time - 60;
                                $total_time_hour_count++;

                                while ($total_time >= 60) {
                                    $total_time = $total_time - 60;
                                    $total_time_hour_count++;
                                }

                                // display the total hour count and remaining total time
                                echo '<span class="grid-place-5">' . $total_time_hour_count . ' hr ' . $total_time . ' min</span>';
                            }
                            else {
                                echo '<span class="grid-place-5">' . $total_time . ' min</span>';
                            }

                            // reset to 0
                            $total_time = 0;

                            echo '</li>';
                        }
                    ?>
                </ul>
            </div>
            <div class="day-shift-three">
                <!-- heading for summary -->
                <h2> For period (
                    <?php
                        if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                            echo $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                        }
                    ?>
                    )
                </h2>
                <!-- column fields for the summary section of the report -->
                <div class="body-section-tags-last">
                    <span>Date</span>
                    <span>Day Shift 1</span>
                    <span>Day Shift 2</span>
                    <span>Night Shift 1</span>
                    <span>Total Hours</span>
                </div>
                <ul class="period-date">
                    <?php
                        // select all of the distinct dates in the job_task
                        $period_date_sql = "SELECT DISTINCT task_date FROM Job_Task WHERE job_task.task_date >= ? AND job_task.task_date <= ?";
                        $period_date_query = $connect->prepare($period_date_sql);
                        // bind the parameter to the query
                        $period_date_query->bind_param("ss", $from_date, $to_date);
                        // execute the query
                        $period_date_query->execute();
                        // store the result into a variable
                        $period_date_result = $period_date_query->get_result();

                        // loop through the result of the query and display the date as a list item
                        echo '<div class=date-shift>';
                        while ($period_date_row = $period_date_result->fetch_assoc()) {
                            echo '<li>' . $period_date_row['task_date'] . '</li>';
                        }
                        echo '</div>';

                        // counter for the while loop
                        $i = 0;
                        // until $i is not equal to the number of rows fetched by the query, loop
                        echo '<div class=grid-place-total>';
                        while ($i != mysqli_num_rows($period_date_result)) {
                            // declare these variables and reset for every iteration
                            $shift_total = 0;
                            $shift_total_hour_count = 0;

                            // add the total to a variable, add the array index specified by the counter
                            $shift_total = $temp_array_one[$i] + $temp_array_two[$i] + $temp_array_three[$i];

                            // convert shift_total_time to hours instead of just plain minutes
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

                            // increment the $i counter
                            $i++;
                        }
                        echo '</div>';

                        // go through the first array, and convert each integer in the array to minutes
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

                        // go through the second array, and convert each integer in the array to minutes
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

                        // go through the third array, and convert each integer in the array to minutes
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

    <!-- onclick of the return button, go back to generate reports page -->
    <!-- onclick of print button, remove buttons from page and open print dialog -->
    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php';">Return</button>
        <button class="print-btn">Print</button>
    </div>

    <!-- javascript functions -->
    <script src="../js/print.js"></script>
</body>
</html>