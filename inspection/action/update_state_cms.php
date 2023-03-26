
<?php
require "../conf/dbkoneksi.php";
try {

$linename =   $_POST['id'];
$sql_update = "UPDATE [$databaseName].[dbo].[mt_state]
		SET 
		LineName = '$linename'
		WHERE IP = '$HostName' AND Status = 'Proses' AND ProcessCode = 'QA'";

	$stmt_update_state = sqlsrv_query($conn, $sql_update);

    if($stmt_update_state){
		$msg = "Data Berhasil di Input";
		echo json_encode(['msg' => $msg]);
    }else{
		$msg = "Data Gagal di Input";
        echo json_encode(['msg' => $msg]);;
    }
}
catch(Exception $e) {
    echo $msg = "Data Gagal di Input!". $e;
}