<?php 
    $fname=$_POST['fname'];//use post get the first name
    $sname=$_POST['sname'];//use post get the last name
    $email=$_POST['customer-email'];//get the email.
    $address=$_POST['customer-address-1'] . ', ' . $_POST['customer-address-2'] . ', ' . $_POST['customer-zip-code'];
    $mobile=$_POST['customer-mobile'];

    include('../php/connection.php');//connect db

    $find_customer_sql = "SELECT cust_email FROM Customer";
    $find_customer_query = $connect->prepare($find_customer_sql);
    $find_customer_query->execute();
    $find_customer_result = $find_customer_query->get_result();

    $num_rows = mysqli_num_rows($find_customer_result) + 1;
    $cust_id_char = "ACC#" . strval($num_rows);

    $x = 0;
    while ($find_customer_row = $find_customer_result->fetch_assoc()) {
        
        if ($email == $find_customer_row['cust_email']) {
            echo '<script language="JavaScript">;
            alert("Email is already taken, registration failed.");
            window.location.href="../customer_accounts.php";
            </script>;';
            break;
        }

        $x++;
    }

    if ($x == mysqli_num_rows($find_customer_result)) {
        $sq="INSERT INTO Customer(cust_id,cust_id_char,cust_fname,cust_sname,cust_email,cust_address,cust_type,cust_mobile,discount_plan,payment_type) 
        VALUES (null,'$cust_id_char','$fname','$sname','$email','$address',0,'$mobile',null, null)";//insert the new customer.
        $result=mysqli_query($connect,$sq);//run the insert query
    }
    
    if (!$result){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;
        alert("Registered successfully!");
        location.href="../customer_accounts.php";
        </script>;';
    }
    mysqli_close($connect);//close the db

?>