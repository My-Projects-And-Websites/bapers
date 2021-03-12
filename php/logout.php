<?php
    session_start();
    unset($_SESSION['email_login']); // disable the email session
    unset($_SESSION['role']); // disable the role session

    session_destroy();

    header("Location: ../index.php");
?>