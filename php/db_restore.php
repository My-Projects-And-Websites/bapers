<?php
    include "connection.php";
    //get the dir of the sql file.
    $filename = '../db/bapers.sql';
    $read = fopen($filename, "r+");
    $contents = fread($read, filesize($filename));//read the backup files
    $sql = explode(';', $contents);
    //use sql_query to run the lines of command and re-drop it.
    foreach($sql as $query){
        $result = mysqli_query($connect, $query);
    }
    //close the file.
    fclose($read);

    echo "<script>
    alert('Database successfully imported!');
    window.location.href = '../dashboard.php';
    </script>";
?>
