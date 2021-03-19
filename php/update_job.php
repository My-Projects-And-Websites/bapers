<?php
    include 'connection.php';

    $job_identifier = $_POST['job-identifier'];
    $expected_finish = $_POST['expected-finish-job'];

    if (!empty($expected_finish)) {
        $update_expected_time = $connect->prepare("UPDATE Job SET expected_finish = ? WHERE job_id = ?");
        $update_expected_time->bind_param('si', $expected_finish, $job_identifier);
        $update_expected_time->execute();
    }

    $task_status = $_POST['task-stati'];

    $find_spec_jobs = "SELECT * FROM Job_Task WHERE Jobjob_id = '$job_identifier'";
    $result_spec_jobs = $connect->query($find_spec_jobs);
    $count_spec_jobs = 0;

    $pending_count = 0;
    $completed_count = 0;
    $progress_count = 0;

    while ($row_spec_jobs = mysqli_fetch_assoc($result_spec_jobs)) {
        $i = $task_status[$count_spec_jobs];

        $update_job_task_status = $connect->prepare("UPDATE `job_task` SET task_status = ? WHERE JobTaskID = ?");
        $update_job_task_status->bind_param("si", $i, $row_spec_jobs['JobTaskID']);
        $update_job_task_status->execute();

        if ($i == "Pending") {
            $pending_count++;
        }
        else if ($i == "Completed") {
            $completed_count++;
        }
        else {
            $progress_count++;
        }

        $count_spec_jobs++;
    }

    $status = "";
    $blank = null;

    date_default_timezone_set('Europe/London');
    $finish = date('Y-m-d H:i:s');

    $update_job_status = $connect->prepare("UPDATE Job SET job_status = ? WHERE job_id = ?");
    $update_actual_finish = $connect->prepare("UPDATE Job SET actual_finish = ? WHERE job_id = ?");
    $remove_actual_finish = $connect->prepare("UPDATE Job SET actual_finish = ? WHERE job_id = ?");

    if (mysqli_num_rows($result_spec_jobs) == $pending_count) {
        $status = "Pending";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $update_job_status->execute();

        $remove_actual_finish->bind_param("si", $blank, $job_identifier);
        $remove_actual_finish->execute();
    }
    else if (mysqli_num_rows($result_spec_jobs) == $completed_count) {
        $status = "Completed";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $update_job_status->execute();

        $update_actual_finish->bind_param("si", $finish, $job_identifier);
        $update_actual_finish->execute();
    }
    else {
        $status = "In Progress";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $update_job_status->execute();

        $remove_actual_finish->bind_param("si", $blank, $job_identifier);
        $remove_actual_finish->execute();
    }

    mysqli_close($connect);

    header("Location: ../process_job.php");
?>