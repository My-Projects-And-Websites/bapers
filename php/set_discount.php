<?php
    include "connection.php";

    $customer = $_POST['customer-id'];
    $discount = $_POST['discount'];

    $discount_sql = "UPDATE Customer SET discount_plan = ? WHERE cust_id = ?";
    $discount_query = $connect->prepare($discount_sql);
    $discount_query->bind_param("si", $discount, $customer);
    $discount_query->execute();

    header("Location: ../customer_accounts.php");
?>