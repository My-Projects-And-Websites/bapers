<?php
    include 'connection.php';

    date_default_timezone_set('Europe/London');

    $job_identifier = $_POST['job-identifier'];//GET JOB_ID FROM FRONT END
    $expected_finish = $_POST['expected-finish-job'];//GET DEADLINE FROM FRONT END

    $blank = null;

    if (!empty($expected_finish)) {// UPDATE THE DEADLINE OF FINISH INTO DATABASE
        $update_expected_time = $connect->prepare("UPDATE Job SET expected_finish = ? WHERE job_id = ?");
        $update_expected_time->bind_param('si', $expected_finish, $job_identifier);
        $update_expected_time->execute();
    }

    $task_status = $_POST['task-stati'];//GET THE TASK STATUS FROM FRONT END

    $find_spec_jobs = "SELECT * FROM Job_Task WHERE Jobjob_id = '$job_identifier'";
    $result_spec_jobs = $connect->query($find_spec_jobs);
    $count_spec_jobs = 0;

    $pending_count = 0;
    $completed_count = 0;

    $curr_time = date('H:i');
    $curr_date = date('Y-m-d');

    $update_task_task_date = $connect->prepare("UPDATE Job_Task SET task_date = ? WHERE JobTaskID = ?");//UPDATE THE TASK_DATE INTO DATABASE
    $update_task_start_time = $connect->prepare("UPDATE Job_Task SET start_time = ? WHERE JobTaskID = ?");//UPDATE THE START_TIME INTO DATABASE
    $update_task_finish_time = $connect->prepare("UPDATE Job_Task SET finish_time = ? WHERE JobTaskID = ?");//UPDATE THE FINISH_TIME INTO DATABASE

    while ($row_spec_jobs = mysqli_fetch_assoc($result_spec_jobs)) {
        $i = $task_status[$count_spec_jobs];
        //UPDATE JOB STATUS INTO DATABASE
        $update_job_task_status = $connect->prepare("UPDATE `job_task` SET task_status = ? WHERE JobTaskID = ?");
        $update_job_task_status->bind_param("si", $i, $row_spec_jobs['JobTaskID']);
        $update_job_task_status->execute();

        if ($i == "Pending") {//UPDATE PENDING STATUS OF JOB INTO DATABASE
            $pending_count++;

            $update_task_finish_time->bind_param("si", $blank, $row_spec_jobs['JobTaskID']);
            $update_task_finish_time->execute();
        }
        else if ($i == "Completed") {//UPDATE COMPLETE STATUS OF JOB INTO DATABASE
            $completed_count++;

            $update_task_finish_time->bind_param("si", $curr_time, $row_spec_jobs['JobTaskID']);
            $update_task_finish_time->execute();

            if (!isset($row_spec_jobs['task_date'])) {
                $update_task_task_date->bind_param("si", $curr_date, $row_spec_jobs['JobTaskID']);
                $update_task_task_date->execute();
            }

            if (!isset($row_spec_jobs['start_time'])) {
                $update_task_start_time->bind_param("si", $curr_time, $row_spec_jobs['JobTaskID']);
                $update_task_start_time->execute();
            }
        }
        else if ($i == "In Progress") {//UPDATE IN PROGRESS STATUS OF JOB INTO DATABASE
            $update_task_finish_time->bind_param("si", $blank, $row_spec_jobs['JobTaskID']);
            $update_task_finish_time->execute();
            
            if (!isset($row_spec_jobs['task_date'])) {
                $update_task_task_date->bind_param("si", $curr_date, $row_spec_jobs['JobTaskID']);
                $update_task_task_date->execute();
            }

            if (!isset($row_spec_jobs['start_time'])) {
                $update_task_start_time->bind_param("si", $curr_time, $row_spec_jobs['JobTaskID']);
                $update_task_start_time->execute();
            }
        }

        $count_spec_jobs++;
    }

    $status = "";
    $finish = date('Y-m-d H:i:s');
    //REMOVE JOB STATUS INTO DATABASE
    $update_job_status = $connect->prepare("UPDATE Job SET job_status = ? WHERE job_id = ?");
    $update_actual_finish = $connect->prepare("UPDATE Job SET actual_finish = ? WHERE job_id = ?");
    $remove_actual_finish = $connect->prepare("UPDATE Job SET actual_finish = ? WHERE job_id = ?");
    //REMOVE JOB STATUS OF PENDING INTO DATABASE
    if (mysqli_num_rows($result_spec_jobs) == $pending_count) {
        $status = "Pending";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $remove_actual_finish->bind_param("si", $blank, $job_identifier);
    }
    //REMOVE JOB STATUS OF COMPLETED INTO DATABASE
    else if (mysqli_num_rows($result_spec_jobs) == $completed_count) {
        $status = "Completed";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $update_actual_finish->bind_param("si", $finish, $job_identifier);
    }
    else {//REMOVE JOB STATUS OF IN PROGRESS INTO DATABASE
        $status = "In Progress";
        $update_job_status->bind_param("si", $status, $job_identifier);
        $remove_actual_finish->bind_param("si", $blank, $job_identifier);
    }

    $update_job_status->execute();
    $remove_actual_finish->execute();
    $update_actual_finish->execute();

    mysqli_close($connect);

    header("Location: ../process_job.php");
?>