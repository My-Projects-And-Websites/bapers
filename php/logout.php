<?php
    session_start();
    
    unset($_SESSION['email_login']); // disable the username session
    unset($_SESSION['role']); // disable the role session

    setCookie(session_name(), '', time() - 3600, '/');

    session_destroy();

    header("Location: ../index.php");
?>