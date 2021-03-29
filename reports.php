<?php
    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Receptionist" || $_SESSION['role'] == "Technician") {
        header("Location: index.php");
    }

    $role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Reports</title>

    <link rel="stylesheet" href="css/pages/reports/reports.css">
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
            <div class="customer-report">
                <h2>Customer Report</h2>
                <p>
                    Generate a report that contains details about jobs ordered by a
                    particular customer within a specific time frame.
                </p>
                <span>* Note: If "from" or "to" field is empty, all job orders will be displayed.</span>
            </div>
            <div class="staff-report">
                <h2>Individual Performance Report</h2>
                <p>
                    Generate a report that contains details about a BIPL staff's performance.
                    All performance of the staff on the specified date will be generated in the
                    report.
                </p>
            </div>
            <div class="summary-report">
                <h2>Summary Performance Report</h2>
                <p>
                    Generate a report about BIPL staff day and night shifts and total time spent per shift
                    in each department. Specify the dates to get the results required for which shifts need
                    to be monitored.
                </p>
            </div>
            <div class="customer-report-generate">
                <form action="report_templates/customer_report_temp.php" method="POST" class="generate-report-for-customer">
                    <div class="input-customer-id-field">
                        <label for="customer-id"><span class="star">*</span> Customer ID:</label>
                        <input type="text" name="customer-id" id="customer-id" placeholder="Customer ID" required>
                    </div>
                    <div class="input-date-field">
                        <div class="from-date-field">
                            <label for="from-date">From:</label>
                            <input type="date" name="from-date" id="from-date">
                        </div>
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
            <div class="indi-performance-generate">
                <form action="report_templates/indi_performance_temp.php" method="POST" class="generate-report-for-staff">
                    <div class="input-date-field">
                        <div class="from-date-field">
                            <label for="from-date"><span class="star">*</span> From:</label>
                            <input type="date" name="from-date" id="from-date" required>
                        </div>
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
            <div class="sum-performance-generate">
                <form action="report_templates/sum_performance_temp.php" method="POST" class="generate-report-for-summary">
                    <div class="input-date-field">
                        <div class="from-date-field">
                            <label for="from-date"><span class="star">*</span> From:</label>
                            <input type="date" name="from-date" id="from-date" required>
                        </div>
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

    <script src="js/open-sidebar-links.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>