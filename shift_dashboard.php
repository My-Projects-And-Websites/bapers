<?php
    if(!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login']) || !isset($_SESSION['role']) || $_SESSION['role'] != "Shift Manager") {
        header("Location: index.php");
    }

    echo "hello world! i am a shift manager";
?>

<a href="php/logout.php">Log Out</a>