<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Technician") {
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

    <title>BAPERS | Accept Job</title>

    <link rel="stylesheet" href="css/pages/accept_job/accept_job.css">
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
                                $tasks_result = $connect->query($tasks);

                                while ($tasks_row = mysqli_fetch_assoc($tasks_result)) {
                                    echo "<div class=checkbox-task-" . $tasks_row['task_id'] . ">" .
                                    "<label for=task-id-" . $tasks_row['task_id'] . ">" .
                                    "<input type=checkbox name=task[] value=" . $tasks_row['task_id'] . " id=task-id-" . $tasks_row['task_id'] . ">" .
                                    "<span>" . $tasks_row['task_desc'] . "</span>" .
                                    "</label>" .
                                    "<select name=assign-task[] id=assign-staff-to-" . $tasks_row['task_id'] . ">";

                                    $staffs = "SELECT * FROM Staff";
                                    $staffs_result = $connect->query($staffs);

                                    while ($staffs_row = mysqli_fetch_assoc($staffs_result)) {
                                        echo "<option value=" . $staffs_row['staff_id'] . ">" . $staffs_row['staff_id'] . ' | ' . $staffs_row['staff_fname'] . ' ' . $staffs_row['staff_sname'] . "</option>";
                                    }

                                    echo "</select></div>";
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