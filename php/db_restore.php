<?php
    include "connection.php";
    
    $filename = '../db/bapers.sql';
    $read = fopen($filename, "r+");
    $contents = fread($read, filesize($filename));
    $sql = explode(';', $contents);

    foreach($sql as $query){
        $result = mysqli_query($connect, $query);
    }

    fclose($read);

    echo "<script>
    alert('Database successfully imported!');
    window.location.href = '../dashboard.php';
    </script>";
?>