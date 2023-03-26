<?php
require "../conf/dbkoneksi.php";

try {
    $shiftName = $_POST['shiftName'];
    $workdate_get = $_POST['workdate_get'];
    $transid = $_POST['transid'];
    $area = $_POST['area'];
    
    $composition = $_POST['compositionOne'];
    $FM = $composition["FM"];

    $ProductCode = $_POST['product']['ProductCode'];
    $PartName = $_POST['product']['PartName'];
    $CustomerCode = $_POST['product']['CustomerCode'];
    $CustomerName = $_POST['product']['CustomerName'];
    $MaterialType = $_POST['product']['MaterialType'];
    $MaterialGrade = $_POST['product']['MaterialGrade'];
    
    $sql_proses = "INSERT INTO [$databaseName].[dbo].[inspection_lines_cms]
            (
            [SessionID]
            ,[ProductCode]
            ,[ShiftName]
            ,[WorkingDate]
            ,[LineName]
            ,[No_CMS]
            ,[Time_CMS]
            ,[FurnanceNumber]
            ,[Sample]
            ,[Laddle]
            ,[Comp_CAct]
            ,[Comp_SiAct]
            ,[Comp_MnAct]
            ,[Comp_SAct]
            ,[Comp_CuAct]
            ,[Comp_SnAct]
            ,[Comp_CrAct]
            ,[Comp_PAct]
            ,[Comp_ZnAct]
            ,[Comp_AlAct]
            ,[Comp_TiAct]
            ,[Comp_MgAct]
            ,[Comp_NiAct]
            ,[Comp_VAct]
            ,[Comp_MoAct]
            ,[Comp_SbAct]
            ,[Comp_Fe1]
            ,[Comp_Fe2]
            ,[Remark]
            ,[AssetID]
            ,[RecordTime]
            ,[CompositionRemark]
            )
        VALUES";
        $eventCount = count($_POST['composition']);
        for ($i = 0; $i < $eventCount; $i++) {
            $val = $_POST['composition'][$i];
            $No_CMS = $val[0][0][0];
            $Time_CMS = $val[0][1][0];
            $FurnanceNumber = $val[0][2][0];
            $Sample = $val[0][3][0];
            $Laddle = $val[0][4][0];
            $Comp_CAct = $val[0][5][0];
            $Comp_SiAct = $val[0][6][0];
            $Comp_MnAct = $val[0][7][0];
            $Comp_SAct = $val[0][8][0];
            $Comp_CuAct = $val[0][9][0];
            $Comp_SnAct = $val[0][10][0];
            $Comp_CrAct = $val[0][11][0];
            $Comp_PAct = $val[0][12][0];
            $Comp_ZnAct = $val[0][13][0];
            $Comp_AlAct = $val[0][14][0];
            $Comp_TiAct = $val[0][15][0];
            $Comp_MgAct = $val[0][16][0];
            $Comp_NiAct = $val[0][17][0];
            $Comp_VAct = $val[0][18][0];
            $Comp_MoAct = $val[0][19][0];
            $Comp_SbAct = $val[0][20][0];
            $Comp_Fe1 = $val[0][21][0];
            $Comp_Fe2 = $val[0][22][0];

            $sql_proses .= "(
                    '$transid',
                    '$ProductCode',
                    '$shiftName',
                    '$workdate_get',
                    '$area',
                    '$No_CMS',
                    '$Time_CMS',
                    '$FurnanceNumber',
                    '$Sample',
                    '$Laddle',
                    '$Comp_CAct',
                    '$Comp_SiAct',
                    '$Comp_MnAct',
                    '$Comp_SAct',
                    '$Comp_CuAct',
                    '$Comp_SnAct',
                    '$Comp_CrAct',
                    '$Comp_PAct',
                    '$Comp_ZnAct',
                    '$Comp_AlAct',
                    '$Comp_TiAct',
                    '$Comp_MgAct',
                    '$Comp_NiAct',
                    '$Comp_VAct',
                    '$Comp_MoAct',
                    '$Comp_SbAct',
                    '$Comp_Fe1',
                    '$Comp_Fe2',
                    '',
                    '',
                    getdate(),
                    ''" . (($i + 1) == $eventCount ? ')' : '),'); 						 
        }		
	
    // var_dump($sql_product);die();
    $stmtLine = sqlsrv_query($conn, $sql_proses);

    if($stmtLine){
        echo $msg = "Data Berhasil di Input";
    }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}