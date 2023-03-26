<?php
date_default_timezone_set("Asia/Jakarta");
$workingdate_new = date('Y-m-d H:i');
$workingdate = date('Y-m-d');
// $tgl_pertama = date('Y-m-01', strtotime($workingdate));
// $tgl_terakhir = date('Y-m-t', strtotime($workingdate));

$get_lines = "SELECT TOP (1000) 
        CMS.[SessionID]
        ,CMS.[WorkingDate]
        ,CMS.[ProductCode]
        ,PRD.[PartName] as PartName
        ,PRD.[AliasName] as AliasName
        ,CUS.[CustomerCode] as CustomerCode
        ,CUS.[CustomerName] as CustomerName
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
FROM [QA_INS].[dbo].[inspection_lines_cms] CMS
LEFT OUTER JOIN ATI.dbo.PRODUCT_TABLE MT ON MT.ProductCode = CMS.ProductCode
OUTER APPLY 
(
	SELECT TOP 1  ProductCode, [AliasName],[MaterialGrade],[MaterialType], [PartType], [PartName] FROM [PRD].[dbo].[mt_product_casting] 
	WHERE ProductCode = CMS.ProductCode
) PRD
LEFT OUTER JOIN [ATI].[dbo].[CUSTOMER_TABLE] CUS ON CUS.CustomerCode = MT.CustomerCode
WHERE CMS.CF_Active = 0";
// var_dump($get_lines);

// $get_lines = "
// DECLARE 
// 	@D1 datetime = '2022/07/22' 
// 	,@D2 datetime = '2022/07/23'

// SELECT * FROM (
// 	SELECT DT.[WorkingDate]
// 		  ,DT.[ShiftName]
// 		  ,DT.[SessionID]
// 		  ,DT.[LineCode]
// 		  ,DT.[SubSessionID]
// 		  ,DT.[FurnanceNumber]
// 		  ,DT.[ProductCode]
// 		  ,DT.[PartName]
// 		  ,[Comp_CAct]
// 		  ,[Comp_SiAct]
// 		  ,[Comp_MnAct]
// 		  ,[Comp_SAct]
// 		  ,[Comp_CuAct]
// 		  ,[Comp_SnAct]
// 		  ,[Comp_CrAct]
// 		  ,[Comp_PAct]
// 		  ,[Comp_ZnAct]
// 		  ,[Comp_AlAct]
// 		  ,[Comp_TiAct]
// 		  ,[Comp_NiAct]
// 		  ,[Comp_SbAct]
// 		  ,[Comp_MoAct]
// 		  ,[Comp_MgAct]
// 		  ,[Comp_VAct]
// 	  FROM [PRD].[dbo].[melting_lines_product] DT
// 	  OUTER APPLY
// 	  (
// 			SELECT * FROM [PRD].[dbo].[melting_lines]
// 			Where WorkingDate = DT.WorkingDate
// 			AND SessionID = DT.SessionID
// 			AND SubSessionID = DT.SubSessionID
// 			AND LineCode = DT.LineCode
// 			AND ShiftName = DT.ShiftName
// 			AND Remark is null
// 	  ) MD
// ) MT 
// WHERE WorkingDate BETWEEN @D1 AND @D2";

$stmt_report = sqlsrv_query($conn, $get_lines);