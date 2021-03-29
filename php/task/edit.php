<?php
    include "../connection.php";

    $task_identifier = $_POST['task-identifier'];

    $task_desc = $_POST['task-description'];
    $task_location = $_POST['task-location'];
    $task_price = $_POST['task-price'];
    $task_duration = $_POST['task-duration'];

    $edit_task_sql = "UPDATE Task SET task_desc = ?, task_location = ?, task_price = ?, task_duration = ? WHERE task_id = ?";
    $edit_task_query = $connect->prepare($edit_task_sql);
    $edit_task_query->bind_param("ssdii", $task_desc, $task_location, $task_price, $task_duration, $task_identifier);
    $edit_task_query->execute();

    if (!$edit_task_query) {
        echo "Error: " . mysqli_error($connect);
    }
    else {
        echo '<script>
        alert("Task updated successfully!");
        window.location.href = "../../manage_tasks.php";
        </script>';
    }
    

    // header("Location: ../../manage_tasks.php");
?>