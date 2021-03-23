<?php
$time =15; //how long it run again
$url = "localhost/php/autorun.php"; // let it access. 

include('../php/alert.php');

sleep( $time );//stop for the time that set.
file_get_contents ( $url ); //run the php.
?>