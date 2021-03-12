<?php
session_start();//start the session
$image=imagecreatetruecolor(100,38);//make a scene
$bgcolor=imagecolorallocate($image,255,255,255);//back-ground colour
imagefill($image,0,0,$bgcolor);
 
 
$ccode='';//storage the code
 
// generate random letter+number
for($i=0;$i<4;$i++) {
    $fs = 10;       
    $fc = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));
    $code = 'abcdefghijklmnopqrstuvwxyz1234567890';   //select code includes
    $fct = substr($code, rand(0, strlen($code)), 1);
    $ccode.=$fct;
    $x = ($i * 100 / 4) + rand(5, 10);
    $y = rand(5, 10);
    imagestring($image, $fs, $x, $y, $fct, $fc);
}
$_SESSION['code']=$ccode;
 
// generate the points on code image
for($i=0;$i<200;$i++){
    $pointcolor=imagecolorallocate($image,rand(50,200),rand(50,200),rand(50,200));
    imagesetpixel($image,rand(1,99),rand(1,29),$pointcolor);//
}
 
// generate the lines on code image
for($i=0;$i<3;$i++){
    $linecolor=imagecolorallocate($image,rand(80,280),rand(80,220),rand(80,220));
    imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linecolor);
}
 
// output
header('content-type:image.png');
imagepng($image);
 
// destroy the code image
imagedestroy($image);

//code-study sorce from:https://hotexamples.com/examples/-/-/imagestring/php-imagestring-function-examples.html