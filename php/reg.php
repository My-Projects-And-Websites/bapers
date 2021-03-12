<?php 
    $fname=$_POST['first_name'];//use post get the first name
    $sname=$_POST['last_name'];//use post get the last name
    $login=$_POST['email'];//get the email.
    $password=$_POST['password'];//use post get the password
    $conpassword=$_POST['password_repeat'];//use post get the confirm-password
    $role=$_POST['Role_input'];
    $dep=$_POST['Department_input'];

    if($password != $conpassword){
        echo "<script>alert('Password Not Matched');window.location.href = document.referrer;</script>";//the password doesnt matched.
        exit();
    }

    include('../php/connection.php');//connect db
    $sq="insert into staff(staff_id,staff_fname,staff_sname,staff_role,staff_department,total_time,username_login,password_login) values (null,'$fname','$sname','$role','$dep',0,'$login','$password')";//insert the new user.
    $reslut=mysqli_query($connect,$sq);//run the insert query
    


    if (!$reslut){
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    }else{
        echo '<script language="JavaScript">;alert("Done!");location.href="../index.php";</script>;';
    }
    mysqli_close($connect);//close the db

?>