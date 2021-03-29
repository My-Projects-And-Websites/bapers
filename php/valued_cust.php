<?php
    include "connection.php";

    if (isset($_POST['valued'])) {
        $valued = $_POST['valued'];
    }
    
    $customers = $_POST['cust-identifier'];

    $i = 0;
    foreach ($valued as $value) {
        $i++;

        if ($value == 1) {
            $customer_sql = "UPDATE Customer SET cust_type = ? WHERE cust_id = ?";
            $customer_query = $connect->prepare($customer_sql);
            $customer_query->bind_param("ii", $value, $i);
            $customer_query->execute();
        }
        else if ($value == 0) {
            $customer_sql = "UPDATE Customer SET cust_type = ? WHERE cust_id = ?";
            $customer_query = $connect->prepare($customer_sql);
            $customer_query->bind_param("ii", $value, $i);
            $customer_query->execute();
        }
    }

    header("Location: ../customer_accounts.php");
?>