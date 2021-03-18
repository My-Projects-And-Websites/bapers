<?php
    include 'connection.php';

    $jobIdentifier = $_POST['job-identifier'];
    $expectedFinish = $_POST['expected-finish-job'];

    if (!empty($expectedFinish)) {
        $updateExpectedTime = $connect->prepare("UPDATE Job SET expected_finish = ? WHERE job_id = ?");
        $updateExpectedTime->bind_param('si', $expectedFinish, $jobIdentifier);
        $updateExpectedTime->execute();
    }

    $taskStatus = $_POST['task-stati'];

    $find_spec_jobs = "SELECT * FROM Job_Task WHERE Jobjob_id = '$jobIdentifier'";
    $result_spec_jobs = $connect->query($find_spec_jobs);
    $count_spec_jobs = 0;

    while ($row_spec_jobs = mysqli_fetch_assoc($result_spec_jobs)) {
        $i = $taskStatus[$count_spec_jobs];

        $updateJobTaskStatus = $connect->prepare("UPDATE `job_task` SET task_status = ? WHERE JobTaskID = ?");
        $updateJobTaskStatus->bind_param("si", $i, $row_spec_jobs['JobTaskID']);
        $updateJobTaskStatus->execute();

        $count_spec_jobs++;
    }

    mysqli_close($connect);

    header("Location: ../process_job.php");
?>