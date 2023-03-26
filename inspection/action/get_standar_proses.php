<?php
require "../conf/dbkoneksi.php";
// require "../session.php";
header('Content-Type: application/json; charset=utf-8');
session_start();
    if($_POST['sample']){
        $sample = $_POST['sample'];
        $res = [];

        $data_hardness = "SELECT TOP 1 
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax,
        ItemCheck
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'Hardness' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_hardness = sqlsrv_query($conn, $data_hardness);
        
        $data_nodul = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'Nodularity' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_nodul = sqlsrv_query($conn, $data_nodul);

        $data_pearl = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'Pearlite' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_pearl = sqlsrv_query($conn, $data_pearl);

        $data_ferli = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'Ferlite' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_ferli = sqlsrv_query($conn, $data_ferli);
        
        $data_grap = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'GraphiteType' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_graph = sqlsrv_query($conn, $data_grap);

        $data_matrix = "SELECT TOP 1 
        Param,
        STDVal
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE Sample = '$sample' AND Element = 'MatrixStructure' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_matrix = sqlsrv_query($conn, $data_matrix);

        $hard = sqlsrv_fetch_array($stmt_hardness, SQLSRV_FETCH_ASSOC);
        $nodul = sqlsrv_fetch_array($stmt_nodul, SQLSRV_FETCH_ASSOC);
        $pearl = sqlsrv_fetch_array($stmt_pearl, SQLSRV_FETCH_ASSOC);
        $ferli = sqlsrv_fetch_array($stmt_pearl, SQLSRV_FETCH_ASSOC);
        $graph = sqlsrv_fetch_array($stmt_graph, SQLSRV_FETCH_ASSOC);
        $matrix = sqlsrv_fetch_array($stmt_matrix, SQLSRV_FETCH_ASSOC);
    //    var_dump($data_hardness);die();
        echo json_encode( [ 'hard' => $hard, 
                            'nodul'  => $nodul, 
                            'pearl' => $pearl, 
                            'ferli' => $ferli, 
                            'graph' => $graph,
                            'matrix' => $matrix
                        ]);
    }else{
        echo json_encode( [ 'data' => 'error' ] );
    }
