<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager") {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Accept Job</title>

    <link rel="stylesheet" href="css/pages/accept_job/accept_job.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <section class="sidebar">
            <a href="dashboard.php" class="sidebar-link">
                <ion-icon name="apps-outline"></ion-icon>
                <span>Overview</span>
            </a>
            <a href="payments.php" class="sidebar-link">
                <ion-icon name="card-outline"></ion-icon>
                <span>Payments</span>
            </a>
            <a href="accounts.php" class="sidebar-link">
                <ion-icon name="add-circle-outline"></ion-icon>
                <span>Accounts</span>
            </a>
            <a href="reports.php" class="sidebar-link">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Reports</span>
            </a>
            <div class="open-jobs-link">
                <button class="open-job-collapsed-bar">
                    <ion-icon name="caret-forward-outline" class="job-arrow"></ion-icon>
                    <span>Jobs</span>
                </button>
                <div class="job-links">
                    <a href="accept_job.php"><span>Accept Jobs</span></a>
                    <a href="process_job.php"><span>Process Jobs</span></a>
                </div>
            </div>
            <div class="open-customer-link">
                <button class="open-customer-collapsed-bar">
                    <ion-icon name="caret-forward-outline" class="customer-arrow"></ion-icon>
                    <span>Customers</span>
                </button>
                <div class="customer-links">
                    <a href="customer_accounts.php"><span>Accounts</span></a>
                    <a href=""><span>Discounts</span></a>
                </div>
            </div>
            <a href="php/logout.php" class="sidebar-link">
                <ion-icon name="settings-outline"></ion-icon>
                <span>Sign Out</span>
            </a>
        </section>
        <section class="header">
            <span><?php echo $_SESSION['fname'] . ' ' . $_SESSION['sname']; ?></span>
        </section>
        <section class="content">
            <div class="assign-job-form">
                <form action="php/assign_job.php" method="POST" class="create-job">
                    <div class="left-part-form">
                        <h2>Assign a Job</h2>
                        <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                        <div class="input-customer-id-field">
                            <label for="job-customer-id">Customer ID:</label>
                            <input type="text" name="job-customer-id" id="job-customer-id" required>
                        </div>
                        <div class="input-staff-id-field">
                            <label for="job-staff-id">Staff ID:</label>
                            <select name="job-staff-id" id="job-staff-id">
                                <?php
                                    $all_staff_query = "SELECT * FROM Staff";
                                    $all_staff_result = $connect->query($all_staff_query);
                                    
                                    while ($all_staff_row = mysqli_fetch_assoc($all_staff_result)) {
                                        echo "<option value=" . $all_staff_row['staff_id'] . ">" . $all_staff_row['staff_id'] . ' | ' . $all_staff_row['staff_fname'] . ' ' . $all_staff_row['staff_sname'] . "</option>";
                                    }
                                ?>
                            </select>
                            <ion-icon name="caret-down-outline"></ion-icon>
                        </div>
                        <div class="input-instructions-field">
                            <label for="job-instructions">Instructions</label>
                            <textarea name="job-instructions" id="job-instructions" required></textarea>
                        </div>
                        <div class="input-urgency-field">
                            <label for="job-urgency">Urgent:</label>
                            <select name="job-urgency" id="job-urgency" required>
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                            </select>
                            <ion-icon name="caret-down-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="right-part-container">
                        <div class="right-part-form">
                            <h2>Select Tasks</h2>
                            <p>Assign an upcoming job by filling up this form and prepare it for processing.</p>
                            <?php
                                $tasks = "SELECT * FROM Task";
                                $task_result = $connect->query($tasks);
                                
                                while ($row = mysqli_fetch_assoc($task_result)) {
                                    echo "<div class=checkbox-task-" . $row['task_id'] . ">
                                            <label for=chk-task-" . $row['task_id'] . ">" . 
                                            "<input type=checkbox name=task[]  id=chk-task-" . $row['task_id'] . " value=" . $row['task_id'] . ">" . 
                                            "<span>" . $row['task_desc'] . "</span></div>";
                                }
                            ?>
                        </div>
                        <div class="input-submit-field">
                            <input type="submit" name="job-assign-form" value="Assign" id="job-assign-btn">
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/search.js"></script>
    <script src="js/modal-form.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>