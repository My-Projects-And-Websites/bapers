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

    <title>BAPERS | Payments</title>

    <link rel="stylesheet" href="css/pages/payments/payments.css">
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
            <?php
                if ($_SESSION['role'] == "Office Manager") {
                    echo "<div class='payment-alert'>
                        <div class='modal-late-payment'>
                            <h2>Late Payment Alert</h2>
                            <p>
                                There are overdue payments that needs your attention.
                            </p>
                            <button id='close-payment-alert'>View</button>
                        </div>
                    </div>";
                }
            ?>
            <div class="form-search-find">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search">
                    </div>
                </form>
            </div>
            <div class="payment-details">
                <div class="payment-detail-tags">
                    <span>Customer</span>
                    <span>Job ID</span>
                    <span>Payment ID</span>
                    <span>Discount</span>
                    <span>Total</span>
                    <span>Payment Status</span>
                </div>
                <ul id="payment-list">
                    <?php
                        $job_sql = "SELECT job.*, customer.* 
                        FROM Job
                        INNER JOIN Customer ON customer.cust_id = job.Customercust_id";
                        $job_query = $connect->prepare($job_sql);
                        $job_query->execute();
                        $job_result = $job_query->get_result();

                        while ($job_row = $job_result->fetch_assoc()) {
                            $job_identifier = $job_row['job_id'];
                            $paym_sql = "SELECT * FROM Payment WHERE payment_id = ?";
                            $paym_query = $connect->prepare($paym_sql);
                            $paym_query->bind_param("i", $job_identifier);
                            $paym_query->execute();
                            $paym_result = $paym_query->get_result();

                            if ($job_row['job_status'] == "Completed") {
                                echo '<li><span>' . $job_row['cust_id_char'] . '</span>' .
                                '<span>' . $job_row['job_id_char'] . '</span>';

                                while ($paym_row = $paym_result->fetch_assoc()) {
                                    echo '<span>' . $paym_row['payment_id_char'] . '</span>' .
                                    '<span>£' . number_format((float)$paym_row['payment_discount'], 2, '.', '') . '</span>' .
                                    '<span>£' . number_format((float)$paym_row['payment_total'], 2, '.', '') . '</span>' .
                                    '<span>' . $paym_row['payment_status'] . '</span>';
                                }

                                echo '<button class="open-jobs-payment" onclick=togglePaym(' . $job_row['job_id'] . ')><ion-icon name="caret-down-outline"></ion-icon></button>';
                                echo '</li>';

                                if ($job_row['discount_plan'] == "Fixed" || $job_row['discount_plan'] == "Flexible" || !isset($job_row['discount_plan'])) {
                                    echo '<div class=jobs-payment-details-' . $job_row['job_id'] . '><form class=jobs-payment-form-' . $job_row['job_id'] . ' method=POST action=php/update_paym.php>';
                                
                                    echo '<div class=payment-type-details>' .
                                    '<div class=payment-type>' . '<h2>Payment Type</h2>' . 
                                    '<select name=payment-type-cash-card class=payment-type-cash-card-' . $job_row['job_id'] . ' onchange=changePaymType(' . $job_row['job_id'] . ')>' .
                                    '<option value=Cash>Cash</option>' .
                                    '<option value=Card>Card</option>' .
                                    '</select>' . '</div>' .
                                    '<div class=card-name-' . $job_row['job_id'] . '>' . '<h2>Cardholder Name</h2><input type=text name=card-name class=card-name-details-' . $job_row['job_id'] . ' placeholder="Cardholder Name"></div>' .
                                    '<div class=card-num-' . $job_row['job_id'] . '>' . '<h2>Card Number</h2><input type=text name=card-num class=card-num-details-' . $job_row['job_id'] . ' placeholder="Card Number"></div>' .
                                    '<div class=card-exp-' . $job_row['job_id'] . '>' . '<h2>Expiry Date</h2><input type=month name=exp-date class=exp-date-details-' . $job_row['job_id'] . ' placeholder="Expiry Date"></div>' .
                                    '<div class=card-type-' . $job_row['job_id'] . '>' . '<h2>Card Type</h2><select name=card-type class=card-type-details-' . $job_row['job_id'] . '>' .
                                    '<option value="Visa">Visa</option>' .
                                    '<option value="Mastercard">Mastercard</option>' .
                                    '<option value="Maestro">Maestro</option>' .
                                    '</select></div>';
                                    echo '</div>';

                                    echo '<div class=discount-details>';

                                    if (!isset($job_row['discount_plan'])) {
                                        echo '<div class=discount-plan>' . '<h2>Discount Plan</h2><span class=discount-plan-text-' . $job_row['job_id'] . '>Unavailable</span></div>';
                                    }
                                    else {
                                        echo '<div class=discount-plan>' . '<h2>Discount Plan</h2><span>' . $job_row['discount_plan'] .'</span></div>';
                                    }
                                    
                                    echo '<div class=discount-rate>' . '<h2>Discount Rate</h2><input type=text name=discount-rate class=discount-rate-input-' . $job_row['job_id'] . ' placeholder="Discount Rate"></div>';
                                    echo '<input type=hidden name=payment-identifier value=' . $job_row['job_id'] . '>';
                                    echo '<div class=input-submit-btn><input type=submit value=Apply></div></div>';
                                }
                                else if ($job_row['discount_plan'] == "Variable") {
                                    echo '<div class=jobs-payment-details-' . $job_row['job_id'] . '><form class=jobs-payment-form-variable-' . $job_row['job_id'] . ' method=POST action=php/update_paym.php>';

                                    echo '<div class=payment-type-details>' .
                                    '<div class=payment-type>' . '<h2>Payment Type</h2>' . 
                                    '<select name=payment-type-cash-card class=payment-type-cash-card-' . $job_row['job_id'] . ' onchange=changePaymType(' . $job_row['job_id'] . ')>' .
                                    '<option value=Cash>Cash</option>' .
                                    '<option value=Card>Card</option>' .
                                    '</select>' . '</div>' .
                                    '<div class=card-name-' . $job_row['job_id'] . '>' . '<h2>Cardholder Name</h2><input type=text name=card-name class=card-name-details-' . $job_row['job_id'] . ' placeholder="Cardholder Name"></div>' .
                                    '<div class=card-num-' . $job_row['job_id'] . '>' . '<h2>Card Number</h2><input type=text name=card-num class=card-num-details-' . $job_row['job_id'] . ' placeholder="Card Number"></div>' .
                                    '<div class=card-exp-' . $job_row['job_id'] . '>' . '<h2>Expiry Date</h2><input type=month name=exp-date class=exp-date-details-' . $job_row['job_id'] . ' placeholder="Expiry Date"></div>' .
                                    '<div class=card-type-' . $job_row['job_id'] . '>' . '<h2>Card Type</h2><select name=card-type class=card-type-details-' . $job_row['job_id'] . '>' .
                                    '<option value="Visa">Visa</option>' .
                                    '<option value="Mastercard">Mastercard</option>' .
                                    '<option value="Maestro">Maestro</option>' .
                                    '</select></div>';
                                    echo '</div>';

                                    echo '<div class=discount-plan-details-paym><div class=discount-plan>' . '<h2>Discount Plan:<span>' . $job_row['discount_plan'] .'</span></h2></div>';

                                    $task_paym_sql = "SELECT job_task.*, task.* 
                                    FROM Job_Task 
                                    INNER JOIN Task ON task.task_id = job_task.Tasktask_id
                                    WHERE job_task.Jobjob_id = ?";
                                    $task_paym_query = $connect->prepare($task_paym_sql);
                                    $task_paym_query->bind_param("i", $job_identifier);
                                    $task_paym_query->execute();
                                    $task_paym_result = $task_paym_query->get_result();

                                    echo '<div class=discount-task-details>';
                                    while ($task_paym_row = $task_paym_result->fetch_assoc()) {
                                        echo '<div class=task-details-discount>' . 
                                        '<span>' . $task_paym_row['task_id'] . '</span>' .
                                        '<span>' . $task_paym_row['task_desc'] . '</span>' .
                                        '<span>£' . number_format((float)$task_paym_row['task_price'], 2, '.', '') . '</span>' .
                                        '<input type=text name=discount-rate[] placeholder="Discount Rate">' . 
                                        '</div>';
                                    }
                                    echo '</div>';

                                    echo '<input type=hidden name=payment-identifier value=' . $job_row['job_id'] . '>';

                                    echo '<div class=input-submit-btn><input type=submit value="Apply"></div></div>';
                                }

                                echo '</form></div>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </main>

    <script src="js/open-sidebar-links.js"></script>
    <script src="js/close-alert.js"></script>
    <script src="js/search-paym.js"></script>
    <script src="js/open-paym-details.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>