<?php
    if(!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Receptionist") {
        header("Location: index.php");
    }

    echo "hello world! i am a receptionist";
?>

<a href="php/logout.php">Log Out</a>