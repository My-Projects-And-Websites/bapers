<?php
if (!isset($_SESSION)) {
        session_start(); // start the session if not started yet
    }

//differents roles only allowed access their own page.
$role_redir;
switch($_SESSION['role']){
    case "Office Manager":
        $role_redir = '/office_dashboard.php';
        break;
    case "Receptionists":
        $role_redir = '/recp_dashboard.php';
        break;
    case "Administrator":
        $role_redir = '/admin_dashboard.php';
        break;
    case "Shift Manager":
        $role_redir = '/shift_dashboard.php';
        break; 
    case "Technicians":
        $role_redir = '/tech_dashboard.php';
        break; 
        }

$current_link=$_SERVER['PHP_SELF'];//get the current site.location

if($role_redir != $current_link && isset($_SESSION['email_login']) ){
    header("Location:".$role_redir);
}
else{
    header("Location: ../404.php");
}


?>