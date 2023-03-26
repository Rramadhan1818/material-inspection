<?php
require "../conf/dbkoneksi.php";

try {
	$trans_code = date('YmdHis');
	$TransID = $trans_code;
	$WorkingDate = date('Y-m-d H:i');
	$ShiftName = $_POST['ShiftName'];
	$CK_Operator = $_POST['CK_Operator'];
	$npk_op = $_POST['Operator'];
	$npk_led = $_POST['Leader'];
	$npk_fore = $_POST['Foreman'];
	
	$sql = "INSERT INTO [$databaseName].[dbo].[inspection_trans]
		(
		TransID
		,WorkingDate
		,ShiftName
		,LinesCode
		,StartTime
		,OperatorID
		,LeaderID
		,ForemanID
		,Status
		,AssetID
		,CK_Operator
		)
	VALUES
		('$TransID',
		'$WorkingDate',
		'$ShiftName',
		'INS001',
		getdate(),
		'$npk_op',
		'$npk_led',
		'$npk_fore',
		'Proses',
		'$HostName',
		'$CK_Operator')";
	// var_dump($sql);die();
	$stmt_post_trans = sqlsrv_query($conn, $sql);

	$sql_update = "UPDATE [$databaseName].[dbo].[mt_state]
		SET 
		TransID = '$TransID',
		WorkingDate = '$WorkingDate',
		Status = 'Proses',
		Session = 'ACTIVE',
		OperatorID = '$npk_op',
		LeaderID = '$npk_led',
		ForemanID = '$npk_fore',
		ShiftName = '$ShiftName',
		RecordTime = getdate(),
		SessionID = '$TransID',
		CK_Operator = '$CK_Operator'
		WHERE IP = '$HostName' AND Status = 'Start' AND ProcessCode = 'QA'";
	$stmt_update_state = sqlsrv_query($conn, $sql_update);
	
	$get_state = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
    $stmt_state = sqlsrv_query($conn, $get_state);
    
	$state = [];
	while($row_get_state = sqlsrv_fetch_array($stmt_state, SQLSRV_FETCH_ASSOC))	
    {
		$state[] = $row_get_state;
    }

    if($sql_update){
		$msg = "Data Berhasil di Input";
		echo json_encode(['msg' => $msg, 'state' => $state]);
    }else{
		$msg = "Data Gagal di Input";
        echo json_encode(['msg' => $msg]);;
    }
}
catch(Exception $e) {
    echo $msg = "Data Gagal di Input!". $e;
}

?>