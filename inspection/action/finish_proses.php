<?php
require "../conf/dbkoneksi.php";

try {
        $TransID = $_POST['transid'];
        $ShiftName = $_POST['shiftName'];
        $WorkingDate = $_POST['workdate_get'];

        $npk_op = $_POST['operatorID'];
        $npk_led = $_POST['leaderID'];
        $npk_fore = $_POST['foremanID'];
        
        $sql_update_trans = "UPDATE [$databaseName].[dbo].[inspection_trans]
            SET
            FinishTime = getdate(),
            Status = 'Finish'
            WHERE TransID = '$TransID' ";

        $stmt_update_trans = sqlsrv_query($conn, $sql_update_trans);
        
        $sql_finish_up = "UPDATE [$databaseName].[dbo].[mt_state]
            SET 
            TransID = '$TransID',
            WorkingDate = '$WorkingDate',
            ShiftName = '$ShiftName',
            Status = 'Start',
            Session = 'PASSIVE',
            OperatorID = '$npk_op',
            LeaderID = '$npk_led',
            ForemanID = '$npk_fore',
            RecordTime = getdate()
            WHERE Status = 'Proses' AND IP = '$HostName'";

        $stmt_state = sqlsrv_query($conn, $sql_finish_up);

        if($stmt_state){
            echo $msg = "Data Berhasil di Input, Proses Selesai";
        }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}