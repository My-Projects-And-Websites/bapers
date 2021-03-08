<?php
    if(!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

    if (!isset($_SESSION['email_login'])) {
        header("Location: index.php");
    }

    echo "hello world!";
?>

<a href="php/logout.php">Log Out</a>