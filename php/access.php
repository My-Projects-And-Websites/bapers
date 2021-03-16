<?php
if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }
//have to login to view this page
if (!isset($_SESSION['email_login'])) {
    echo '<script language="JavaScript">;alert("You are not login!");location.href="../404.php";</script>;';
    exit();}

?>
