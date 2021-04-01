<?php
    include "connection.php";

    $fname=$_POST['fname'];//use post get the first name
    $sname=$_POST['sname'];//use post get the last name
    $login=$_POST['staff-email'];//get the email.
    $password=$_POST['staff-password'];//use post get the password
    $conpassword=$_POST['staff-confirm'];//use post get the confirm-password
    $role=$_POST['staff-role'];
    $dep=$_POST['staff-department'];

    if ($password != $conpassword){
        echo "<script>alert('Password Not Matched');window.location.href = document.referrer;</script>";//the password doesnt matched.
        exit();
    }

    $staff_sql = "SELECT * FROM Staff";
    $staff_query = $connect->prepare($staff_sql);
    $staff_query->execute();
    $staff_result = $staff_query->get_result();

    $num_rows = mysqli_num_rows($staff_result) + 1;
    $staff_id_char = "STAFF#" . strval($num_rows);

    include('../php/connection.php');//connect db
    include('send_email.php');//SEND INFO EMAIL

    $i = 0;
    while ($staff_row = $staff_result->fetch_assoc()) {

        if ($login == $staff_row['username_login']) {
            echo '<script language="JavaScript">;
            alert("Email is already taken, registration failed.");
            window.location.href="../accounts.php";
            </script>;';
            break;
        }
        
        $i++;
    }
    
    if ($i == mysqli_num_rows($staff_result)) {
        $sq="insert into staff(staff_id,staff_id_char,staff_fname,staff_sname,staff_role,staff_department,total_time,username_login,password_login) values (null,'$staff_id_char','$fname','$sname','$role','$dep',0,'$login','$password')";//insert the new user.
        $result=mysqli_query($connect,$sq);//run the insert query
    }

    if (!$result){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Registered successfully!");location.href="../accounts.php";</script>;';
        staff_reg_email($login,$fname." ".$sname,$login,$password,$dep,$role);
    }
    mysqli_close($connect);//close the db

?>