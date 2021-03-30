<?php   
$db_host='localhost';
$db_user='root';
$db_pass='root';
$db_name='test';// change the name of table here
$db_file_name = '../db/database_backup/03-30-2021_22-40-06-test2.sql';

// using mysql dump to output the db info.
$mysqldump_dir='localhost/www/server/mysql/bin';//change this dir to mysql/bin folder.
exec("D:\phpstudy_pro\Extensions\MySQL5.7.26\bin\mysql -u".$db_user." -p".$db_pass." ".$db_name." < ".$db_file_name); //*please Note, if want the exec run successfully, need to check 'disable_functions' in php.ini


?>  
  