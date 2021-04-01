<?php
    // db connection and update late payments
    include "php/connection.php";
    include "php/late.php";

    if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    // if the user role is technician, then redirect to the dashboard page
    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] == "Technician") {
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
    <title>BAPERS | Payments</title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="css/pages/payments/payments.css">
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
            <?php
                // query to select all payment data frmo database
                $payment_sql = "SELECT * FROM Payment";
                $payment_query = $connect->prepare($payment_sql);
                $payment_query->execute();
                // store the result of the query
                $payment_result = $payment_query->get_result();
                
                // return true for this if statement if the role of authenticated user is office manager
                if ($_SESSION['role'] == "Office Manager") {

                    // declare a counter for every late payment
                    $late_count = 0;
                    while ($payment_row = $payment_result->fetch_assoc()) {

                        // increment late count for each late payment and status is not paid
                        if ($payment_row['payment_status'] != "Paid" && $payment_row['payment_late'] == 1) {
                            $late_count++;
                        }
                    }

                    // display pop-up alert for late payments
                    if ($late_count != 0) {
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
                }
            ?>
            <!-- search bar to easily identify any payment -->
            <div class="form-search-find">
                <form action="#" method="POST" class="search-field">
                    <div class="input-search-field">
                        <label for="search-bar"><ion-icon name="search-outline"></ion-icon></label>
                        <!-- input field to enter queries for searching -->
                        <input type="text" id="search-bar" name="search-bar" placeholder="Search">
                    </div>
                </form>
            </div>
            <!-- payment details -->
            <div class="payment-details">
                <!-- payment column names -->
                <div class="payment-detail-tags">
                    <span>Customer</span>
                    <span>Job ID</span>
                    <span>Payment ID</span>
                    <span>Subtotal</span>
                    <span>Discount</span>
                    <span>Payment Status</span>
                </div>
                <!-- list of all payments recorded in the system -->
                <ul id="payment-list">
                    <?php
                        // query to get all fields combined from job and customer tables
                        // only return records where job is complete
                        $job_sql = "SELECT job.*, customer.* 
                        FROM Job
                        INNER JOIN Customer ON customer.cust_id = job.Customercust_id
                        WHERE job_status = 'Completed'";
                        $job_query = $connect->prepare($job_sql);
                        $job_query->execute();
                        // store the data fetched
                        $job_result = $job_query->get_result();

                        // if there are results that were returned, then go through this if statement
                        if (mysqli_num_rows($job_result) != 0) {
                            // loop through the jobs from the query
                            while ($job_row = $job_result->fetch_assoc()) {
                                // this variables holds the id of the job
                                $job_identifier = $job_row['job_id'];
                                // this query selects all payment that are equal to the variable above
                                $paym_sql = "SELECT * FROM Payment WHERE payment_id = ?";
                                $paym_query = $connect->prepare($paym_sql);
                                $paym_query->bind_param("i", $job_identifier);
                                $paym_query->execute();
                                // store the data into a variable from payment squery
                                $paym_result = $paym_query->get_result();

                                // if job status is completed then display this list item
                                if ($job_row['job_status'] == "Completed") {
                                    echo '<li><span>' . $job_row['cust_id_char'] . '</span>' .
                                    '<span>' . $job_row['job_id_char'] . '</span>';

                                    // loop through all the payment results from the previous query
                                    while ($paym_row = $paym_result->fetch_assoc()) {
                                        // number_format function gives two decimal points to float numbers
                                        echo '<span>' . $paym_row['payment_id_char'] . '</span>' .
                                        '<span>£' . number_format((float)$paym_row['payment_total'], 2, '.', '') . '</span>' .
                                        '<span>£' . number_format((float)$paym_row['payment_discount'], 2, '.', '') . '</span>' .
                                        '<span>' . $paym_row['payment_status'] . '</span>';
                                    }

                                    // this button opens the payment type and discount plans for the payment
                                    echo '<button class="open-jobs-payment" onclick=togglePaym(' . $job_row['job_id'] . ')><ion-icon name="caret-down-outline"></ion-icon></button>';
                                    echo '</li>';

                                    // this div block will be displayed depending on the discount plan of the customer
                                    if ($job_row['discount_plan'] == "Fixed" || $job_row['discount_plan'] == "Flexible" || !isset($job_row['discount_plan'])) {
                                        echo '<div class=jobs-payment-details-' . $job_row['job_id'] . '><form class=jobs-payment-form-' . $job_row['job_id'] . ' method=POST action=php/update_paym.php>';
                                    
                                        // display elements for the payment types and relevant card details
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

                                        // if there is no discount plan, display unavailable otherwise display the plan
                                        if (!isset($job_row['discount_plan'])) {
                                            echo '<div class=discount-plan>' . '<h2>Discount Plan</h2><span class=discount-plan-text-' . $job_row['job_id'] . '>Unavailable</span></div>';
                                        }
                                        else {
                                            echo '<div class=discount-plan>' . '<h2>Discount Plan</h2><span>' . $job_row['discount_plan'] .'</span></div>';
                                        }
                                        
                                        // this is the discount rate input where users can enter the discount amount for the job
                                        echo '<div class=discount-rate-' . $job_row['job_id'] . '>' . '<h2>Discount Rate</h2><input type=text name=discount-rate class=discount-rate-input-' . $job_row['job_id'] . ' placeholder="Discount Rate"></div>';
                                        // hidden inputs to serve as identifiers when form is submitted
                                        echo '<input type=hidden name=payment-identifier value=' . $job_row['job_id'] . '>';
                                        echo '<input type=hidden name=discount-plan value=' . $job_row['discount_plan'] . '>';
                                        echo '<div class=input-submit-btn><input type=submit value="Process Payment"></div></div>';
                                    }
                                    // if the discount plan of customer is equal to variable then display this instead
                                    else if ($job_row['discount_plan'] == "Variable") {
                                        echo '<div class=jobs-payment-details-' . $job_row['job_id'] . '><form class=jobs-payment-form-variable-' . $job_row['job_id'] . ' method=POST action=php/update_paym.php>';

                                        // display this block of payment and discount details 
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

                                        // display discount plan
                                        echo '<div class=discount-plan-details-paym><div class=discount-plan>' . '<h2>Discount Plan:<span>' . $job_row['discount_plan'] .'</span></h2></div>';

                                        // select all fields from job_task and task tables
                                        $task_paym_sql = "SELECT job_task.*, task.* 
                                        FROM Job_Task 
                                        INNER JOIN Task ON task.task_id = job_task.Tasktask_id
                                        WHERE job_task.Jobjob_id = ?";
                                        $task_paym_query = $connect->prepare($task_paym_sql);
                                        $task_paym_query->bind_param("i", $job_identifier);
                                        // execute the query
                                        $task_paym_query->execute();
                                        // store the result from the query into a variable
                                        $task_paym_result = $task_paym_query->get_result();

                                        // display this HTML element and child elements will be from looping through the result of the query
                                        echo '<div class=discount-task-details>';
                                        while ($task_paym_row = $task_paym_result->fetch_assoc()) {
                                            echo '<div class=task-details-discount>' . 
                                            '<span>' . $task_paym_row['task_id'] . '</span>' .
                                            '<span>' . $task_paym_row['task_desc'] . '</span>' .
                                            '<span>£' . number_format((float)$task_paym_row['task_price'], 2, '.', '') . '</span>' .
                                            '<input type=text name=discount-rate-var[] placeholder="Discount Rate">' . 
                                            '</div>';
                                        }
                                        echo '</div>';

                                        // hidden inputs to serve as identifiers when this form is submitted
                                        echo '<input type=hidden name=payment-identifier value=' . $job_row['job_id'] . '>';
                                        echo '<input type=hidden name=discount-plan value=' . $job_row['discount_plan'] . '>';
                                        echo '<div class=input-submit-btn><input type=submit value="Process Payment"></div></div>';
                                    }

                                    echo '</form></div>';
                                }
                            }
                        }
                        // if there are no completed jobs from the query, display this text instead
                        else {
                            echo '<li><span>No jobs completed.</span></li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </main>

    <!-- javascript functions -->
    <script src="js/open-sidebar-links.js"></script>
    <script src="js/close-alert.js"></script>
    <script src="js/search-paym.js"></script>
    <script src="js/open-paym-details.js"></script>
    <!-- ionicons is an icon library used to handle all the icons in this page -->
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>