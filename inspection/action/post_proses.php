<?php
require "../conf/dbkoneksi.php";

try {
    // var_dump($_POST);die();
    
    $shiftName = $_POST['shiftName'];
    $workdate_get = $_POST['workdate_get'];
    $transid = $_POST['transid'];

    $SessionID = isset($_POST['form'][0]['value']) ? $_POST['form'][0]['value'] : NULL;
    $FurnanceNumber =isset($_POST['form'][1]['value']) ? $_POST['form'][1]['value'] : NULL;
    $PartName = isset($_POST['form'][2]['value']) ? $_POST['form'][2]['value'] : NULL;
    $ProductCode = isset($_POST['form'][3]['value']) ? $_POST['form'][3]['value'] : NULL;
    $CustomerName = isset($_POST['form'][4]['value']) ? $_POST['form'][4]['value'] : NULL;
    $CustomerCode = isset($_POST['form'][5]['value']) ? $_POST['form'][5]['value'] : NULL;
    $DateCode = isset($_POST['form'][6]['value']) ? $_POST['form'][6]['value'] : NULL;
    $CavityNo = isset($_POST['form'][7]['value']) ? $_POST['form'][7]['value'] : NULL;

    // var_dump($_POST);die();
    $Nodularity = isset($_POST['Hardness']['Nodularity']) ? $_POST['Hardness']['Nodularity'] : 0;
    $Graphite_Type = isset($_POST['Hardness']['Graphite_Type']) ? $_POST['Hardness']['Graphite_Type'] : NULL;
    $MatrixStructure = isset($_POST['Hardness']['MatrixStructure']) ? $_POST['Hardness']['MatrixStructure'] : 0;
    $Pearlite = isset($_POST['Hardness']['Pearlite']) ? $_POST['Hardness']['Pearlite'] : 0;
    $Ferlite = isset($_POST['Hardness']['Ferlite']) ? $_POST['Hardness']['Ferlite'] : 0;
    
    // GET TRANS
    $sql_trans = "SELECT TOP 1 *
    FROM [$databaseName].[dbo].[inspection_trans] TR
    WHERE TR.TransID = '$transid'";
    $stmt_trans = sqlsrv_query($conn, $sql_trans);
    $row_trans = sqlsrv_fetch_array($stmt_trans, SQLSRV_FETCH_ASSOC);
    $OperatorID = $row_trans['OperatorID']; 

    $sql_proses = "INSERT INTO [$databaseName].[dbo].[inspection_lines]
            (
            TransID
            ,SessionID
            ,ProductCode
            ,PartName
            ,WorkingDate
            ,ShiftName
            ,CustomerCode
            ,CustomerName
            ,DateCode
            ,CavityNo
            ,FurnanceNumber
            ,RecordTime
            ,Created_at
            ,PreparedBy
            ,PreparedDate
            ,PreparedStatus
            )
        VALUES(
                    '$transid',
                    '$SessionID',
                    '$ProductCode',
                    '$PartName',
                    '$workdate_get',
                    '$shiftName',
                    '$CustomerCode',
                    '$CustomerName',
                    '$DateCode',
                    '$CavityNo',
                    '$FurnanceNumber',
                    getdate(),
                    getdate(), 
                    '$OperatorID', 
                    getdate(), 
                    '3' 
                    )";

        $sql_hardness = "INSERT INTO [$databaseName].[dbo].[inspection_lines_hardness]
            (
            TransID
            ,ProductCode
            ,HardnessValue
            ,TrialTimes
            ,Nodularity
            ,Graphite_Type
            ,Pearlite
            ,Ferlite
            ,MatrixStructure
            ,WorkingDate
            ,Created_at
            )
        VALUES";
        $eventCount = count($_POST['form_hardness']);
        for ($i = 0; $i < $eventCount; $i++) {
            $HardnessValue = $_POST['form_hardness'][$i]['value'];
            $noloop = $i+1;
            $sql_hardness .= "(
                    '$transid',
                    '$ProductCode',
                    '$HardnessValue',
                    '$noloop',
                    '$Nodularity',
                    '$Graphite_Type',
                    '$Pearlite',
                    '$Ferlite',
                    '$MatrixStructure',
                    '$workdate_get',
                    getdate()" . (($i + 1) == $eventCount ? ')' : '),'); 						 
        }	
    
    // var_dump($sql_proses, $sql_hardness);die();
    $sql_update = "UPDATE [$databaseName].[dbo].[inspection_lines_cms]
		SET CF_Active = 'True'
		WHERE SessionID = '$SessionID' AND ProductCode = '$ProductCode'";
    //  var_dump($sql_update);die();
    $stmtLine = sqlsrv_query($conn, $sql_proses);
    $stmtLineHardness = sqlsrv_query($conn, $sql_hardness);
    $stmt_update_state = sqlsrv_query($conn, $sql_update);

    if($stmtLine){
        echo $msg = "Data Berhasil di Input";
    }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}