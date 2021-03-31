<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role'])) {
        header("Location: index.php");
    }

    $role = $_SESSION['role'];
    $staff_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Dashboard</title>

    <link rel="stylesheet" href="css/dash/dashboard.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <div class="sidebar">
            <?php
                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist" || $role == "Technician") {
                    echo '<a href="dashboard.php" class="sidebar-link">
                        <ion-icon name="apps-outline"></ion-icon>
                        <span>Overview</span>
                    </a>';
                }

                if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                    echo '<a href="payments.php" class="sidebar-link">
                        <ion-icon name="card-outline"></ion-icon>
                        <span>Payments</span>
                    </a>';
                }
                
                if ($role == "Office Manager") {
                    echo '<a href="accounts.php" class="sidebar-link">
                        <ion-icon name="add-circle-outline"></ion-icon>
                        <span>Accounts</span>
                    </a>';
                }
                
                if ($role == "Office Manager" || $role == "Shift Manager") {
                    echo '<a href="reports.php" class="sidebar-link">
                        <ion-icon name="document-text-outline"></ion-icon>
                        <span>Reports</span>
                    </a>';
                }

                if ($role == "Office Manager") {
                    echo '<a href="manage_tasks.php" class="sidebar-link">
                        <ion-icon name="create-outline"></ion-icon>
                        <span>Tasks</span>
                    </a>';
                }
                
                if ($role == "Office Manager") {
                    echo '<a href="customer_accounts.php" class="sidebar-link">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <span>Customers</span>
                    </a>';
                }
                
                echo '<div class="open-jobs-link">';
                    echo '<button class="open-job-collapsed-bar">
                        <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                        <span>Jobs</span>
                    </button>';
                    echo '<div class="job-links">';
                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Receptionist") {
                            echo '<a href="accept_job.php"><span>Accept Jobs</span></a>';
                        }

                        if ($role == "Office Manager" || $role == "Shift Manager" || $role == "Technician") {
                            echo '<a href="process_job.php"><span>Process Jobs</span></a>';
                        }
                    echo '</div>';
                echo '</div>';
                
                echo '<a href="php/logout.php" class="sidebar-link">
                    <ion-icon name="settings-outline"></ion-icon>
                    <span>Sign Out</span>
                </a>';
            ?>
        </div>
        <div class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </div>
        <div class="content">
            <h2 id="welcome">Hi, <?php echo $_SESSION['fname']; ?>.</h2>
            <?php
                if ($role != "Receptionist") {
                    echo '<a class="process" href="process_job.php">Process Jobs</a>';
                }
                
                if ($role != "Technician") {
                    echo '<a class="payments" href="payments.php">Payments</a>';
                }
            
                if ($role == "Office Manager") {
                    echo '<form action="php/db_restore.php" class="database-util">
                        <button type="submit">
                            <span>Backup Database</span>
                        </button>
                    </form>
                    <form action="php/db_backup.php" class="database-util">
                        <button type="submit">
                            <span>Import Database</span>
                        </button>
                    </form>';
                }
            ?>

            <?php
                if ($role != "Receptionist") {
                    echo '<div class="jobs-to-process">
                        <div class="tags">
                            <span>Job ID</span>
                            <span>Urgent</span>
                            <span>Deadline</span>
                        </div>';
                            
                        $jobs_to_process_sql = "SELECT * FROM Job ORDER BY job_deadline DESC";
                        $jobs_to_process_query = $connect->prepare($jobs_to_process_sql);
                        $jobs_to_process_query->execute();
                        $jobs_to_process_result = $jobs_to_process_query->get_result();
                        
                        if (mysqli_num_rows($jobs_to_process_result) != 0) {

                            while ($jobs_to_process_row = $jobs_to_process_result->fetch_assoc()) {
                                if ($jobs_to_process_row['job_urgency'] == 1) {
                                    $jobs_to_process_row['job_urgency'] = "Yes";
                                }
                                else {
                                    $jobs_to_process_row['job_urgency'] = "No";
                                }

                                echo '<div class="details"><span class="job-id">' . $jobs_to_process_row['job_id_char'] . '</span>' .
                                '<span class="job-urgency">' . $jobs_to_process_row['job_urgency'] . '</span>' .
                                '<span class="job-deadline">' . $jobs_to_process_row['job_deadline'] . '</span></div>';
                            }
                        }
                        else {
                            echo '<div class="details"><span class=no-jobs>No jobs to process.</span></div>';
                        }

                        echo '</div>';
                }

                if ($role != "Technician") {
                    echo '<div class="payments-to-process" id="' . $role . '">
                        <div class="tags">
                            <span>Payment ID</span>
                            <span>Late</span>
                            <span>Total</span>
                        </div>';
                            
                        $paym_to_process_sql = "SELECT * FROM Payment";
                        $paym_to_process_query = $connect->prepare($paym_to_process_sql);
                        $paym_to_process_query->execute();
                        $paym_to_process_result = $paym_to_process_query->get_result();
                        
                        if (mysqli_num_rows($paym_to_process_result) != 0) {

                            while ($paym_to_process_row = $paym_to_process_result->fetch_assoc()) {
                                if ($paym_to_process_row['payment_late'] == 1) {
                                    $paym_to_process_row['payment_late'] = "Late";
                                }
                                else {
                                    $paym_to_process_row['payment_late'] = "No";
                                }

                                echo '<div class="details"><span class="paym-id">' . $paym_to_process_row['payment_id_char'] . '</span>' .
                                '<span class="paym-deadline">' . $paym_to_process_row['payment_late'] . '</span>' .
                                '<span class="paym-urgency">£' . number_format((float)$paym_to_process_row['payment_total'], 2, '.', '') . '</span></div>';
                            }
                        }
                        else {
                            echo '<div class="details"><span class=no-paym>No payments to process.</span></div>';
                        }

                        echo '</div>';
                }
            ?>
        </div>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/move-paym-process.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>