<?php
    // db connection and update late payments
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if role of authenticated user is receptionist, redirect to dashboard page
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Receptionist" ) {
        header("Location: dashboard.php");
    }

    // variables used as reference for access privileges
    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title -->
    <title>BAPERS | Process Job</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="css/pages/process_job/process_job.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <!-- sidebar placed on the left side of the page -->
        <div class="sidebar">
            <?php
                // everyone has access to dashboard
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist" || $role == "Technician") {
                    echo '<a href="dashboard.php" class="sidebar-link">
                        <ion-icon name="apps-outline"></ion-icon>
                        <span>Overview</span>
                    </a>';
                }

                // except for technician, everyone has access to Payments page
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                    echo '<a href="payments.php" class="sidebar-link">
                        <ion-icon name="card-outline"></ion-icon>
                        <span>Payments</span>
                    </a>';
                }
                
                // if role is office manager, can access accounts page
                if ($role == "Office Manager") {
                    echo '<a href="accounts.php" class="sidebar-link">
                        <ion-icon name="add-circle-outline"></ion-icon>
                        <span>Accounts</span>
                    </a>';
                }
                
                // if role is either one of manager, can access reports page
                if ($role == "Office Manager" || $role == "Shift Manager") {
                    echo '<a href="reports.php" class="sidebar-link">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Reports</span>
                    </a>';
                }

                // if role is office manager, can access tasks page
                if ($role == "Office Manager") {
                    echo '<a href="manage_tasks.php" class="sidebar-link">
                        <ion-icon name="create-outline"></ion-icon>
                        <span>Tasks</span>
                    </a>';
                }
                
                // if role is office manager, can access customers page
                if ($role == "Office Manager") {
                    echo '<a href="customer_accounts.php" class="sidebar-link">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Customers</span>
                    </a>';
                }
                
                // dropdown containing links for accepting and processing jobs
                echo '<div class="open-jobs-link">';
                    echo '<button class="open-job-collapsed-bar">
                        <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                        <span>Jobs</span>
                    </button>';
                    echo '<div class="job-links">';
                        // except for technician, everyone has access to accepting jobs page
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                            echo '<a href="accept_job.php"><span>Accept Jobs</span></a>';
                        }
                        // except for technician, everyone has access to Payments page
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Technician") {
                            echo '<a href="process_job.php"><span>Process Jobs</span></a>';
                        }
                    echo '</div>';
                echo '</div>';
                
                // log out button, this ends the session and unsets any $_SESSION variables to disable access to authenticated pages
                echo '<a href="php/logout.php" class="sidebar-link">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Sign Out</span>
                </a>';
            ?>
        </div>
        <!-- header that contains the name of the authenticated user -->
        <section class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </section>
        <!-- main content of the page -->
        <section class="content">
            <!-- search field where user can enter details and relevant list items will be returned -->
            <div class="form-search-find">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <!-- this is the input field where user can enter their query -->
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search job ID, status, anything...">
                    </div>
                </form>
            </div>
            <!-- this is the list of jobs registered in the database -->
            <div class="job-details">
                <!-- jobs column title -->
                <div class="job-detail-tags">
                    <span>ID</span>
                    <span>Customer</span>
                    <span>Urgent</span>
                    <span>Deadline</span>
                    <span>Finish Time</span>
                    <span>Expected Time</span>
                    <span>Status</span>
                </div>
                <!-- list of jobs -->
                <ul id="job-list">
                    <?php
                        // combine two tables and select every field within the table
                        $job_query = "SELECT job.*, customer.*  FROM `Job` INNER JOIN Customer ON customer.cust_id = job.Customercust_id";
                        // execute the query
                        $job_result = $connect->query($job_query);
    
                        // if there are jobs registered in the database, then return true for this if statement
                        if (mysqli_num_rows($job_result) != 0) {
                            // loop through the result from the query
                            while ($row = mysqli_fetch_assoc($job_result)) {
                                // if job urgency is 0 then display No and vice versa
                                if ($row['job_urgency'] == 0) { 
                                    $row['job_urgency'] = "No";
                                }
                                else {
                                    $row['job_urgency'] = "Yes";
                                }

                                // variable to hold the id of the job
                                $job_id_find = $row['job_id'];

                                // this query combines job_Task and task to get the tasks that belong to the specific job
                                $task_query = "SELECT job_task.Tasktask_id, job_task.task_status, task.task_id, task.task_desc, task.task_location, task.task_duration 
                                FROM Job_Task
                                INNER JOIN task ON job_task.Tasktask_id = task.task_id
                                WHERE job_task.Jobjob_id = $job_id_find";
                                // execute the query
                                $task_query_results = $connect->query($task_query);

                                // display the details from the database
                                echo "<li id=job-" . $row['job_id'] . 
                                '><span>' . $row['job_id_char'] .
                                '</span><span>' . $row['cust_id_char'] .
                                '</span><span>' . $row['job_urgency'] . 
                                '</span><span>' . $row['job_deadline'] . 
                                '</span><span>' . $row['actual_finish'] . 
                                '</span><span>' . $row['expected_finish'] . 
                                '</span><span>' . $row['job_status'] . 
                                '</span><button class=drop-details-' . $row['job_id'] . ' onclick=toggleJobDetails(' . $row['job_id'] . ')><ion-icon name="caret-down-outline"></ion-icon>' . 
                                '</button></li>' .
                                '<div style="display: none;" id=job-details-container-' . $row['job_id'] .
                                '><form action=php/update_job.php method="POST" id=form-job-details-' . $row['job_id'] .
                                '><div class="input-field-expected-instructions">
                                <div class="special-instructions"><h3>Special Instructions</h3><p>' . $row['special_instructions'] . '</p></div>
                                <div class="expected-finish"><label for="expected-finish-job">Expected Finish</label><input type=datetime-local name=expected-finish-job id="expected-finish-job"></div>' . 
                                '</div>' .
                                '<div class="tasks-to-do">';

                                // loop through all the tasks from the query
                                while ($task_query_row = mysqli_fetch_assoc($task_query_results)) {
                                    // display the tasks for that specific job
                                    echo "<div class='job-relevant-tasks'>" . 
                                    '<div class="task-details">' .
                                    '<span>' . $task_query_row['task_desc'] . '</span>' .
                                    '<span>' . $task_query_row['task_location'] . '</span>' .
                                    '<select name=task-stati[] id=task-stati-' . $task_query_row['task_id'] . '>';

                                    // the selected option for this select tag is based on the value from the database
                                    // if task status is pending then option selected will be pending
                                    if ($task_query_row['task_status'] == "Pending") {
                                        echo '<option value="Pending" selected>Pending</option>' .
                                        '<option value="In Progress">Task In Progress</option>' .
                                        '<option value="Completed">Completed</option>';
                                    }
                                    else if ($task_query_row['task_status'] == "In Progress") {
                                        echo '<option value="Pending">Pending</option>' .
                                        '<option value="In Progress" selected>Task In Progress</option>' .
                                        '<option value="Completed">Completed</option>';
                                    }
                                    else if ($task_query_row['task_status'] == "Completed") {
                                        echo '<option value="Pending" selected>Pending</option>' .
                                        '<option value="In Progress">Task In Progress</option>' .
                                        '<option value="Completed" selected>Completed</option>';
                                    }
                                    
                                    echo "</select></div>" .
                                    '</div>'; 
                                }

                                // hidden input to identify the job that needs to be updated
                                // submit button to submit the form and process its data
                                echo '</div>' .
                                '<input type=hidden name=job-identifier value=' . $row['job_id'] . '>' .
                                '<input type=submit name="job-detail-submit-btn" value=Update>' .
                                '</form></div>';
                            }
                        }
                        else {
                            echo "<li><span id='no-jobs'>No jobs available.</span></li>";
                        }
                    ?>
                </ul>
            </div>
        </section>
    </main>

    <!-- javascript functions -->
    <script src="js/open-sidebar-links.js"></script>
    <script src="js/search-job.js"></script>
    <script src="js/open-job-details.js"></script>
    <!-- ionicons is an icon library which handles all icons in the page -->
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>