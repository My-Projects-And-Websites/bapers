<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    date_default_timezone_set('Europe/London');

    $_SESSION['cust_id'] = $_POST['customer-id'];
    $_SESSION['from_date'] = $_POST['from-date'];
    $_SESSION['to_date'] = $_POST['to-date'];
    $_SESSION['datetime_of_generate'] = date('Y-m-d H:i:s');

    header("Location: ../report_templates/customer_report_temp.php");
?>