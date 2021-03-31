<?php
    include "connection.php";

    $tables = array();
    $result = mysqli_query($connect,"SHOW TABLES");

    $tables_sql = "SHOW TABLES";
    $tables_query = $connect->prepare($tables_sql);
    $tables_query->execute();
    $tables_result = $tables_query->get_result();

    while($tables_row = $tables_result->fetch_assoc()) {
        $tables[] = $tables_row['Tables_in_' . $db_name];
    }

    $write = '';
    foreach ($tables as $table) {
        $get_data = mysqli_query($connect,"SELECT * FROM ".$table);
        $num_fields = mysqli_num_fields($get_data);
        
        $write .= 'DROP TABLE '.$table.';';
        $get_table = mysqli_fetch_row(mysqli_query($connect, "SHOW CREATE TABLE ".$table));
        $write .= "\n\n" . $get_table[1] . ";\n\n";
        
        for($i = 0; $i < $num_fields; $i++) {

            while($row = mysqli_fetch_row($get_data)) {
                $write .= "INSERT INTO ".$table." VALUES(";

                for ($j=0;$j<$num_fields;$j++) {
                    $row[$j] = addslashes($row[$j]);

                    if (isset($row[$j])) { 
                        $write .= '"'.$row[$j].'"';
                    }
                    else { 
                        $write .= '""';
                    }

                    if ($j<$num_fields-1) { 
                        $write .= ',';
                    }
                }
                
                $write .= ");\n";
            }
        }

        $write .= "\n\n\n";
    }

    //save file
    $handle = fopen("../db/bapers.sql","w+");
    fwrite($handle,$write);
    fclose($handle);
    
    echo "<script>
    alert('Database successfully exported!');
    window.location.href = '../dashboard.php';
    </script>";
?>
