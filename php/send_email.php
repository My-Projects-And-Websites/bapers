<?php
use PHPMailer\PHPMailer\PHPMailer; //initial the "PHPMAILER" plugin
function staff_reg_email($email_add,$name,$login_email,$login_pass,$department,$role){ //send email for staff registeration
    require('phpmailer/Exception.php');
    require('phpmailer/PHPMailer.php');
    require('phpmailer/SMTP.php');
    
    $email = new PHPMailer();
    $email->SMTPDebug = 1;
    $email->isSMTP();
    $email->SMTPAuth = true;
    $email->Host = 'smtpout.secureserver.net';//SMTP SERVER ADD
    if ($email-> port == 587){
        $email->SMTPSecure = 'TLS';
    }
    $email->Charset = 'UTF-8';
    $email->FromName = 'BAPERS Register System';
    $email->Username = 'admin@bapers.co.uk';
    $email->Password = 'GROUP20IN2018';
    $email->From = 'register@bapers.co.uk';
    $email->isHTML(true);//SET HTML EMAIL 
    
    $email->addAddress($email_add);
    $email->Subject = "Welcome to BAPERS [DO NOT REPLY]";
    $template = '../email_templates/staff_template_email.php';
    $email_send = '../email_templates/staff_email.php';
    file_put_contents($email_send,file_get_contents($template));
    file_put_contents($email_send,str_replace('{{login_email}}',$login_email,file_get_contents($email_send)));//show login_email
    file_put_contents($email_send,str_replace('{{Password}}',$login_pass,file_get_contents($email_send)));//show login_password
    file_put_contents($email_send,str_replace('{{department}}',$department,file_get_contents($email_send)));//show department
    file_put_contents($email_send,str_replace('{{role}}',$role,file_get_contents($email_send)));//show role
    file_put_contents($email_send,str_replace('{{name}}',$name,file_get_contents($email_send)));//show name
    $body = file_get_contents($email_send);
    $email->Body = $body;
    $email->send();
    unlink($email_send);
}
function job_deadline_email($email_add,$job_id,$job_deadline,$job_SI,$customer_id,$oder_time,$last_status){// SEND EMAIL ALERT THE STAFF OF JOB DEADLINE
    require('phpmailer/Exception.php');
    require('phpmailer/PHPMailer.php');
    require('phpmailer/SMTP.php');
    
    $email = new PHPMailer();
    $email->SMTPDebug = 1;
    $email->isSMTP();
    $email->SMTPAuth = true;
    $email->Host = 'smtpout.secureserver.net';
    if ($email-> port == 587){
        $email->SMTPSecure = 'TLS';
    }
    $email->Charset = 'UTF-8';
    $email->FromName = 'BAPERS Alert System';
    $email->Username = 'admin@bapers.co.uk';
    $email->Password = 'GROUP20IN2018';
    $email->From = 'alert@bapers.co.uk';
    $email->isHTML(true);
    foreach ($email_add as $add_send) {
        $email->AddAddress($add_send);
    }
    $email->Subject = "Job Status Alert! [DO NOT REPLY]";
    $template = '../email_templates/job_deadline_template.php';
    $email_send = '../email_templates/job_deadline_email.php';
    file_put_contents($email_send,file_get_contents($template));
    file_put_contents($email_send,str_replace('{{Job_id}}',$job_id,file_get_contents($email_send)));//show job_id
    file_put_contents($email_send,str_replace('{{Job_deadline}}',$job_deadline,file_get_contents($email_send)));//show job_deadline
    file_put_contents($email_send,str_replace('{{Job_SI}}',$job_SI,file_get_contents($email_send)));//show special instruction
    file_put_contents($email_send,str_replace('{{customer_id}}',$customer_id,file_get_contents($email_send)));//show customer_id
    file_put_contents($email_send,str_replace('{{order_time}}',$oder_time,file_get_contents($email_send)));//show order_time
    file_put_contents($email_send,str_replace('{{last_status}}',$last_status,file_get_contents($email_send)));//show lastest_status
    $body = file_get_contents($email_send);
    $email->Body = $body;
    $email->send();
    unlink($email_send);
}
function late_payment_email($email_add,$payment_id,$cust_id,$cust_name,$cust_email,$cust_no,$cust_add,$payment_due,$due_time){// SEND EMAIL ALERT THE STAFF THAT HAS LATEPAYMENT
    require('phpmailer/Exception.php');
    require('phpmailer/PHPMailer.php');
    require('phpmailer/SMTP.php');
    
    $email = new PHPMailer();
    $email->SMTPDebug = 1;
    $email->isSMTP();
    $email->SMTPAuth = true;
    $email->Host = 'smtpout.secureserver.net';
    if ($email-> port == 587){
        $email->SMTPSecure = 'TLS';
    }
    $email->Charset = 'UTF-8';
    $email->FromName = 'BAPERS Alert System';
    $email->Username = 'admin@bapers.co.uk';
    $email->Password = 'GROUP20IN2018';
    $email->From = 'alert@bapers.co.uk';
    $email->isHTML(true);
    
    foreach ($email_add as $add_send) {
        $email->AddAddress($add_send);
    }
    $email->Subject = "Late Payment Alert! [DO NOT REPLY]";
    $template = '../email_templates/late_payment_template.php';
    $email_send = '../email_templates/late_payment_email.php';
    file_put_contents($email_send,file_get_contents($template));
    file_put_contents($email_send,str_replace('{{Payment_id}}',$payment_id,file_get_contents($email_send)));//show payment_id
    file_put_contents($email_send,str_replace('{{customer_id}}',$cust_id,file_get_contents($email_send)));//show customer ID
    file_put_contents($email_send,str_replace('{{cutomer_name}}',$cust_name,file_get_contents($email_send)));//show customer name
    file_put_contents($email_send,str_replace('{{customer_email}}',$cust_email,file_get_contents($email_send)));//show customer email
    file_put_contents($email_send,str_replace('{{customer_number}}',$cust_no,file_get_contents($email_send)));//show customer number
    file_put_contents($email_send,str_replace('{{customer_add}}',$cust_add,file_get_contents($email_send)));//show customer address
    file_put_contents($email_send,str_replace('{{total_due}}',$payment_due,file_get_contents($email_send)));//show total due amount
    file_put_contents($email_send,str_replace('{{due_time}}',$due_time,file_get_contents($email_send)));//show due time
    $body = file_get_contents($email_send);
    $email->Body = $body;
    $email->send();
    unlink($email_send);
}
?>