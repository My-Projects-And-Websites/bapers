<?php
    include('connection.php');// FOR CONNECT DATABASE.
    include('discount.php'); // FOR CALCULATION OF DISCOUNT.
    $cust_identifier = $_POST['payment-identifier']; // GET JOB_ID FROM FRONT END.

    if (isset($_POST['discount-rate'])) {
        $discount_rate = $_POST['discount-rate'];//GET THE DISCOUNT RATE(INT) FROM FRONT END.
    }

    if (isset($_POST['discount-rate-var'])) {//GET THE DISCOUNT RATE[] FROM FRONT END.
        $discount_rate_var = $_POST['discount-rate-var'];
    }

    $paym_type = $_POST['payment-type-cash-card'];//GET THE PAYMENT TYPE FROM FRONT END.
    $paym_card_name = $_POST['card-name'];//GET THE CARD_HOLDER NAME FROM FRONT END.
    $paym_card_num = $_POST['card-num'];//GET THE CARD NUMBERS FROM FRONT END.
    $paym_card_exp = $_POST['exp-date'];//GET THE EXPIRE DATE FROM FRONT END.
    $paym_card_type = $_POST['card-type'];//GET THE CARD TYPE FROM FRONT END.
    $discount_plan = $_POST['discount-plan'];//GET THE discount_plan FROM FRONT END.

    $last_four_digits = substr((string)$paym_card_num, -4);

    $check = false;

    if (!empty($paym_card_name) && !empty($paym_card_num) && !empty($paym_card_exp)) {//check the user input the card details or not.
        $check = true;
    }
    else {
        $check = false;
    }

    if ($paym_type == "Card" && $check) {//PUSH THE CARD DETAILS OF THE PAYMENT INTO THE DATABASE.
        $paym_type = 1;

        $update_payment_sql = "UPDATE Customer SET payment_type = ? WHERE cust_id = ?";//PAYMENT_TYPE.
        $update_payment_query = $connect->prepare($update_payment_sql);
        $update_payment_query->bind_param("ii", $paym_type, $cust_identifier);
        $update_payment_query->execute();

        $update_paym_status_sql = "UPDATE Payment SET payment_status = 'Paid' WHERE payment_id = ?";//PAYMENT_STATUS.
        $update_paym_status_query = $connect->prepare($update_paym_status_sql);
        $update_paym_status_query->bind_param("i", $cust_identifier);
        $update_paym_status_query->execute();

        $insert_card_sql = "INSERT INTO cardpayment (card_num, cardholder_name, card_expiry, card_type, Paymentpayment_id) VALUES (?, ?, ?, ?, ?)";
        $insert_card_query = $connect->prepare($insert_card_sql);
        $insert_card_query->bind_param("ssssi", $last_four_digits, $paym_card_name, $paym_card_exp, $paym_card_type, $cust_identifier);
        $insert_card_query->execute();

        mysqli_close($connect);

        if($discount_rate>100){//DISCOUNT RATE NOT BE ALLOWED TO OVER 100.
            echo '<script/>alert("Discount Rate cannot be over 100");<script>';
        } else if ($discount_plan == "Fixed" || $discount_plan == "Flexible") {
            discount($discount_plan,$cust_identifier,$discount_rate);
        } else if ($discount_plan == "Variable") {
            Variable_discount($cust_identifier,$discount_rate_var);//APPLY THE DISCOUNT CALCULATION.
        } 

        if (!$update_payment_query) {//ERROR SITUATIONS.
            echo '<script>alert("Payment unsuccessful. Try again!");window.history.back();</script>';}
       else {
           echo '<script>alert("Payment processed successfully!");window.location.href = "../payments.php";</script>'; }
    }
    else if ($paym_type == "Cash"){//FOR CASH PAYMENT, UPDATE THE PAYMENT DATABASE.
        $paym_type = 0;

        $update_payment_sql = "UPDATE Customer SET payment_type = ? WHERE cust_id = ?";//SET PAYMENT TYPE = CASH.
        $update_payment_query = $connect->prepare($update_payment_sql);
        $update_payment_query->bind_param('ii', $paym_type, $cust_identifier);
        $update_payment_query->execute();

        $update_paym_status_sql = "UPDATE Payment SET payment_status = 'Paid' WHERE payment_id = ?";//SET CUSTOMER BEEN PAID.
        $update_paym_status_query = $connect->prepare($update_paym_status_sql);
        $update_paym_status_query->bind_param("i", $cust_identifier);
        $update_paym_status_query->execute();

        if($discount_rate>100){
            echo '<script/>alert("Discount Rate cannot be over 100");<script>';
        } else if ($discount_plan == "Fixed" || $discount_plan == "Flexible") {
            discount($discount_plan,$cust_identifier,$discount_rate);
        } else if ($discount_plan == "Variable") {
            Variable_discount($cust_identifier,$discount_rate_var);//APPLY THE DISCOUNT CALCULATION.
        } 

        if (!$update_payment_query) {
             echo '<script>alert("Payment unsuccessful. Try again!");window.history.back();</script>';}
        else {
            echo '<script>alert("Payment processed successfully!");window.location.href = "../payments.php";</script>'; }
    }
    else {
        echo "<script>alert('Please enter the card details!');
        window.history.back();</script>";
    }
?>