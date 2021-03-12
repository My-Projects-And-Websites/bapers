<?php 
    $fname=$_POST['fname'];//use post get the first name
    $sname=$_POST['sname'];//use post get the last name
    $email=$_POST['customer-email'];//get the email.
    $address=$_POST['customer-address-1'] . ' ' . $_POST['customer-address-2'] . ' ' . $_POST['customer-zip-code'];
    $mobile=$_POST['customer-mobile'];

    include('../php/connection.php');//connect db
    $sq="insert into Customer(cust_id,cust_fname,cust_sname,cust_email,cust_address,cust_status,cust_mobile,discount_plan,payment_type) 
    values (null,'$fname','$sname','$email','$address',0,'$mobile',null,0)";//insert the new customer.
    $result=mysqli_query($connect,$sq);//run the insert query
    

    if (!$result){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Registered successfully!");location.href="../accept_job.php";</script>;';
    }
    mysqli_close($connect);//close the db

?>