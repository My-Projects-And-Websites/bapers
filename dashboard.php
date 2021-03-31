<?php
    // db connection and update late payments
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if these session variables do not exist, redirect user to login page
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role'])) {
        header("Location: index.php");
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
    <title>BAPERS | Dashboard</title>

    <!-- stylesheets for this page -->
    <link rel="stylesheet" href="css/dash/dashboard.css">
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
        <div class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </div>
        <!-- main content of the page -->
        <div class="content">
            <!-- welcome the user, display the first name -->
            <h2 id="welcome">Hi, <?php echo $_SESSION['fname']; ?>.</h2>
            <?php
                // except for receptionists, everyone will have process jobs link in their dashboard 
                if ($role != "Receptionist") {
                    echo '<a class="process" href="process_job.php">Process Jobs</a>';
                }
                
                // except for technicians, everyone will have payments link in their dashboard 
                if ($role != "Technician") {
                    echo '<a class="payments" href="payments.php">Payments</a>';
                }
            
                // office managers only, restore and backup database
                if ($role == "Office Manager") {
                    echo '<form action="php/db_restore.php" class="database-util">
                        <button type="submit">
                            <span>Backup Database</span>
                        </button>
                    </form>
                    <form action="php/db_backup.php" class="database-util">
                        <button type="submit">
                            <span>Restore Database</span>
                        </button>
                    </form>';
                }
            ?>

            <?php
                // if role is not receptionist, display process jobs in dashboard
                if ($role != "Receptionist") {
                    // process jobs legend 
                    echo '<div class="jobs-to-process">
                        <div class="tags">
                            <span>Job ID</span>
                            <span>Urgent</span>
                            <span>Deadline</span>
                        </div>';
                        
                        // query to use that fetches all jobs in descending order of deadline
                        $jobs_to_process_sql = "SELECT * FROM Job ORDER BY job_deadline DESC";
                        $jobs_to_process_query = $connect->prepare($jobs_to_process_sql);
                        $jobs_to_process_query->execute();
                        // fetch all rows based on the executed query
                        $jobs_to_process_result = $jobs_to_process_query->get_result();
                        
                        // if there are jobs to process then go through this if statement
                        if (mysqli_num_rows($jobs_to_process_result) != 0) {

                            // loop through the results from the query
                            while ($jobs_to_process_row = $jobs_to_process_result->fetch_assoc()) {
                                // display yes or no according to job_urgency e.g. if 1, display yes, vice-versa
                                if ($jobs_to_process_row['job_urgency'] == 1) {
                                    $jobs_to_process_row['job_urgency'] = "Yes";
                                }
                                else {
                                    $jobs_to_process_row['job_urgency'] = "No";
                                }

                                // display HTML tags if there are jobs to process
                                echo '<div class="details"><span class="job-id">' . $jobs_to_process_row['job_id_char'] . '</span>' .
                                '<span class="job-urgency">' . $jobs_to_process_row['job_urgency'] . '</span>' .
                                '<span class="job-deadline">' . $jobs_to_process_row['job_deadline'] . '</span></div>';
                            }
                        }
                        // if there are no jobs, return "No jobs to process."
                        else {
                            echo '<div class="details"><span class=no-jobs>No jobs to process.</span></div>';
                        }

                        echo '</div>';
                }

                // if role is not technician, display payments to process
                if ($role != "Technician") {
                    // payments to process legends
                    echo '<div class="payments-to-process" id="' . $role . '">
                        <div class="tags">
                            <span>Payment ID</span>
                            <span>Status</span>
                            <span>Total</span>
                        </div>';
                        
                    // select all the payment data
                    $paym_to_process_sql = "SELECT * FROM Payment";
                    $paym_to_process_query = $connect->prepare($paym_to_process_sql);
                    $paym_to_process_query->execute();
                    $paym_to_process_result = $paym_to_process_query->get_result();
                    
                    // if there are payments to process, display this if statement block
                    if (mysqli_num_rows($paym_to_process_result) != 0) {

                        // loop through all the payment data
                        while ($paym_to_process_row = $paym_to_process_result->fetch_assoc()) {
                            // display payment_id, status and total
                            echo '<div class="details"><span class="paym-id">' . $paym_to_process_row['payment_id_char'] . '</span>' .
                            '<span class="paym-deadline">' . $paym_to_process_row['payment_status'] . '</span>' .
                            '<span class="paym-urgency">£' . number_format((float)$paym_to_process_row['payment_total'], 2, '.', '') . '</span></div>';
                        }
                    }
                    // if there are no payments to process, return "No payments to process."
                    else {
                        echo '<div class="details"><span class=no-paym>No payments to process.</span></div>';
                    }

                    echo '</div>';
                }
            ?>
        </div>
    </main>

    <!-- javascript functions, open job links and styling payment process -->
    <script src="js/open-sidebar-links.js"></script>
    <script src="js/move-paym-process.js"></script>
    <!-- ion-icons - an icon library that handles all icons in the page -->
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>