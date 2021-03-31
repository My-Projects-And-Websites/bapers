<?php
    include('connection.php');

    $cust_identifier = $_POST['payment-identifier'];

    if (isset($_POST['discount-rate'])) {
        $discount_rate = $_POST['discount-rate'];
    }

    if (isset($_POST['discount-rate-var'])) {
        $discount_rate_var = $_POST['discount-rate-var'];
    }

    $paym_type = $_POST['payment-type-cash-card'];
    $paym_card_name = $_POST['card-name'];
    $paym_card_num = $_POST['card-num'];
    $paym_card_exp = $_POST['exp-date'];
    $paym_card_type = $_POST['card-type'];

    $last_four_digits = substr((string)$paym_card_num, -4);

    $check = false;

    if (!empty($paym_card_name) && !empty($paym_card_num) && !empty($paym_card_exp)) {
        $check = true;
    }
    else {
        $check = false;
    }

    if ($paym_type == "Card" && $check) {
        $paym_type = 1;

        $update_payment_sql = "UPDATE Customer SET payment_type = ? WHERE cust_id = ?";
        $update_payment_query = $connect->prepare($update_payment_sql);
        $update_payment_query->bind_param("ii", $paym_type, $cust_identifier);
        $update_payment_query->execute();

        $update_paym_status_sql = "UPDATE Payment SET payment_status = 'Paid' WHERE payment_id = ?";
        $update_paym_status_query = $connect->prepare($update_paym_status_sql);
        $update_paym_status_query->bind_param("i", $cust_identifier);
        $update_paym_status_query->execute();

        $insert_card_sql = "INSERT INTO cardpayment (card_num, cardholder_name, card_expiry, card_type, Paymentpayment_id) VALUES (?, ?, ?, ?, ?)";
        $insert_card_query = $connect->prepare($insert_card_sql);
        $insert_card_query->bind_param("ssssi", $last_four_digits, $paym_card_name, $paym_card_exp, $paym_card_type, $cust_identifier);
        $insert_card_query->execute();

        mysqli_close($connect);

        if (!$update_payment_query || !$insert_card_query) {
            echo '<script>
            alert("Payment unsuccessful. Try again!");
            window.history.back();
            </script>';
        }
        else {
            echo '<script>
            alert("Payment processed successfully!");
            window.location.href = "../payments.php";
            </script>';
        }
    }
    else if ($paym_type == "Cash"){
        $paym_type = 0;

        $update_payment_sql = "UPDATE Customer SET payment_type = ? WHERE cust_id = ?";
        $update_payment_query = $connect->prepare($update_payment_sql);
        $update_payment_query->bind_param('ii', $paym_type, $cust_identifier);
        $update_payment_query->execute();

        $update_paym_status_sql = "UPDATE Payment SET payment_status = 'Paid' WHERE payment_id = ?";
        $update_paym_status_query = $connect->prepare($update_paym_status_sql);
        $update_paym_status_query->bind_param("i", $cust_identifier);
        $update_paym_status_query->execute();

        if (!$update_payment_query) {
            echo '<script>
            alert("Payment unsuccessful. Try again!");
            window.history.back();
            </script>';
        }
        else {
            echo '<script>
            alert("Payment processed successfully!");
            window.location.href = "../payments.php";
            </script>';
        }
    }
    else {
        echo "<script>alert('Please enter the card details!');
        window.history.back();</script>";
    }
?>