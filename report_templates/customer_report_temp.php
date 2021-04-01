<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    include '../php/connection.php';

    date_default_timezone_set('Europe/London');

    $_SESSION['cust_id'] = $_POST['customer-id'];
    $_SESSION['from_date'] = $_POST['from-date'];
    $_SESSION['to_date'] = $_POST['to-date'];

    $customer = $_SESSION['cust_id'];
    $from = $_SESSION['from_date'];
    $to = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($_SESSION['to_date'])));

    if (empty($_SESSION['from_date']) || empty($_SESSION['to_date'])) {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer'";
    }
    else {
        $find_customer = "SELECT * FROM Job WHERE Customercust_id = '$customer' AND order_time >= '$from' AND order_time <= '$to'";
    }

    $find_customer_result = $connect->query($find_customer);
    
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

    <title>Customer Report | <?php echo $customer_record_row[0] . ' ' . $customer_record_row[1]; ?> </title>

    <link rel="stylesheet" href="../css/report_templates/customer_report/customer_report.css">
    <link rel="stylesheet" href="../css/global.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
    <main id="print-this">
        <div class="heading-section">
            <h1>Customer Report</h1>
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
            <div class="body-section-tags">
                <span>Job ID</span>
                <span>Urgent</span>
                <span>Deadline</span>
                <span>Expected Finish</span>
                <span>Actual Finish</span>
                <span>Cost</span>
            </div>
            <ul class="list-of-jobs-ordered">
                <?php
                    while ($find_customer_row = mysqli_fetch_assoc($find_customer_result)) {
                        $job_id_join_paym = $find_customer_row['job_id'];

                        if ($find_customer_row['job_urgency'] == 0) {
                            $find_customer_row['job_urgency'] = "No";
                        }
                        else {
                            $find_customer_row['job_urgency'] = "Yes";
                        }

                        $paym_sql = "SELECT payment_total, payment_discount FROM Payment WHERE payment_id = '$job_id_join_paym'";
                        $paym_result = $connect->query($paym_sql);
                        $paym_row = mysqli_fetch_row($paym_result);

                        echo '<li class=job-' . $job_id_join_paym . '>' .
                        '<span>' . $job_id_join_paym .'</span>' . 
                        '<span>' . $find_customer_row['job_urgency'] . '</span>' .
                        '<span>' . $find_customer_row['job_deadline'] . '</span>' .
                        '<span>' . $find_customer_row['expected_finish'] . '</span>' .
                        '<span>' . $find_customer_row['actual_finish'] . '</span>';

                        $total_price = $paym_row[0] - $paym_row[1];

                        echo "<span class=total-cost>" . number_format((float)$total_price, 2, '.', '') . '</span></li>';
                    }
                ?>
            </ul>
            <div class="total">
                <span id="pay-cost"></span>
            </div>
        </div>
    </main>

    <div class="utility-btn">
        <button class="back-btn" onclick="window.location.href = '../reports.php';">Return</button>
        <button class="print-btn">Print</button>
    </div>

    <script src="../js/print.js"></script>
    <script src="../js/calculate-cost.js"></script>
</body>
</html>