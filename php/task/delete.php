<?php
    include "../connection.php";

    $task_identifier = $_POST['task-identifier'];

    $delete_task_sql = "DELETE FROM Task WHERE task_id = ?";
    $delete_task_query = $connect->prepare($delete_task_sql);
    $delete_task_query->bind_param("i", $task_identifier);
    $delete_task_query->execute();

    $delete_job_task_sql = "DELETE FROM Job_Task WHERE Tasktask_id = ?";
    $delete_job_task_query = $connect->prepare($delete_job_task_sql);
    $delete_job_task_query->bind_param('i', $task_identifier);
    $delete_job_task_query->execute();

    header("Location: ../../manage_tasks.php");
?>