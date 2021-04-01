<?php
    include "connection.php";

    if (isset($_POST['valued'])) {//GET VALUED CONFRIM FROM FRONT END
        $valued = $_POST['valued'];
    }
    
    $customers = $_POST['cust-identifier']; // GET THE CUSTOMER ID

    $i = 0;
    foreach ($valued as $value) {
        $i++;
        // UPDATE THE VALUED CUSTOMER STATUS.
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