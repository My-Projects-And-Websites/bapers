<?php
    include('alert.php');
    $run = include 'autorun_switch.php';
    $time = 10;// updated every 15 sec.
    $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 
    //  ToDo the task
    
    if(!$run) die(); // config from switch.
    late_payment_alert();
    deadline_alert();
    sleep($time);
    file_get_contents($url);
?>