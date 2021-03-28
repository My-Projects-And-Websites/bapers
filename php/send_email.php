<?php
use PHPMailer\PHPMailer\PHPMailer;
function staff_reg_email($email_add,$name,$login_email,$login_pass,$department,$role){
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
    $email->FromName = 'BAPERS Register System';
    $email->Username = 'admin@bapers.co.uk';
    $email->Password = 'IN2018Group20';
    $email->From = 'register@bapers.co.uk';
    $email->isHTML(true);
    
    $email->addAddress($email_add);
    $email->Subject = "Welcome to BAPERS";
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

function job_deadline_email($email_add,$job_id,$job_deadline,$job_SI,$customer_id,$oder_time,$last_status){
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
    $email->Password = 'IN2018Group20';
    $email->From = 'alert@bapers.co.uk';
    $email->isHTML(true);
    
    $email->addAddress($email_add);
    $email->Subject = "Job Status Warning!";
    $template = '../email_templates/late_payment_template.php';
    $email_send = '../email_templates/late_payment_email.php';
    file_put_contents($email_send,file_get_contents($template));
    file_put_contents($email_send,str_replace('{{Job_id}}',$job_id,file_get_contents($email_send)));//show login_email
    file_put_contents($email_send,str_replace('{{Job_deadline}}',$job_deadline,file_get_contents($email_send)));//show login_password
    file_put_contents($email_send,str_replace('{{Job_SI}}',$job_SI,file_get_contents($email_send)));//show department
    file_put_contents($email_send,str_replace('{{customer_id}}',$customer_id,file_get_contents($email_send)));//show role
    file_put_contents($email_send,str_replace('{{order_time}}',$oder_time,file_get_contents($email_send)));//show name
    file_put_contents($email_send,str_replace('{{last_status}}',$last_status,file_get_contents($email_send)));//show name
    $body = file_get_contents($email_send);
    $email->Body = $body;
    $email->send();
    unlink($email_send);
}
?>