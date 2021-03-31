<?php
    include "connection.php";

    $view_jobs_sql = "SELECT * FROM Job";
    $view_jobs_query = $connect->prepare($view_jobs_sql);
    $view_jobs_query->execute();
    $view_jobs_result = $view_jobs_query->get_result();

    $isLate = 1;
    while ($view_jobs_row = $view_jobs_result->fetch_assoc()) {
        $job_id = $view_jobs_row['job_id'];
        $view_paym_sql = "SELECT * FROM Payment WHERE payment_id = ?";
        $view_paym_query = $connect->prepare($view_paym_sql);
        $view_paym_query->bind_param('i', $job_id);
        $view_paym_query->execute();
        $view_paym_result = $view_paym_query->get_result();

        $date_interval = strtotime(date("Y-m-d H:i:s")) - strtotime($view_jobs_row['actual_finish']); 

        while ($view_paym_row = $view_paym_result->fetch_assoc()) {

            if ($view_paym_row['payment_status'] != "Paid" && $date_interval > 86400) {
                $late_paym_sql = "UPDATE Payment SET payment_late = ?, payment_status = 'Late' WHERE payment_id = ?";
                $late_paym_query = $connect->prepare($late_paym_sql);
                $late_paym_query->bind_param("ii", $isLate, $job_id);
                $late_paym_query->execute();
            }
        }
    }
?>