<?php

$url = $_SERVER['REQUEST_URI'];
require "conf/dbkoneksi.php";


$cari = "SELECT COUNT(*) Ada FROM [$databaseName].[dbo].[mt_state] WHERE Session = 'ACTIVE' AND IP = '$HostName'";

$stmt_cari = sqlsrv_query($conn, $cari);
$row_ = sqlsrv_fetch_array($stmt_cari, SQLSRV_FETCH_ASSOC);
$ada = $row_['Ada'];

$statusSearch = '';
$sql = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE Session = 'ACTIVE' AND IP = '$HostName' ORDER BY RecordTime DESC";
$stmt = sqlsrv_query($conn, $sql);
while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
{
	$statusSearch = $row['Status'];	
}

if($ada > 0) {

	header('Location: ' . $statusSearch);
	
	// if($statusSearch = 'Start'){
	// 	echo '<meta http-equiv="refresh" content="0"; url="start">';
	// 	header('Location: start');
	// }else if($statusSearch = 'Proses'){
	// 	echo '<meta http-equiv="refresh" content="0"; url="proses">';
	// 	header('Location: proses');
	// }else{
	// 	echo '<meta http-equiv="refresh" content="0"; url="start">';
	// 	header('Location: start');
	// }
	// if($url != '/quality/kalibrasi/'.$statusSearch.'')
	// {
	// 	die("<script>location.href = '".$statusSearch."'</script>");
	// 	echo '<meta http-equiv="refresh" content="0; url='.$statusSearch.'">';
	// }
	// header('Location: ' . $statusSearch);
	
}else{
	
	header('Location: ' . $statusSearch);
	
}
?>