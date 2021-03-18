<?php
    include "php/connection.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Office Manager" ) {
        header("Location: office_dashboard.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BAPERS | Accounts</title>

    <link rel="stylesheet" href="css/pages/process_job/process_job.css">
    <link rel="stylesheet" href="css/dashboard/dashboard.css">
    <link rel="stylesheet" href="css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main class="dash-template">
        <section class="sidebar">
            <a href="office_dashboard.php" class="sidebar-link">
                <ion-icon name="apps-outline"></ion-icon>
                <span>Overview</span>
            </a>
            <a href="" class="sidebar-link">
                <ion-icon name="card-outline"></ion-icon>
                <span>Payments</span>
            </a>
            <a href="accounts.php" class="sidebar-link">
                <ion-icon name="add-circle-outline"></ion-icon>
                <span>Accounts</span>
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
                    <a href=""><span>Accounts</span></a>
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
            <div class="form-search-find">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search job ID, status, anything...">
                    </div>
                </form>
            </div>
            <div class="job-details">
                <div class="job-detail-tags">
                    <span>ID</span>
                    <span>Customer</span>
                    <span>Urgent</span>
                    <span>Deadline</span>
                    <span>Finish Time</span>
                    <span>Expected Time</span>
                    <span>Status</span>
                </div>
                <ul id="job-list">
                    <?php
                        $job_query = "SELECT * FROM `Job`";
                        $job_result = $connect->query($job_query);
    
                        if (mysqli_num_rows($job_result) != 0) {
                            while ($row = mysqli_fetch_assoc($job_result)) {
                                if ($row['job_urgency'] == 0) { 
                                    $row['job_urgency'] = "No";
                                }
                                else {
                                    $row['job_urgency'] = "Yes";
                                }

                                $job_id_find = $row['job_id'];

                                $task_query = "SELECT job_task.Tasktask_id, job_task.task_status, task.task_id, task.task_desc, task.task_location, task.task_duration 
                                FROM Job_Task
                                INNER JOIN task 
                                ON job_task.Tasktask_id = task.task_id
                                WHERE job_task.Jobjob_id = $job_id_find";
                                $task_query_results = $connect->query($task_query);

                                echo "<li id=job-" . $row['job_id'] . 
                                '><span>' . $row['job_id'] .
                                '</span><span>' . $row['Customercust_id'] .
                                '</span><span>' . $row['job_urgency'] . 
                                '</span><span>' . $row['job_deadline'] . 
                                '</span><span>' . $row['actual_finish'] . 
                                '</span><span>' . $row['expected_finish'] . 
                                '</span><span>' . $row['job_status'] . 
                                '</span><button class=drop-details-' . $row['job_id'] . ' onclick=()><ion-icon name="caret-down-outline"></ion-icon>' . 
                                '</button></li>' .
                                '<div class=job-details-' . $row['job_id'] .
                                '><form action=php/update-job.php class=form-job-details-' . $row['job_id'] .
                                '><div class="input-actual-finish"><label for="actual-finish-job">Actual Finish</label><input type=datetime-local name=actual-finish-job id="actual-finish-job"></div>' .
                                '<div class="input-expected-finish"><label for="expected-finish-job">Expected Finish</label><input type=datetime-local name=expected-finish-job id="expected-finish-job"></div>';

                                while ($task_query_row = mysqli_fetch_assoc($task_query_results)) {
                                    echo $task_query_row['task_desc'];
                                }

                                echo '</form></div>';
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

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/search-job.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>