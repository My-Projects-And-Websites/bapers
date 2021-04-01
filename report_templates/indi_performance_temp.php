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

    // select all of the tasks from job_task table that are within the specified date and also get staff details 
    $staff_query = "SELECT job_task.Staffstaff_id, job_task.task_date, staff.staff_fname, staff_sname
    FROM Job_Task
    INNER JOIN Staff ON job_task.Staffstaff_id = staff.staff_id
    WHERE job_task.task_date >= '$from_date' AND job_task.task_date <= '$to_date' AND job_task.task_status = 'Completed'
    GROUP BY job_task.Staffstaff_id";
    // execute the query
    $staff_result = $connect->query($staff_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title -->
    <title>Individual Performance Report</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="../css/report_templates/indi_performance/indi_performance.css">
    <link rel="stylesheet" href="../css/global.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <!-- heading to explain what the report is -->
            <h1>Individual Performance Report</h1>
            <!-- text to explain the contents of the report -->
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
                <!-- column fields to be displayed when report is generated -->
                <span>Name</span>
                <span>Task ID</span>
                <span>Department</span>
                <span>Date</span>
                <span>Start Time</span>
                <span>Time Taken</span>
                <span>Total</span>
            </div>
            <!-- list of staff that worked in the specified date -->
            <ul class="body-section-list">
                <?php
                    // loop through the results from the query
                    while ($staff_row = mysqli_fetch_assoc($staff_result)) {
                        echo '<li><span class="staff-name">' . $staff_row['Staffstaff_id'] . ' | ' . $staff_row['staff_fname'] . ' ' . $staff_row['staff_sname'] . '</span>';

                        // assign the staff id to a variable
                        $staff_id = $staff_row['Staffstaff_id'];

                        // select all the tasks that matches the ID from Tasktask_id field
                        $task_sql = "SELECT job_task.Tasktask_id, job_task.start_time, job_task.finish_time, job_task.task_date, task.task_location, job_task.Staffstaff_id
                        FROM Job_Task
                        INNER JOIN Task ON job_task.Tasktask_id = task.task_id
                        WHERE job_task.task_date >= ? AND job_task.task_date <= ?
                        AND job_task.Staffstaff_id = ? AND job_task.task_status = 'Completed'
                        ORDER BY job_task.Tasktask_id";
                        $task_query = $connect->prepare($task_sql);
                        // bind parameters to the query
                        $task_query->bind_param("ssi", $from_date, $to_date, $staff_id);
                        // execute the query
                        $task_query->execute();
                        // store the result of the query to a variable
                        $task_result = $task_query->get_result();

                        // declare integer variables
                        $total_time = 0;
                        $loop_count = 0;

                        // loop through all the result from the task query
                        while ($task_row = $task_result->fetch_assoc()) {
                            // convert the start_time and finish_time and store it in a new variable
                            $start_time_convert = new DateTime($task_row['start_time']);
                            $finish_time_convert = new DateTime($task_row['finish_time']);

                            // get the difference between the two dates
                            $time_taken = $start_time_convert->diff($finish_time_convert);

                            // display the details from the task such as the ID, location, date, time started and the time taken
                            echo '<span class="grid-place-1">' . $task_row['Tasktask_id'] . '</span>' .
                            '<span class="grid-place-2">' . $task_row['task_location'] . '</span>' .
                            '<span class="grid-place-3">' . $task_row['task_date'] . '</span>' .
                            '<span class="grid-place-4">' . $task_row['start_time'] . '</span>' .
                            '<span class="grid-place-5">' . $time_taken->format("%i") . ' min</span>';

                            // add the time_taken from each loop to total_time int he format of minutes
                            $total_time += (int)$time_taken->format("%i");
                            // variable to contain the hours 
                            $hour_count = 0;

                            // increment loop counter
                            $loop_count++;

                            // at the end of the iteration, run this if statement
                            if ($loop_count == mysqli_num_rows($task_result)) {

                                // check if total time is greater or equal to 60
                                if ($total_time >= 60) {
                                    
                                    // while total_time is more than 60, subtract 60 and incremet hour count
                                    while ($total_time >= 60) {
                                        $total_time = $total_time - 60;
                                        $hour_count++;
                                    }

                                    // display this
                                    echo '<span class="grid-place-6">' . $hour_count . ' hr ' . $total_time . ' min</span>';
                                }
                                // if total time is not greater than 60
                                else {
                                    // display the original total time
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