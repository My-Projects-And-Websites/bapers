<?php
	$db_host = "localhost"; //change to your server add
	$db_user = "root"; // change to your phpMyAdmin username
	$db_pass = "";
	$db_name = "bapers";// database table name

	$connect=mysqli_connect($db_host, $db_user, $db_pass, $db_name);	
	mysqli_set_charset($connect,"utf8");
?>	
