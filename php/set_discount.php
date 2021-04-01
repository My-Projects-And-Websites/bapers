<?php
    include "connection.php";

    $customer = $_POST['customer-id'];//GET THE CUSTOMER ID FROM FRONT-END
    $discount = $_POST['discount'];//GET THE CUSTOMER DISCOUNT PLAN FROM FRONT-END

    if ($discount == "") {
        $discount = null;
    }

    $discount_sql = "UPDATE Customer SET discount_plan = ? WHERE cust_id = ?";//UPDATE THE DISCOUNT PLAN.
    $discount_query = $connect->prepare($discount_sql);
    $discount_query->bind_param("si", $discount, $customer);
    $discount_query->execute();

    header("Location: ../customer_accounts.php");
?>