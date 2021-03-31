<?php
    // db connection and update late payments
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if the role of authenticated user is technician, redirect user to dashboard page
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Technician") {
        header("Location: dashboard.php");
    }

    // variable used as reference for access privileges
    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title -->
    <title>BAPERS | Accept Job</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="css/pages/accept_job/accept_job.css">
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
            <!-- assign a new job to staff members -->
            <div class="assign-job-form">
                <!-- submit this form to pass data to php/assign_job.php -->
                <form action="php/assign_job.php" method="POST" class="create-job">
                    <div class="left-part-form">
                        <h2>Assign a Job</h2>
                        <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                        <!-- customer id prompt -->
                        <div class="input-customer-id-field">
                            <label for="job-customer-id">Customer ID:</label>
                            <input type="text" name="job-customer-id" id="job-customer-id" required>
                        </div>
                        <!-- special instructions prompt -->
                        <div class="input-instructions-field">
                            <label for="job-instructions">Instructions</label>
                            <textarea name="job-instructions" id="job-instructions" required></textarea>
                        </div>
                        <!-- select if job is urgent or not -->
                        <div class="input-urgency-field">
                            <label for="job-urgency">Urgent:</label>
                            <select name="job-urgency" id="job-urgency" required>
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            </select>
                            <ion-icon name="caret-down-outline"></ion-icon>
                        </div>
                        <!-- default to no display, ask for how many hours for deadline -->
                        <div class="input-urgency-hours-field">
                            <label for="urgency-hours">How many hours should it take?</label>
                            <input type="text" name="urgency-hours" id="urgency-hours">
                        </div>
                    </div>
                    <div class="right-part-container">
                        <!-- list of tasks available to be assigned for each job -->
                        <div class="right-part-form">
                            <h2>Select Tasks</h2>
                            <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                            <?php
                                // selects all the task from the table
                                $tasks = "SELECT * FROM Task";
                                $tasks_result = $connect->query($tasks);

                                // display checkboxes for each task to be assigned
                                while ($tasks_row = mysqli_fetch_assoc($tasks_result)) {
                                    echo "<div class=checkbox-task-" . $tasks_row['task_id'] . ">" .
                                    "<label for=task-id-" . $tasks_row['task_id'] . ">" .
                                    "<input type=checkbox name=task[] value=" . $tasks_row['task_id'] . " id=task-id-" . $tasks_row['task_id'] . ">" .
                                    "<span>" . $tasks_row['task_desc'] . "</span>" .
                                    "</label>" .
                                    '<div class="task-amount-input"><input type=text name=times-amount[] placeholder="#" class="task-amount">' .
                                    "<select name=assign-task[] id=assign-staff-to-" . $tasks_row['task_id'] . ">";

                                    // select all staff member and display as an option to a select tag
                                    $staffs = "SELECT * FROM Staff";
                                    $staffs_result = $connect->query($staffs);

                                    // loop through all staff
                                    while ($staffs_row = mysqli_fetch_assoc($staffs_result)) {
                                        echo "<option value=" . $staffs_row['staff_id'] . ">" . $staffs_row['staff_id'] . ' | ' . $staffs_row['staff_fname'] . ' ' . $staffs_row['staff_sname'] . "</option>";
                                    }

                                    echo "</select></div></div>";
                                }
                            ?>
                        </div>
                        <!-- submit button, click when finished filling in everything  -->
                        <div class="input-submit-field">
                            <input type="submit" name="job-assign-form" value="Assign" id="job-assign-btn">
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- if urgent select tag is set to yes, display this field -->
    <script>
        $(function() {
            // variables for urgency hours form fields
            var hours = $('.input-urgency-hours-field');
            var urgency = $('#job-urgency');

            // when select tag = Yes, "how many hours" field will slide down
            urgency.on('change', function() {
                if ($(this).val() == "Yes") {
                    hours.slideDown(300);
                }
                else {
                    hours.slideUp(300);
                }
            });
        }); 
    </script>
    <!-- javascript functions -->
    <script src="js/open-sidebar-links.js"></script>
    <script src="js/search.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>