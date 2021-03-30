<?php  
$db_host='localhost';
$db_user='root';
$db_pass='root';
$db_name='test2';

// set the format of the backup file name.
$filename=date("m-d-Y_H-i-s")."-".$db_name.".sql";  
// create the folder of the db backup if not exits.
$db_backup = '../db/database_backup';
if (!is_dir($db_backup))
	mkdir($db_backup, 0755, TRUE);

//back up temp folder
$saveFile = $db_backup.'/'.$filename;
// SAVE IT AS FILENAME FORMAT 
header("Content-disposition:filename=".$filename);  
header("Content-type:application/octetstream");  
header("Pragma:no-cache");  
header("Expires:0");  

// using mysql dump to output the db info.
$mysqldump_dir='localhost/www/server/mysql/bin';//change this dir to mysql/bin folder.
exec("D:\phpstudy_pro\Extensions\MySQL5.7.26\bin\mysqldump -h$db_host -u$db_user -p$db_pass --default-character-set=utf8 $db_name > ".$saveFile); //*please Note, if want the exec run successfully, need to check 'disable_functions' in php.ini
$file = fopen($saveFile, "r"); //open the file.
fclose($file);

?>  