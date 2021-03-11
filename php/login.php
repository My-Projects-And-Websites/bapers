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

        $sql = "SELECT staff_role, username_login, password_login, staff_fname, staff_sname, staff_id, count(1) from `Staff` where username_login = '$login' and password_login = '$pass'";
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
            $_SESSION['fname'] = $row[3];
            $_SESSION['sname'] = $row[4];
            $_SESSION['id'] = $row[5];
            $_SESSION['num_rows'] = $row[6];

            if ($row[0] == "Office Manager") {
                header("Location: ../office_dashboard.php");
            }
            else if ($row[0] == "Receptionist") {
                header("Location: ../recep_dashboard.php");
            }
            else if ($row[0] == "Shift Manager") {
                header("Location: ../shift_dashboard.php");
            }
            else if ($row[0] == "Technician") {
                header("Location: ../tech_dashboard.php");
            }
        }
        else {
            header("Location: ../index.php");
        }
    }
?>