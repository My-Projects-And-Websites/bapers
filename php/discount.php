<?php
//Update the status of the discount plan in the database.
function discount_plan($customer_id,$discount_plan){
    include('connection.php');
    $sql = "SELECT cust_type from customer";
    $get_value = $connect->query($sql);
    $value = mysqli_fetch_row($get_value);
    if ($discount_plan = 'Fixed discount' && $value['cust_type'] = 1){
    $update_plan = "UPDATE customer SET discount_plan = '$discount_plan' where cust_id = '$customer_id'";
    $connect->query($update_plan);
    }elseif($discount_plan = 'Variable discount' && $value['cust_type'] = 1){
        $update_plan = "UPDATE customer SET discount_plan = '$discount_plan' where cust_id = '$customer_id'";
        $connect->query($update_plan);
    }elseif($discount_plan = 'Flexible discount' && $value['cust_type'] = 1){
        $update_plan = "UPDATE customer SET discount_plan = '$discount_plan' where cust_id = '$customer_id'";
        $connect->query($update_plan);
    }
    else{
        echo '<script language="JavaScript">;alert("You cannot set discount for non-valued customer!");</script>;';
    }
}


//recaulating the price.// function only use when caculating the total price
//need to input $discount_percentage. //DATABASE NEED ADD "payment_status" (tiny int),"payment_discount"(double),"discount_rate"(int) into payment.
//When do the Flexible discount, can call the function with discount($customer_id,0)
function discount($customer_id,$discount_percentage){
    $pay_separate = 0;
    // NEED TO SELECT FROM FRONT PAGE THAT VALUED CUSTOMER PAY SINGLE TASK OR NOT. IF PAY SAPERATE THE DISCOUNT NOT WILL APPLY TO.
    //1 MEANS PAY SINGLE TASK ; 0 MEANS PAY ALL IN
    include('connection.php');
    $plan_status_check="SELECT cust_type,discount_plan FROM customer where cust_id = '$customer_id'";//get from front-end;
    
    $result = $connect->query($plan_status_check);
    $row =  mysqli_fetch_row($result);
    if ($row['cust_type'] == 1 && $row['discount_plan'] != null) {

        if ($row['cust_type'] = "Fixed" && $pay_separate = 0) { //Fixed discount calculation 
            $get_tasks_price = "SELECT payment_total FROM payment where Customercust_id = '$customer_id'";
            $result2 = $connect->query($get_tasks_price);
            $total_price = mysqli_fetch_row($result2);
            $discount_price = $total_price * ($discount_percentage/100);
            $update_payment = "UPDATE payment set payment_discount = '$discount_price',discount_rate = '$discount_percentage' 
            WHERE Customercust_id = $customer_id and payment_status = 0";
            $connect->query($update_payment);
        }
        else if ($row['cust_type'] = "Variable" && $pay_separate = 0) { //Variable discount calculation

            //Need to show out the current task list that assigned to customer.
            //and then assigned new discount price into task.(I create an new variable into table task call 'task_discount')

            $task_Q="SELECT task_id from task INNER join job_task on task.task_id = job_task.JobTaskID INNER join job ON 
            job_task.Jobjob_id = job.job_id AND job.Customercust_id = $customer_id";
            $sql=$connect->query($task_Q);
            $task_row = mysqli_fetch_all($sql,MYSQLI_ASSOC);
            //input the discount rate from the front-part and put it into an array.
            $discount_array;
            for($i=0;$i<=sizeof($task_row);$i++){
                $discount_rate = $discount_percentage/100;
                $set_discount="UPDATE task set task_discount = task_price * '$discount_rate' where task_id = '$task_row[$i]'";
                $connect->query($set_discount);
            }
        }
        else if ($row['cust_type'] = "Flexible" && $pay_separate = 0) { //Flexible discount(on volume per month)
            /* discount table */
            /* <=£1000 = 5% 
                1000<= price <=2000 = 10%
                >2000 = 15%           */
            $total_price_Q = "SELECT payment_total FROM payment where Customercust_id = $customer_id and payment_status = 0";
            $get_total_price2 = $connect->query($total_price_Q);
            $total_price2 = mysqli_fetch_row($get_total_price2);
            if ($total_price2 <= 1000) {
                $discount_percentage = 5;
                $discount_rate = $discount_percentage / 100;
                $discount_price2 = $total_price2 * $discount_rate;
            }
            else if ($total_price2 <= 2000 and $total_price2 > 1000) {
                $discount_percentage = 10;
                $discount_rate = $discount_percentage / 100;
                $discount_price2 = $total_price2 * $discount_rate;
            }
            else if ($total_price2 > 2000) {
                $discount_percentage = 15;
                $discount_rate = $discount_percentage / 100;
                $discount_price2 = $total_price2 * $discount_rate;
            }

            $update_payment = "UPDATE payment set payment_discount = '$discount_price2',discount_rate = '$discount_percentage' 
            WHERE Customercust_id = $customer_id and payment_status = 0";
            $connect->query($update_payment);

        }
    }
}
?>