<?php
    if(!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || $_SESSION['role'] != "Administrator") {
        header("Location: index.php");
    }

    echo "hello world! i am an admin";
?>

<a href="php/logout.php">Log Out</a>