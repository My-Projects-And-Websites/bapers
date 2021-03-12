<?php 
    $fname=$_POST['fname'];//use post get the first name
    $sname=$_POST['sname'];//use post get the last name
    $login=$_POST['staff-email'];//get the email.
    $password=$_POST['staff-password'];//use post get the password
    $conpassword=$_POST['staff-confirm'];//use post get the confirm-password
    $role=$_POST['staff-role'];
    $dep=$_POST['staff-department'];

    if($password != $conpassword){
        echo "<script>alert('Password Not Matched');window.location.href = document.referrer;</script>";//the password doesnt matched.
        exit();
    }

    $hashed_pword = md5($password);

    include('../php/connection.php');//connect db
    $sq="insert into staff(staff_id,staff_fname,staff_sname,staff_role,staff_department,total_time,username_login,password_login) values (null,'$fname','$sname','$role','$dep',0,'$login','$hashed_pword')";//insert the new user.
    $result=mysqli_query($connect,$sq);//run the insert query
    

    if (!$result){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    } else {
        echo '<script language="JavaScript">;alert("Registered successfully!");location.href="../accounts.php";</script>;';
    }
    mysqli_close($connect);//close the db

?>