<?php
/*-------------------------------------DISCOUNT PART ---------------------------------------------------*/

//recaulating the price.// function only use when caculating the total price
//need to input $discount_percentage. //DATABASE NEED ADD "payment_status" (tiny int),"payment_discount"(double),"discount_rate"(int) into payment.
//When do the Flexible discount, can call the function with discount($customer_id,0)
function discount($discount_plan,$payment_id,$discount_percentage){
    // NEED TO SELECT FROM FRONT PAGE THAT VALUED CUSTOMER PAY SINGLE TASK OR NOT. IF PAY SAPERATE THE DISCOUNT NOT WILL APPLY TO.
    //1 MEANS PAY SINGLE TASK ; 0 MEANS PAY ALL IN
    include('connection.php');
    if ($discount_plan != null){
        if($discount_plan == "Fixed"){//Fixed discount calculation 
            $get_job_price = "SELECT total_price FROM job where job_id = '$payment_id'";
            $result2 = $connect->query($get_job_price);
            $total_price = mysqli_fetch_row($result2);
            $discount_price = ($total_price[0]*(($discount_percentage)/100));
            $discount_price = -$discount_price;
            $update_discount_price = "UPDATE job set discount_amount = '$discount_price' where job_id = '$payment_id'";
            $update_payment="UPDATE payment set discount_rate = '$discount_percentage',payment_discount ='$discount_price' where payment_id = '$payment_id'";
            $connect->query($update_discount_price);
            $connect->query($update_payment);
        }
            elseif($discount_plan == "Flexible"){ //Flexible discount(on volume per month)
            /* discount table */
            /* <=£1000 = 0% 
                1000<= price <=2000 = 1%
                >2000 = 2%     */
            if($discount_percentage != null){
                $discount_percentage=0;
            }else{
                $discount_percentage=0;
            }
            $total_price_Q = "SELECT total_price FROM job where job_id = '$payment_id'";
            $get_total_price2 = $connect->query($total_price_Q);
            $total_price2 = mysqli_fetch_row($get_total_price2);
            if($total_price2[0] <= 2000 and $total_price2[0]>1000 ){
                $discount_percent= 1;
                $discount_rate = $discount_percent/100;
                $discount_price2 = - ($total_price2[0] * $discount_rate);
            }elseif($total_price2[0] > 2000 ){
                $discount_percent = 2;
                $discount_rate = $discount_percent/100;
                $discount_price2 = -($total_price2[0] * $discount_rate);
            }else{
                $discount_price2 = 0;
            }
            $discount_price2 = -$discount_price2;
            $update_discount_price = "UPDATE job set discount_amount = '$discount_price2' where job_id = '$payment_id'";
            $update_payment="UPDATE payment set discount_rate = '$discount_percentage',payment_discount ='$discount_price2' where payment_id = '$payment_id'";
            $connect->query($update_discount_price);
            $connect->query($update_payment);
        
        }
    }
}


    function Variable_discount($payment_id,$discount_pA){
        include('connection.php');
        $task_Q="SELECT task_price from task INNER JOIN job_task ON job_task.JobTaskID=task.task_id WHERE job_task.Jobjob_id = '$payment_id'";// get tasks in the job
        $sql=$connect->query($task_Q);
        $task_row = mysqli_fetch_all($sql,MYSQLI_ASSOC);
        $discount_price = 0;
        //input the discount rate from the front-part and put it into an array.
        for($i=0;$i<=sizeof($task_row);$i++){
            $discount_rate = $discount_pA[$i]/100;
            if($discount_percentage[$i] = null || $discount_pA[$i] = 0){//assigned 0% 
                $discount_rate = 0; 
            }
            $total_rate += $discount_pA[$i];
            $discount_price= $discount_price + $task_row[$i]['task_price'] * ($discount_rate);
         }
            $discount_price = -$discount_price;
            $update_discount_price = "UPDATE job set discount_amount = '$discount_price' where job_id = '$payment_id'";
            $update_payment="UPDATE payment set discount_rate = '$total_rate',payment_discount ='$discount_price' where payment_id = '$payment_id'";
            $connect->query($update_discount_price);
            $connect->query($update_payment);

    }
?>
