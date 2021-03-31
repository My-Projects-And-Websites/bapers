<?php
    // update late payments
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if role of authenticated user is receptionist or technician, redirect to login page
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Receptionist" || $_SESSION['role'] == "Technician") {
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
    <title>BAPERS | Reports</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="css/pages/reports/reports.css">
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
            <!-- details of customer sales report -->
            <div class="customer-report">
                <h2>Customer Report</h2>
                <p>
                    Generate a report that contains details about jobs ordered by a
                    particular customer within a specific time frame.
                </p>
                <span>* Note: If "from" or "to" field is empty, all job orders will be displayed.</span>
            </div>
            <!-- details of individual performance report -->
            <div class="staff-report">
                <h2>Individual Performance Report</h2>
                <p>
                    Generate a report that contains details about a BIPL staff's performance.
                    All performance of the staff on the specified date will be generated in the
                    report.
                </p>
            </div>
            <!-- details of summary performance report -->
            <div class="summary-report">
                <h2>Summary Performance Report</h2>
                <p>
                    Generate a report about BIPL staff day and night shifts and total time spent per shift
                    in each department. Specify the dates to get the results required for which shifts need
                    to be monitored.
                </p>
            </div>
            <!-- form to generate customer sales report -->
            <div class="customer-report-generate">
                <!-- when submitted, access the report_templates/customer_report_temp.php as specified in action attribute -->
                <form action="report_templates/customer_report_temp.php" method="POST" class="generate-report-for-customer">
                    <!-- customer id field -->
                    <div class="input-customer-id-field">
                        <label for="customer-id"><span class="star">*</span> Customer ID:</label>
                        <input type="text" name="customer-id" id="customer-id" placeholder="Customer ID" required>
                    </div>
                    <div class="input-date-field">
                        <!-- specify the starting date -->
                        <div class="from-date-field">
                            <label for="from-date">From:</label>
                            <input type="date" name="from-date" id="from-date">
                        </div>
                        <!-- specify the end date -->
                        <div class="to-date-field">
                            <label for="to-date">To:</label>
                            <input type="date" name="to-date" id="to-date">
                        </div>
                    </div>
                    <div class="input-submit-btn">
                        <input type="submit" value="Generate">
                    </div>
                </form>
            </div>
            <!-- form to generate individual performance report -->
            <div class="indi-performance-generate">
                <!-- when submitted, access report_templates/indi_performance_temp.php as specified in action attribute -->
                <form action="report_templates/indi_performance_temp.php" method="POST" class="generate-report-for-staff">
                    <div class="input-date-field">
                        <!-- specify the starting date -->
                        <div class="from-date-field">
                            <label for="from-date"><span class="star">*</span> From:</label>
                            <input type="date" name="from-date" id="from-date" required>
                        </div>
                        <!-- specify the end date -->
                        <div class="to-date-field">
                            <label for="to-date"><span class="star">*</span> To:</label>
                            <input type="date" name="to-date" id="to-date" required>
                        </div>
                    </div>
                    <div class="input-submit-btn">
                        <input type="submit" value="Generate">
                    </div>
                </form>
            </div>
            <!-- form to generate summary performance report -->
            <div class="sum-performance-generate">
                <!-- when submitted, access the report_templates/sum_performance_temp.php as specified in action attribute -->
                <form action="report_templates/sum_performance_temp.php" method="POST" class="generate-report-for-summary">
                    <div class="input-date-field">
                        <!-- specify the start date -->
                        <div class="from-date-field">
                            <label for="from-date"><span class="star">*</span> From:</label>
                            <input type="date" name="from-date" id="from-date" required>
                        </div>
                        <!-- specify the end date -->
                        <div class="to-date-field">
                            <label for="to-date"><span class="star">*</span> To:</label>
                            <input type="date" name="to-date" id="to-date" required>
                        </div>
                    </div>
                    <div class="input-submit-btn">
                        <input type="submit" value="Generate">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- javascript functions -->
    <script src="js/open-sidebar-links.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>