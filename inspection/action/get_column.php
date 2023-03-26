<?php
require "../conf/dbkoneksi.php";
header('Content-Type: application/json; charset=utf-8');

	$SessionID = $_GET['SessionID'];
	$ProductCode = $_GET['ProductCode'];
	
    $sql_col = "SELECT TOP (1000) 
            CMS.[SessionID]
            ,CMS.[WorkingDate]
            ,CMS.[ProductCode]
            ,PRD.[PartName] as PartName
            ,CMS.[ShiftName]
            ,CMS.[LineName]
            ,CMS.[No_CMS]
            ,CMS.[Time_CMS]
            ,CMS.[FurnanceNumber]
            ,CMS.[Sample]
            ,CMS.[Laddle]
            ,CAST(CAST(CMS.Comp_CAct AS numeric(10, 2)) AS float) AS Comp_CAct
            ,CAST(CAST(CMS.Comp_SiAct AS numeric(10, 2)) AS float) AS Comp_SiAct 
            ,CAST(CAST(CMS.Comp_MnAct AS numeric(10, 3)) AS float) AS Comp_MnAct
            ,CAST(CAST(CMS.Comp_SAct AS numeric(10, 3)) AS float) AS Comp_SAct
            ,CAST(CAST(CMS.Comp_CuAct AS numeric(10, 3)) AS float) AS Comp_CuAct
            ,CAST(CAST(CMS.Comp_SnAct AS numeric(10, 3)) AS float) AS Comp_SnAct
            ,CAST(CAST(CMS.Comp_CrAct AS numeric(10, 3)) AS float) AS Comp_CrAct
            ,CAST(CAST(CMS.Comp_PAct AS numeric(10, 3)) AS float) AS Comp_PAct
            ,CAST(CAST(CMS.Comp_ZnAct AS numeric(10, 3)) AS float) AS Comp_ZnAct
            ,CAST(CAST(CMS.Comp_AlAct AS numeric(10, 3)) AS float) AS Comp_AlAct
            ,CAST(CAST(CMS.Comp_TiAct AS numeric(10, 3)) AS float) AS Comp_TiAct
            ,CAST(CAST(CMS.Comp_MgAct AS numeric(10, 3)) AS float) AS Comp_MgAct
            ,CAST(CAST(CMS.Comp_NiAct AS numeric(10, 3)) AS float) AS Comp_NiAct
            ,CAST(CAST(CMS.Comp_VAct AS numeric(10, 3)) AS float) AS Comp_VAct
            ,CAST(CAST(CMS.Comp_MoAct AS numeric(10, 3)) AS float) AS Comp_MoAct
            ,CAST(CAST(CMS.Comp_SbAct AS numeric(10, 3)) AS float) AS Comp_SbAct
            ,CAST(CAST(CMS.Comp_Fe1 AS numeric(10, 4)) AS float) AS Comp_Fe1
            ,CAST(CAST(CMS.Comp_Fe2 AS numeric(10, 4)) AS float) AS Comp_Fe2
            ,CMS.[Remark]
            ,CMS.[AssetID]
            ,CMS.[RecordTime]
            ,CMS.[CompositionRemark]
            ,CMS.[RecID]
    FROM [$databaseName].[dbo].[inspection_lines_cms] CMS
    OUTER APPLY 
    (
        SELECT TOP 1  ProductCode, [AliasName],[MaterialGrade],[MaterialType], [PartType], [PartName] FROM [PRD].[dbo].[mt_product_casting] 
        WHERE ProductCode = CMS.ProductCode
    ) PRD
	WHERE CMS.SessionID = '$SessionID' AND CMS.ProductCode = '$ProductCode'";

        $stmt_column = sqlsrv_query($conn, $sql_col);
        $fields_set = "";
        
        $res = [];

        while( $row = sqlsrv_fetch_array($stmt_column, SQLSRV_FETCH_ASSOC) ) {
            $res[] = $row;
        }
        
        sqlsrv_free_stmt($stmt_column);
        echo json_encode( [ 'data' => $res ] );