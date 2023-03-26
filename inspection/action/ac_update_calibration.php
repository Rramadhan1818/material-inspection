<?php
require "../conf/dbkoneksi.php";

try {
    $value_update = $_POST['value_update'];
    $recid = $_POST['recid'];
    $sql_update_ac = "UPDATE [$databaseName].[dbo].[calibration_lines]
    SET [Value] = '$value_update' 
    WHERE RecID = '$recid'";
    
    $stmt_update_ac = sqlsrv_query($conn, $sql_update_ac);

    if($stmt_update_ac){
        echo $msg = "Data Berhasil di Input";
    }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}