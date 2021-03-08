<?php
    session_start();
    unset($_SESSION['email_login']); // disable the username session
    unset($_SESSION['role']); // disable the username session

    session_destroy();

    header("Location: ../index.php");
?>