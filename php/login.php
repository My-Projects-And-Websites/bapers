<?php
    include 'connection.php';

    if(!isset($_SESSION)) {
        session_start(); // start the session if it still does not exist
    }

    if ($connect->connect_errno) {
        echo "Connection failed! Something went wrong.";
        exit();
    }
    else {
        $login = $_POST['login-email'];
        $pass = $_POST['login-password'];

        $sql = "SELECT staff_role, username_login, password_login from `Staff` where username_login = '$login' and password_login = '$pass'";
        $result = $connect->query($sql);
        $row = mysqli_fetch_row($result);

        if (!isset($row[1]) || !isset($row[2]) || $login != $row[1] && $pass != $row[2] ) {
            echo "<script language='javascript'>
                      alert('Please enter valid credentials.');
                      window.location.href = 'http://localhost/BAPERS/index.php';
                  </script>";
        }
        else if ($login == $row[1] && $pass == $row[2]) {
            $_SESSION['email_login'] = $login;
            $_SESSION['role'] = $row[0];

            header("Location: ../admin_dashboard.php");
        }
    }
?>