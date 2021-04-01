<?php
    // start the session if not started yet
    if (!isset($_SESSION)) {
        session_start();
    }

    // include database connection
    include '../php/connection.php';

    // set the timezone to European time
    date_default_timezone_set('Europe/London');

    // get the id of the customer and dates from the form in the reports.php
    $_SESSION['cust_id'] = $_POST['customer-id'];
    $_SESSION['from_date'] = $_POST['from-date'];
    $_SESSION['to_date'] = $_POST['to-date'];

    // assign these session variables into another variable 
    $customer = $_SESSION['cust_id'];
    $from = $_SESSION['from_date'];
    $to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($_SESSION['to_date'])));

    // if the from or to date is empty, display all the jobs ordered by the customer
    if (empty($_SESSION['from_date']) || empty($_SESSION['to_date'])) {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer'";
    }
    else {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer' AND order_time >= '$from' AND order_time <= '$to'";
    }

    // execute the query
    $find_customer_result = $connect->query($find_customer);
    
    // get the name of the specified customer
    $customer_record = "SELECT cust_fname, cust_sname FROM Customer WHERE cust_id = " . $_SESSION['cust_id'];
    $customer_record_result = $connect->query($customer_record);
    $customer_record_row = mysqli_fetch_row($customer_record_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- tab title, contains name of user -->
    <title>Customer Report | <?php echo $customer_record_row[0] . ' ' . $customer_record_row[1]; ?> </title>

    <!-- page stylesheets -->
    <link rel="stylesheet" href="../css/report_templates/customer_report/customer_report.css">
    <link rel="stylesheet" href="../css/global.css">

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <!-- main heading -->
            <h1>Customer Report</h1>
            <!-- small text explaining the contents of the report -->
            <p>
                Report for <?php echo $customer_record_row[0] . ' ' . $customer_record_row[1]; ?>

                <?php
                    if (!empty($_SESSION['from_date']) || !empty($_SESSION['to_date'])) { 
                        echo ' | ' . $_SESSION['from_date'] . ' - ' . $_SESSION['to_date']; 
                    }
                ?>
            </p>
        </div>
        <div class="body-section">
            <!-- column names for the data that is to be generated -->
            <div class="body-section-tags">
                <span>Job ID</span>
                <span>Urgent</span>
                <span>Deadline</span>
                <span>Expected Finish</span>
                <span>Actual Finish</span>
                <span>Cost</span>
            </div>
            <!-- list of dates where the user ordered a job -->
            <ul class="list-of-jobs-ordered">
                <?php
                    // loop through the results from the first query
                    while ($find_customer_row = mysqli_fetch_assoc($find_customer_result)) {
                        // assign the id of the job which is ordered by the customer
                        $job_id_join_paym = $find_customer_row['job_id'];

                        // if the urgency is set to 0, display no, else display yes
                        if ($find_customer_row['job_urgency'] == 0) {
                            $find_customer_row['job_urgency'] = "No";
                        }
                        else {
                            $find_customer_row['job_urgency'] = "Yes";
                        }

                        // get the total of the job from the payments table
                        $paym_sql = "SELECT payment_total, payment_discount FROM Payment WHERE payment_id = '$job_id_join_paym'";
                        $paym_result = $connect->query($paym_sql);
                        // execute the query
                        $paym_row = mysqli_fetch_row($paym_result);

                        // display the relevant details for the customer orders
                        echo '<li class=job-' . $job_id_join_paym . '>' .
                        '<span>' . $job_id_join_paym .'</span>' . 
                        '<span>' . $find_customer_row['job_urgency'] . '</span>' .
                        '<span>' . $find_customer_row['job_deadline'] . '</span>' .
                        '<span>' . $find_customer_row['expected_finish'] . '</span>' .
                        '<span>' . $find_customer_row['actual_finish'] . '</span>';

                        // get the total price by subtracting the discount from the total
                        $total_price = $paym_row[0] - $paym_row[1];

                        // put two decimal points for the number
                        echo "<span class=total-cost>" . number_format((float)$total_price, 2, '.', '') . '</span></li>';
                    }
                ?>
            </ul>
            <div class="total">
                <!-- used javascript to calculate the total cost of the orders -->
                <span id="pay-cost"></span>
            </div>
        </div>
    </main>

    <!-- onclick of the return button, go back to generate reports page -->
    <!-- onclick of print button, remove buttons from page and open print dialog -->
    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php';">Return</button>
        <button class="print-btn">Print</button>
    </div>

    <!-- javascript functions -->
    <script src="../js/print.js"></script>
    <script src="../js/calculate-cost.js"></script>
</body>
</html>