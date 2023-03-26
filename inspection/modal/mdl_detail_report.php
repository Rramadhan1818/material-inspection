<style>
    .title-head {
        margin-bottom: 2px;
        text-decoration: bold;
        font-weight: bold;
    }

    h6 {
        font-size: 12px;    
    }

    .image-caption {
        padding: 10px 50px 10px 50px;
        transition: transform .2s; /* Animation */
        width: 390px;
        margin: 0 auto;
    }

    .image-caption:hover {
    transform: scale(1.5); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }

    .form-check-input:checked {
        background-color: #7abaff;
        border-color: #b8daff;
    }
</style>

<?php
require "../conf/dbkoneksi.php";
// var_dump( $_POST);
if(isset($_POST)){
    $res = [];
    $photo = [];
    $TransID = $_POST['TransID'];
    $ProductCode = $_POST['ProductCode'];
    $SessionID = $_POST['SessionID'];
    
        // Standar
        $data_hardness = "SELECT TOP 1 
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax,
        ItemCheck
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'Hardness' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_hardness = sqlsrv_query($conn, $data_hardness);

        $data_nodul = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'Nodularity' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_nodul = sqlsrv_query($conn, $data_nodul);

        $data_pearl = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'Pearlite' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_pearl = sqlsrv_query($conn, $data_pearl);

        $data_ferli = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'Ferlite' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_ferli = sqlsrv_query($conn, $data_ferli);
        
        $data_grap = "SELECT TOP 1 
        Param,
        CAST(STDMin AS DECIMAL(3)) AS STDMin,
        CAST(STDMax AS DECIMAL(3)) AS STDMax
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'GraphiteType' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_graph = sqlsrv_query($conn, $data_grap);

        $data_matrix = "SELECT TOP 1 
        Param,
        STDVal
        FROM [QA_INS].[dbo].[QC_INSPECTION_STANDARD] WHERE ProductCode = '$ProductCode' AND Element = 'MatrixStructure' AND StartDate <= CAST(GETDATE() as date)";
        $stmt_matrix = sqlsrv_query($conn, $data_matrix);

        $hard = sqlsrv_fetch_array($stmt_hardness, SQLSRV_FETCH_ASSOC);
        $nodul = sqlsrv_fetch_array($stmt_nodul, SQLSRV_FETCH_ASSOC);
        $pearl = sqlsrv_fetch_array($stmt_pearl, SQLSRV_FETCH_ASSOC);
        $ferli = sqlsrv_fetch_array($stmt_pearl, SQLSRV_FETCH_ASSOC);
        $graph = sqlsrv_fetch_array($stmt_graph, SQLSRV_FETCH_ASSOC);
        $matrix = sqlsrv_fetch_array($stmt_matrix, SQLSRV_FETCH_ASSOC);
        $std = [ 'hard' => $hard, 
                            'nodul'  => $nodul, 
                            'pearl' => $pearl, 
                            'ferli' => $ferli, 
                            'graph' => $graph,
                            'matrix' => $matrix
                        ];


    // GET TRANS
    $sql_trans = "SELECT TOP 1
    TR.[TransID]
    ,TR.[WorkingDate]
	,FORMAT(TR.[WorkingDate], 'dd-MMM-yyyy') HariKerja
    ,TR.[ShiftName]
    ,US.[Picture]
    ,US.[EmployeeName] AS OperatorName
    ,US1.[EmployeeName] AS LeaderName
    ,US2.[EmployeeName] AS ForemanName
    ,FORMAT(TR.[StartTime], 'HH:mm') StartTime
	,FORMAT(TR.[FinishTime], 'HH:mm') FinishTime
    FROM [$databaseName].[dbo].[inspection_trans] TR
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] US ON US.[EmpID] = TR.[OperatorID]
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] US1 ON US1.[EmpID] = TR.[LeaderID]
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] US2 ON US2.[EmpID] = TR.[ForemanID]
    WHERE TR.TransID = '$TransID'";
    $stmt_trans = sqlsrv_query($conn, $sql_trans);
    $row_trans = sqlsrv_fetch_array($stmt_trans, SQLSRV_FETCH_ASSOC);

    $sql_col = "DECLARE
                @TransID bigint = '$TransID' ,
                @ProductCode varchar(50) = '$ProductCode', 
                @SessionID bigint = '$SessionID'

                SELECT 
                TransID	
                ,LN.SessionID	
                ,LN.ShiftName	
                ,LN.DateCode	
                ,LN.CavityNo	
                ,LN.Created_at	
                ,US.EmployeeName AS PreparedBy	
                ,LN.PreparedStatus	
                ,LN.PreparedDate
                ,US1.EmployeeName AS CheckedBy	
                ,LN.CheckedStatus	
                ,LN.CheckedDate
                ,US2.EmployeeName AS ApprovedForemanBy	
                ,LN.ApprovedForemanStatus	
                ,LN.ApprovedForemanDate	
                ,LN.ApprovedSectBy	
                ,LN.ApprovedSectStatus	
                ,LN.ApprovedSectDate	
                ,LN.CF_Result	
                ,LN.Remark	
         	    ,CUS.CustomerCode	
                ,CUS.CustomerName	
                ,PRD.ProductCode	
                ,PRD.PartName
                ,PRD.MaterialGrade	
                ,CMS.No_CMS	
                ,CMS.Time_CMS	
                ,CMS.FurnanceNumber	
                ,CMS.Sample	
                ,CMS.Laddle	
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
                FROM [QA_INS].[dbo].[inspection_lines] LN
                LEFT OUTER JOIN [QA_INS].[dbo].[inspection_lines_cms] CMS ON CMS.SessionID = LN.SessionID
                LEFT OUTER JOIN ATI.dbo.PRODUCT_TABLE MT ON MT.ProductCode = CMS.ProductCode
                OUTER APPLY 
                (
                    SELECT TOP 1  ProductCode, [AliasName],[MaterialGrade],[MaterialType], [PartType], [PartName] FROM [PRD].[dbo].[mt_product_casting] 
                    WHERE ProductCode = CMS.ProductCode
                ) PRD
                LEFT OUTER JOIN [ATI].[dbo].[CUSTOMER_TABLE] CUS ON CUS.CustomerCode = MT.CustomerCode
                LEFT OUTER JOIN [ATI].[dbo].[HRD_EMPLOYEE_TABLE] US ON US.[EmpID] = LN.[PreparedBy]
                LEFT OUTER JOIN [ATI].[dbo].[HRD_EMPLOYEE_TABLE] US1 ON US1.[EmpID] = LN.[CheckedBy]
                LEFT OUTER JOIN [ATI].[dbo].[HRD_EMPLOYEE_TABLE] US2 ON US2.[EmpID] = LN.[ApprovedForemanBy]
                WHERE LN.[TransID] = @TransID AND PRD.[ProductCode] = @ProductCode AND LN.SessionID = @SessionID";
            //    var_dump($sql_col);

                $stmt_column = sqlsrv_query($conn, $sql_col);
                
                while( $row = sqlsrv_fetch_array($stmt_column, SQLSRV_FETCH_ASSOC) ) {
                    $res[] = $row;
                }

                $hardnesss_query = "SELECT 
                            HD.ProductCode
                            ,HD.HardnessValue
                            ,HD.Nodularity
                            ,HD.Graphite_Type
                            ,HD.MatrixStructure
                            ,HD.Pearlite
                            ,HD.Ferlite
                            FROM [QA_INS].[dbo].[inspection_lines_hardness] HD
							WHERE HD.ProductCode = '$ProductCode' AND HD.TransID = '$TransID' ";
                // var_dump($hardnesss_query);
                $stmt_hardness = sqlsrv_query($conn, $hardnesss_query);

                $photo_query  = "SELECT TOP (10) [TransID]
                                ,[Picture]
                                ,[PictureNo]
                                ,[RecID]
                                FROM [QA_INS].[dbo].[inspection_lines_microstructure] WHERE TransID = '$TransID' AND ProductCode = '$ProductCode'";
                $stmt_photo = sqlsrv_query($conn, $photo_query);
                while( $row_photo = sqlsrv_fetch_array($stmt_photo, SQLSRV_FETCH_ASSOC) ) {
                    $photo[] = $row_photo;
                }

                include('../query/Q_get_composition_report.php');
}


?>
            <?php if(!$res == ''){ ?>
                <div class="container" style="font-size:9px">
                        <div style="margin-bottom: .5rem;">
                            <table border="1">
                                <tr>
                                    <td width="20%" style="text-align: center;"><img alt="ATI" src="https://portal.at-indonesia.co.id/PortalSupplierATI/assets/images/ATI_bg.png" style="width: 4rem;height: 2rem;"/></td>
                                    <td width="50%" style="font-size: 1.5rem; text-align: center; font-weight: bold;">MATERIAL INSPECTION</td>
                                    <td width="30%" style="font-size: 0.6rem; padding-left:20px">PT. AT INDONESIA <br>
                                    Jl. Maligi III Lot 1-5, Kawasan Industri KIIC <br>
                                    Tol Jakarta Cikampek Km. 47 - Karawang</td>
                                </tr>
                            </table>
                        </div>
    
                        
                        <div style="margin-bottom: .5rem;">
                            <table style="width:100%">
                                <tr>
                                    <td width="20%" style="text-align: left; font-size: 0.7rem;">Part No</td>
                                    <td width="55%" style="text-align: left; font-size: 0.7rem;">: <?= $res[0]['ProductCode'] ?></td>
                                    <td width="25%"  colspan="2" style="text-align: left; font-size: 0.7rem; vertical-align: baseline;">Purpose : </td>
                                </tr>
                                <tr>
                                    <td width="20%" style="text-align: left; font-size: 0.7rem;">Part Name</td>
                                    <td width="55%" style="text-align: left; font-size: 0.7rem;">: <?= $res[0]['PartName'] ?></td>
                                    <td width="25%" rowspan="6" style="text-align: left; font-size: 0.7rem; vertical-align: baseline;">
                                        <div class="form-check mb-0 ml-2" style="min-height:1rem">
                                        <input class="form-check-input" type="checkbox" value="Sample" name="Sample" id="Sample">
                                        <label class="form-check-label" for="Sample">
                                            Sample
                                        </label>
                                        </div>
                                        <div class="form-check mb-0 ml-2" style="min-height:1rem">
                                            <input class="form-check-input" type="checkbox" value="Reguler" name="Reguler" id="Reguler" checked>
                                            <label class="form-check-label" for="Reguler">
                                                Reguler
                                            </label>
                                        </div>
                                        <div class="form-check mb-0 ml-2" style="min-height:1rem">
                                            <input class="form-check-input" type="checkbox" value="Inisial_Product" name="Inisial_Product" id="Inisial_Product">
                                            <label class="form-check-label" for="Inisial_Product">
                                                Inisial Product
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align: left; font-size: 0.7rem;">Material</td>
                                    <td width="35%" style="text-align: left; font-size: 0.7rem;">   : <?= $res[0]['MaterialGrade'] ?></td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align: left; font-size: 0.7rem;">Shift</td>
                                    <td width="35%" style="text-align: left; font-size: 0.7rem;">   : <?= $res[0]['ShiftName'] ?></td></td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align: left; font-size: 0.7rem;">Laddle</td>
                                    <td width="35%" style="text-align: left; font-size: 0.7rem;">   : <?= $res[0]['Laddle'] ?></td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align: left; font-size: 0.7rem;">Casting Date</td>
                                    <td width="35%" style="text-align: left; font-size: 0.7rem;">   : <?= $res[0]['DateCode'] ?></td>
                                </tr>
                                <tr>
                                    <td width="15%" style="text-align: left; font-size: 0.7rem;">Cavity No</td>
                                    <td width="35%" style="text-align: left; font-size: 0.7rem;">   : <?= $res[0]['CavityNo'] ?></td>
                                </tr>
                            </table>
                        </div>
    
                        <div style="margin-bottom: .5rem;">
                            <h6>1 Chemical Composition </h6>
                            <table style="width:100%">
                                <thead>
                                    <tr class="table-primary">
                                        <td style="text-align: center; font-size: 0.7rem;" rowspan="2">Standar </td>
                                    <?php $comp = $comp[0];
                                    if($comp['ParamC'] ){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">C</td>';
                                    }
                                    if($comp['ParamSi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Si</td>';
                                    }
                                    if($comp['ParamMn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Mn</td>';
                                    }
                                    if($comp['ParamS']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">S</td>';
                                    }
                                    if($comp['ParamCu']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Cu</td>';
                                    }
                                    if($comp['ParamSn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Sn</td>';
                                    }
                                    if($comp['ParamCr']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Cr</td>';
                                    }
                                    if($comp['ParamP']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">P</td>';
                                    }
                                    if($comp['ParamZn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Zn</td>';
                                    }
                                    if($comp['ParamAl']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Al</td>';
                                    }
                                    if($comp['ParamTi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Ti</td>';
                                    }
                                    if($comp['ParamMg']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Mg</td>';
                                    }
                                    if($comp['ParamNi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Ni</td>';
                                    }
                                    if($comp['ParamV']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">V</td>';
                                    }
                                    if($comp['ParamMo']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Mo</td>';
                                    }
                                    if($comp['ParamSb']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Sb</td>';
                                    }
                                    if($comp['ParamFe1']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Fe1</td>';
                                    }
                                    if($comp['ParamFe2']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">Fe2</td>';
                                    }   
                                    ?>
                                        <td style="text-align: center; font-size: 0.7rem;">Result</td>
                                    </tr>
                                    <tr>
                                        <?php
                                    if($comp['ParamC'] ){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleC'] .'</td>';
                                    }
                                    if($comp['ParamSi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleSi'] .'</td>';
                                    }
                                    if($comp['ParamMn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleMn'] .'</td>';
                                    }
                                    if($comp['ParamS']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleS'] .'</td>';
                                    }
                                    if($comp['ParamCu']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleCu'] .'</td>';
                                    }
                                    if($comp['ParamSn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleSn'] .'</td>';
                                    }
                                    if($comp['ParamCr']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleCr'] .'</td>';
                                    }
                                    if($comp['ParamP']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleP'] .'</td>';
                                    }
                                    if($comp['ParamZn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleZn'] .'</td>';
                                    }
                                    if($comp['ParamAl']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleAl'] .'</td>';
                                    }
                                    if($comp['ParamTi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleTi'] .'</td>';
                                    }
                                    if($comp['ParamMg']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleMg'] .'</td>';
                                    }
                                    if($comp['ParamNi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleNi'] .'</td>';
                                    }
                                    if($comp['ParamV']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleV'] .'</td>';
                                    }
                                    if($comp['ParamMo']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleMo'] .'</td>';
                                    }
                                    if($comp['ParamSb']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleSb'] .'</td>';
                                    }
                                    if($comp['ParamFe1']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleFe1'] .'</td>';
                                    }
                                    if($comp['ParamFe2']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['TitleFe2'] .'</td>';
                                    }   
                                    ?>
                                        <td style="text-align: center; font-size: 0.7rem;" rowspan="2">
                                    
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                    <td style="text-align: center; font-size: 0.7rem;">Inspection</td>
                                    <?php
                                        if($comp['ParamC'] ){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_CAct'] .'</td>';
                                    }
                                    if($comp['ParamSi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_SiAct'] .'</td>';
                                    }
                                    if($comp['ParamMn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_MnAct'] .'</td>';
                                    }
                                    if($comp['ParamS']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_SAct'] .'</td>';
                                    }
                                    if($comp['ParamCu']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_CuAct'] .'</td>';
                                    }
                                    if($comp['ParamSn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_SnAct'] .'</td>';
                                    }
                                    if($comp['ParamCr']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_CrAct'] .'</td>';
                                    }
                                    if($comp['ParamP']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_PAct'] .'</td>';
                                    }
                                    if($comp['ParamZn']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_ZnAct'] .'</td>';
                                    }
                                    if($comp['ParamAl']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_AlAct'] .'</td>';
                                    }
                                    if($comp['ParamTi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_TiAct'] .'</td>';
                                    }
                                    if($comp['ParamMg']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_MgAct'] .'</td>';
                                    }
                                    if($comp['ParamNi']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_NiAct'] .'</td>';
                                    }
                                    if($comp['ParamV']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_VAct'] .'</td>';
                                    }
                                    if($comp['ParamMo']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_MoAct'] .'</td>';
                                    }
                                    if($comp['ParamSb']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_SbAct'] .'</td>';
                                    }
                                    if($comp['ParamFe1']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_Fe1Act'] .'</td>';
                                    }
                                    if($comp['ParamFe2']){
                                        echo '<td style="text-align: center; font-size: 0.7rem;">'. $comp['Comp_Fe2Act'] .'</td>';
                                    }   
                                        ?>
                                    </tr> 
                                </thead>
                            </table>
                        </div>
                        <div style="margin-bottom: .5rem;">
                            <h6>2 Hardness & Microstructure </h6>
                            <table style="width:100%">
                                <thead>
                                    <tr class="table-primary">
                                        <td style="text-align: center; font-size: 0.7rem;">Item Check</td>
                                        <?php
                                            if($std['hard']['ItemCheck'] == 'HV' ){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Hardness Vickers (HV)</td>';
                                            }else{
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Hardness Brinell (HB)</td>';
                                            }

                                            if($std['nodul'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Nodularity (%)</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['pearl'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Pearlite (%)</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['ferli'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Ferlite (%)</td>';
                                            }else{
                                                echo '';
                                            }
                                            
                                            if($std['graph'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Graphite Type</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['matrix'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">Matrix Structure</td>';
                                            }else{
                                                echo '';
                                            }
                                        ?>
                                        <td style="text-align: center; font-size: 0.7rem;">Result</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; font-size: 0.7rem;">Standard </td>
                                        <td style="text-align: center; font-size: 0.7rem;"><?= $std['hard']['STDMin'] . ' - ' . $std['hard']['STDMax'] ?></td>
                                        <?php
                                            if($std['nodul'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;"> ' . $std['nodul']['STDMin'] . ' - '. $std['nodul']['Param'] .  '</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['pearl'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">' . $std['pearl']['STDMin'] . ' - ' . $std['pearl']['STDMax'] . '</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['ferli'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">' . $std['ferli']['STDMin'] . ' - ' . $std['ferli']['STDMax'] .  '</td>';
                                            }else{
                                                echo '';
                                            }
                                            
                                            if($std['graph'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;"> ' . $std['graph']['STDMin'] . ' - ' . $std['graph']['STDMax'] . '</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['matrix'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">' . $std['graph']['STDVal'] . '</td>';
                                            }else{
                                                echo '';
                                            }
                                        ?>
                                        <!-- <td style="text-align: center; font-size: 0.7rem;">
                                        </td> -->
                                    </tr>
                                    <?php   $r = 0; 
                                            $numItems = count(sqlsrv_fetch_array($stmt_hardness, SQLSRV_FETCH_ASSOC)); 
                                            while($hd = sqlsrv_fetch_array($stmt_hardness, SQLSRV_FETCH_ASSOC)) { 
                                            $r++
                                            ?>
                                        <tr>
                                            <td style="text-align: center; font-size: 0.7rem;">Actual</td>
                                            <td style="text-align: center; font-size: 0.7rem;"><?= $hd['HardnessValue'] ?></td>
                                            <?php
                                            if($std['nodul'] !== null){
                                                if($r == 1) {
                                                    echo '<td style="text-align: center; font-size: 0.7rem;" rowspan="'. $numItems.'">'. $hd['Nodularity'] .'</td>';
                                                }
                                            }else{
                                                echo '';
                                            }

                                            if($std['pearl'] !== null){
                                                if($r == 1) {
                                                    echo '<td style="text-align: center; font-size: 0.7rem;" rowspan="' . $numItems . '">'. $hd['Pearlite'] .'</td>';
                                                }
                                            }else{
                                                echo '';
                                            }

                                            if($std['ferli'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">'. $hd['Ferlite'] .'</td>';
                                            }else{
                                                echo '';
                                            }
                                            
                                            if($std['graph'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">' . $hd['Graphite_Type'] . '</td>';
                                            }else{
                                                echo '';
                                            }

                                            if($std['matrix'] !== null){
                                                echo '<td style="text-align: center; font-size: 0.7rem;">'.$hd['MatrixStructure'] .'</td>';
                                            }else{
                                                echo '';
                                            }
                                            ?>
                                                <?php 
                                                if($r == 1) {
                                                    if($hd['HardnessValue'] >= $std['hard']['STDMin'] && $hd['HardnessValue'] <= $std['hard']['STDMax'] && $hd['Nodularity'] < $std['nodul']['STDMin']){
                                                    echo '<td style="text-align: center; font-size: 0.7rem;" rowspan="'. $numItems .'">OK </td>';
                                                    }else{
                                                    echo '<td style="text-align: center; font-size: 0.7rem;" rowspan="' . $numItems .'">NG</td>';
                                                    } ;
                                                } 
                                                ?>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </thead>
                            </table>
                        </div>
    
                        <div class="row" style="margin-bottom: .5rem;">
                            <div class="col-md-6" >
                                <h6>3 Cutting sketch</h6>
                                <!-- <img src="data:image;base64,<?= $res[0]['Picture'] ?>" width="250px;"
                                    class="image-caption" style=" margin: auto; display: block;"> -->
                                    <img src="../assets/img-design/<?= $ProductCode ?>.png" width="250px;"
                                    class="image-caption" style=" margin: auto; display: block;">
                            </div>
                            <div class="col-md-6">
                                <h6>4 Microstructure photograph 100X</h6>
                                    <div class="row">
                                    <?php
                                    $PhotoCount = count($photo);
                                    for ($i = 0; $i < $PhotoCount; $i++) { ?>
                                        <div class="col-sm-6 mb-2"><img src="<?= $photo[$i]['Picture'] ?>" 
                                        class="img-fluid"></div>
                                        <?php } ?>
                                    </div>
                            </div>
                        </div>
    
                        <div style="margin-bottom: .5rem; margin-top: .5rem;">
                             <table class="approve report">
                                <tbody>
                                    <tr class="table-primary text-center">
                                        <td>Remarks</td>
                                        <td rowspan="2">Overall Judgement</td>
                                        <!-- <td>Approved 4</td> -->
                                        <td colspan="2">Approved</td>
                                        <td>Checked</td>
                                        <td>Prepared</td>
                                    </tr>
                                        <tr>
                                            <td height="70px" rowspan="4" style="text-align: center; vertical-align: middle;">
                                            <?php if(isset($res[0]['Remark'])){ ?>
                                            <span style="color:#000000; font-family:'Century Gothic'; font-size:9pt">
                                                <i>
                                                    <?php echo $res[0]['Remark'];?>
                                                </i>
                                            </span>
                                            <?php }else{ ?>
                                                <!-- <textarea class="form-control form-control-sm form-sect" rows="5" type="text" id="Remark" name="Remark"></textarea> -->
                                            <?php }  ?>
                                            </td>
                                            <td height="70px" rowspan="2" style="text-align: center; vertical-align: middle;">
                                                <?php if($res[0]['ApprovedSectStatus'] == 3){ ?>
                                                    <img src="../assets/img/approved.png" width="100px"> 
                                                <?php }else if($res[0]['ApprovedForemanStatus'] == 2){  ?>
                                                    <img src="../assets/img/rejected.png" width="100px">
                                                <?php }  ?>
                                            </td>
                                            <td height="70px" rowspan="2" style="text-align: center; vertical-align: middle;">
                                                <?php if($res[0]['ApprovedForemanStatus'] == 3){ ?>
                                                    <img src="../assets/img/approved.png" width="100px"> 
                                                <?php }else if($res[0]['ApprovedForemanStatus'] == 2){  ?>
                                                    <img src="../assets/img/rejected.png" width="100px">
                                                <?php }  ?>
                                            </td>
                                            <td height="70px" rowspan="2" style="text-align: center; vertical-align: middle;">
                                                <?php if($res[0]['CheckedStatus'] == 3){ ?>
                                                    <img src="../assets/img/approved.png" width="100px"> 
                                                <?php }else if($res[0]['CheckedStatus'] == 2){  ?>
                                                    <img src="../assets/img/rejected.png" width="100px">
                                                <?php }  ?>
                                            </td>
                                            <td height="70px" rowspan="2" style="text-align: center; vertical-align: middle;"> 
                                                <?php if($res[0]['PreparedStatus'] == 3){ ?>
                                                    <img src="../assets/img/approved.png" width="100px"> 
                                                <?php }else if($res[0]['PreparedStatus'] == 2){  ?>
                                                    <img src="../assets/img/rejected.png" width="100px">
                                                <?php }  ?>
                                            </td>
                                        </tr>
                                    <tr>
                                        <td rowspan="3" style="text-align: center; vertical-align: middle;">
                                            <?php if(isset($res[0]['CF_Result'])){ echo $res[0]['CF_Result']; }else{ ?>
                                                <!-- <div class="form-group form-sect" >
                                                    <div class="form-check form-check-inline">
                                                        <input style="font-size: 22px; font-weight: bold;"
                                                            class="form-check-input" type="radio" value="OK" name="CF_Result"
                                                            name="ShiftName" checked>
                                                        <label style="font-size: 22px; font-weight: bold;"
                                                            class="form-check-label" for="sp">OK</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input style="font-size: 22px; font-weight: bold;"
                                                            class="form-check-input" type="radio" value="NG" name="CF_Result">
                                                        <label style="font-size: 22px; font-weight: bold;"
                                                            class="form-check-label" for="sm">NG</label>
                                                    </div>
                                                </div>  -->
                                            <?php } ?>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"></span><?= isset($res[0]['ApprovedSectBy']) ? $res[0]['ApprovedForemanBy'] : '' ?></td>
                                        <td>Name : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"></span><?= isset($res[0]['ApprovedForemanBy']) ? $res[0]['ApprovedForemanBy'] : '' ?></td>
                                        <td>Name : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"></span><?= isset($res[0]['CheckedBy']) ? $res[0]['CheckedBy'] : '' ?></td>
                                        <td>Name : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"></span><?= isset($res[0]['PreparedBy']) ? $res[0]['PreparedBy'] : '' ?> </td>
                                    </tr>
                                    <tr>
                                        <!-- <?= date('d M Y h:m:i', strtotime($res[0]['Created_at'])) ?> -->
                                        <td>Date  : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"><?= isset($res[0]['ApprovedSectDate']) ? date('d M Y h:m:i', strtotime($res[0]['ApprovedForemanDate'])): '' ?></span></td>
                                        <td>Date  : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"><?= isset($res[0]['ApprovedForemanDate']) ? date('d M Y h:m:i', strtotime($res[0]['ApprovedForemanDate'])): '' ?></span></td>
                                        <td>Date  : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"><?= isset($res[0]['CheckedDate']) ? date('d M Y h:m:i', strtotime($res[0]['CheckedDate'])) : '' ?></span></td>
                                        <td>Date  : <span style="color:#000000; font-family:'Century Gothic'; font-size:7pt"><?= isset($res[0]['PreparedDate']) ? date('d M Y h:m:i', strtotime($res[0]['PreparedDate'])) : '' ?></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            <?php } ?>  
