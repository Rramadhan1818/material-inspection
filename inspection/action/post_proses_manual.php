<?php
require "../conf/dbkoneksi.php";

try {
    $shiftName = $_POST['shiftName'];
    $workdate_get = $_POST['workdate_get'];
    $transid = $_POST['transid'];
    
    // Array Composition
    $Time_CMS = $_POST['composition']['Time_CMS'];
    $FM = $_POST['composition']['FM'];
    $Sample = $_POST['composition']['Sample'];
    $Laddle = $_POST['composition']['Laddle'];
    $Comp_CAct = $_POST['composition']['Comp_CAct'];
    $Comp_SiAct = $_POST['composition']['Comp_SiAct'];
    $Comp_MnAct = $_POST['composition']['Comp_MnAct'];
    $Comp_SAct = $_POST['composition']['Comp_SAct'];
    $Comp_CuAct = $_POST['composition']['Comp_CuAct'];
    $Comp_SnAct = $_POST['composition']['Comp_SnAct'];
    $Comp_CrAct = $_POST['composition']['Comp_CrAct'];
    $Comp_PAct = $_POST['composition']['Comp_PAct'];
    $Comp_ZnAct = $_POST['composition']['Comp_ZnAct'];
    $Comp_AlAct = $_POST['composition']['Comp_AlAct'];
    $Comp_TiAct = $_POST['composition']['Comp_TiAct'];
    $Comp_MgAct = $_POST['composition']['Comp_MgAct'];
    $Comp_NiAct = $_POST['composition']['Comp_NiAct'];
    $Comp_VAct = $_POST['composition']['Comp_VAct'];
    $Comp_MoAct = $_POST['composition']['Comp_MoAct'];
    $Comp_SbAct = $_POST['composition']['Comp_SbAct'];
    $Comp_Fe1 = $_POST['composition']['Comp_Fe1'];
    $Comp_Fe2 = $_POST['composition']['Comp_Fe2'];

    //  Array Product
    $ProductCode = $_POST['product']['ProductCode'];
    $PartName = $_POST['product']['PartName'];
    $Area = $_POST['product']['Area'];
    
    $sql_proses = "INSERT INTO [$databaseName].[dbo].[inspection_lines_cms]
            (
            [SessionID]
            ,[ProductCode]
            ,[ShiftName]
            ,[WorkingDate]
            ,[LineName]
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
            $sql_proses .= "(
                    '$transid',
                    '$ProductCode',
                    '$shiftName',
                    '$workdate_get',
                    '$Area',
                    '$Time_CMS',
                    '$FM',
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
                    'Manual',
                    '',
                    getdate(),
                    '')"; 						 
	
    // var_dump($sql_proses);die();
    $stmtLine = sqlsrv_query($conn, $sql_proses);

    if($stmtLine){
        echo $msg = "Data Berhasil di Input";
    }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}