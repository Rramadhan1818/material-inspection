<?php

require "conf/dbkoneksi.php";
$dateSearch =  date('Y-m-d');

$session = date('YmdHis');

//CEK SESSI
$sql_cek = "SELECT count(*) as Ada FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' AND Session = 'ACTIVE'";
$stmt_cek = sqlsrv_query($conn, $sql_cek);
$row_cek = sqlsrv_fetch_array($stmt_cek, SQLSRV_FETCH_ASSOC);
$ada = $row_cek['Ada'];	

//REDIRECT
if($ada > 0) {
	
	$sql = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE Session = 'ACTIVE' AND IP = '$HostName' ORDER BY RecordTime DESC";
	$stmt = sqlsrv_query($conn, $sql);
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	{
		$statusSearch = $row['Status'];	
	}
	header('Location: ' . $statusSearch);
	
}else{

	$sql_update = sqlsrv_query($conn, "UPDATE [$databaseName].[dbo].[mt_state] SET [WorkingDate] = '$dateSearch'
		,[Session] = 'PASSIVE'
		,[Status] = 'Start'
		,[ProcessCode] = 'QC'
		,[SessionID] = '$sesss'
	WHERE IP = '$HostName'");
		
	header('Location: start');
	
}
?>

