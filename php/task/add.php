<?php
    include "../connection.php";

    $task_desc = $_POST['task-description'];
    $task_location = $_POST['task-location'];
    $task_price = $_POST['task-price'];
    $task_duration = $_POST['task-duration'];

    $add_task_sql = "SELECT * FROM Task";
    $add_task_query = $connect->prepare($add_task_sql);
    $add_task_query->execute();
    $add_task_result = $add_task_query->get_result();

    $count = 1;
    while ($add_task_row = $add_task_result->fetch_assoc()) {

        if ($task_desc == $add_task_row['task_desc']) {
            echo "<script>Task already exists!</script>";
            break;
        }
        
        if ($count == mysqli_num_rows($add_task_result)) {
            $insert_task_sql = "INSERT INTO Task (task_id, task_desc, task_location, task_price, task_duration) VALUES (null, ?, ?, ?, ?)";
            $insert_task_query = $connect->prepare($insert_task_sql);
            $insert_task_query->bind_param("ssdi", $task_desc, $task_location, $task_price, $task_duration);
            $insert_task_query->execute();
        }

        $count++;
    }

    if (!$insert_task_query) {
        die('Error: ' . mysqli_error($connect));//if sql query error,then output error
    }
    else {
        echo '<script>;
        alert("Task added successfully!");
        window.location.href = "../../manage_tasks.php";
        </script>;';
    }
?>