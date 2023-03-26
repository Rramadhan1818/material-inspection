<!doctype html>
<html lang="en">

<head>

    <?php
    session_start();
    require "conf/dbkoneksi.php";
    require "ui/header.php";
	require "ui/datatable_plugin.php";
	// require "session.php";
    ?>
    <style>
        body {
            background-color: #ddd;
        }

        .container-custom {
            max-width: 1250px;
            padding-right: 5.5px;
            padding-left: 5.5px;
            margin-right: auto;
            margin-left: auto;
        }

        .card-horizontal {
            display: flex;
            flex: 1 1 auto;
        }

        table>thead>tr>th {
            text-align: center;
        }

        table>thead>tr>td {
            text-align: center;
        }

        /* table>tbody>tr>th {
            text-align: center;
        }
        
        table>tbody>tr>td {
            text-align: center;
        } */
        .table thead th {
            border-bottom: 0;
            vertical-align: middle;
        }

        table {
            font-size: 12px;
            margin-bottom: 0px !important;
        }

        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 1rem;
        }

        .error {
            color: red;
        }

        table.dataTable.table-sm>thead>tr>th:not(.sorting_disabled) {
            padding-right: 0px;
            /* padding-left: 0px; */
        }

        button.dt-button,
        div.dt-button,
        a.dt-button,
        input.dt-button {
            border-radius: 0px !important;
            color: white;
            background-color: #007bff;
            padding: 7px;
        }

        button.dt-button:hover:not(.disabled),
        div.dt-button:hover:not(.disabled),
        a.dt-button:hover:not(.disabled),
        input.dt-button:hover:not(.disabled) {
            border: 0px solid #f8f8f8;
            background-color: #343a40;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0.85em;
            padding-left: 1.1rem;
            font-size: 12px !important;
        }

        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 1rem;
        }

        .form-group {
            margin-bottom: 0.5rem
        }

        body {
            margin: 0;
            height: 100%;
            overflow-x: hidden
        }

        .tbl-cms{
            height: 400px; 
            overflow-y: scroll;
        }
    </style>
    <?php
    // GET STATE
    $get_state = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
    $stmt_state = sqlsrv_query($conn, $get_state);
    while($row_get_state = sqlsrv_fetch_array($stmt_state, SQLSRV_FETCH_ASSOC))	
    {
        $operatorID =  $row_get_state['OperatorID'];
        $leaderID =  $row_get_state['LeaderID'];
        $foremanID =  $row_get_state['ForemanID'];
        $shiftName =  $row_get_state['ShiftName'];
        $workdate_get = $row_get_state['WorkingDate'];
        $TransID = $row_get_state['TransID'];
        $Session = $row_get_state['Session'];
    }

    $_SESSION['TransID'] = $TransID;

    //GET TRANS
    $sql_trans = "SELECT TOP 1
    TR.[TransID]
    ,TR.[WorkingDate]
	,FORMAT(TR.[WorkingDate], 'dd-MMM-yyyy') HariKerja
    ,TR.[ShiftName]
    ,US.[PicThumb] AS Picture
    ,US.[EmployeeName]
    ,FORMAT(TR.[StartTime], 'HH:mm') StartTime
	,FORMAT(TR.[FinishTime], 'HH:mm') FinishTime
    FROM [$databaseName].[dbo].[inspection_trans] TR
    LEFT OUTER JOIN [ATI].[dbo].[HRD_EMPLOYEE_TABLE] US ON US.[EmpID] = TR.[OperatorID]
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_state] ST ON ST.[TransID] = TR.[TransID]
    WHERE ST.Session = 'ACTIVE' AND TR.TransID = '$TransID'";
    // var_dump($sql_trans);

    $stmt_trans = sqlsrv_query($conn, $sql_trans);
    $row_get_trans = sqlsrv_fetch_array($stmt_trans, SQLSRV_FETCH_ASSOC);

    //GET DATA TABLE
    $get_table_live = "SELECT TOP (1000) 
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
            ,CMS.[CF_Active]
    FROM [$databaseName].[dbo].[inspection_lines_cms] CMS
    OUTER APPLY 
    (
            SELECT TOP 1  ProductCode, [AliasName],[MaterialGrade],[MaterialType], [PartType], [PartName] FROM [PRD].[dbo].[mt_product_casting] 
            WHERE ProductCode = CMS.ProductCode
    ) PRD";
//    var_dump($get_table_live);
    $stmt_tbl_live = sqlsrv_query($conn, $get_table_live);
?>
</head>
<body>
    <center>
            <div id="loading">
                <div class="spinner-border text-primary text-center" role="status"></div>
            </div>
    </center>
    <?php require "ui/navbar.php"; ?>
    <?php require "modal/mdl_get_part.php"; ?>

    <div class="container-custom">
        <div class="row">
            <div class="col-10">
                <div class="p-0">
                    <div class="card-header p-1 bg-primary">
                    </div>
                </div>
                <div class="card card-outline-secondary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <!-- <label class="col-sm-4 form-control-label form-control-sm mb-0">Cari Composition
                                        </label> -->
                                    <form method="POST" class="form" id="formPost">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-block btn-success btn-xl"
                                                    style="font-size:20px" name="Area" value="DISA">DISA</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button type="submit" class="btn btn-block btn-success btn-xl"
                                                    style="font-size:20px" name="Area" value="ACE">ACE</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- <input class="form-control form-control-sm" type="text" id="searchData"
                                                placeholder="Cari Compotition" data-bs-toggle="modal"
                                                data-bs-target="#modal_get_cms" style="font-size:12px">
                                            <span class="err-search" style="display: none;color: red;"><small>Silahkan
                                                    pilih composition !</small></span> -->
                                </div>
                            </div>
                        </div>
                <?php $AreaName = isset($_POST['Area']) ?  $_POST['Area'] : 'DISA'; ?>
                        <?php
                // $get_area = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
                // $stmt_area = sqlsrv_query($conn, $get_area);
                // $row_get_area = sqlsrv_fetch_array($stmt_area, SQLSRV_FETCH_ASSOC);
                if($AreaName){
                    // var_dump($_POST['Area']);
                        
                    if($AreaName == 'ACE')
                        {
                            $serverComposition = "10.123.230.185";
                        }

                    if($AreaName == 'DISA'){
                            $serverComposition = "10.123.230.186";
                        }
                        $userComposition = "aas";
                        $passwordComposition = "andon";
                        $conn_composition = mysqli_connect($serverComposition, $userComposition, $passwordComposition);
                    ?>
                        <?php if($conn_composition) { ?>
                        <div class="card-header mb-0 p-1 bg-primary">
                            <h6 class="mb-0 text-center">CMS Area <?= $AreaName ?></h6>
                        </div>
                        <div class="card-body mt-1 pt-0">
                            <span class="err-search" style="display: none;color: red;"><small>Silahkan pilih composition !</small></span> 
                            <div class="row tbl-cms">
                                <?php
                                // *** Initialize Local Variables & Constants
                                $second = date('s');
                                $odd_sec = $second%2;
                                $values = array("","","","","","","","","","","","","","","","","","","","");
                                $tr_time = array("","","","","","","","","","","","","","","","","","","","");
                                $furnace = array("","","","","","","","","","","","","","","","","","","","");
                                $samples = array("","","","","","","","","","","","","","","","","","","","");
                                $laddle = array("","","","","","","","","","","","","","","","","","","","");
                                $spektro = array("","","","","","","","","","","","","","","","","","","","");
                                $max_show = 50;
                                $data = "";
                                $idx_data_last = 0;
                                $idx_data_next = 0;
                                $idx_sample = 0;
                                $idx_value = 0;
                                $idx_status = 0;
                                
                                //make blink
                                $detiku = date('s');
                                $modi = fmod($detiku,2); 
                                ?>

                                <?php
                                //table output layout
                                $elements = array("C","Si","Mn","S","Cu","Sn","Cr","P","Zn","Al","Ti","Mg","Ni","V","Mo","Sb","Fe1","Fe2");
                                $layout = "";
                                $layout .= "<table class=\"table-striped table-border\" id=\"table-cms\">";
                                // Table Header
                                $layout .= "<tr align=\"center\">";
                                $layout .= "<th width=\"2%\" ><span class=\"tableheader\" >NO</span></th>";
                                $layout .= "<th width=\"8%\" ><span class=\"tableheader\" >TIME</span></th>";
                                $layout .= "<th width=\"2%\" ><span class=\"tableheader\" >FM</span></th>";
                                $layout .= "<th width=\"10%\" ><span class=\"tableheader\" >SAMPLE</span></th>";
                                $layout .= "<th width=\"4%\" ><span class=\"tableheader\" >LADDLE</span></th>";
                                $width_cell = round(80/count($elements),2);
                                for ($i=0;$i<count($elements);$i++) {
                                    $layout .= "<th width=\"".$width_cell."%\" ><span class=\"tableheader\" >".$elements[$i]."</span></th>";
                                }
                                //$layout .= "<th width=\"2%\" ><span class=\"tableheader\" >OPSI</span></th>";
                                $layout .= "</tr>";

                                // Table Data
                                //$query = "SELECT * FROM tdata_log ORDER BY fid DESC LIMIT $max_show";
                                // $query = "SELECT * FROM tdisplay WHERE fdisplay_name = 'LOCAL' ORDER BY fid DESC";
                                $result = mysqli_query($conn_composition, "SELECT fid
                                , ftr_time
                                , ffurnace
                                , fsample
                                , fladdle
                                , fspektro
                                , fdisplay_name
                                , fshow
                                FROM db_cms.tdisplay ORDER BY ftr_time DESC LIMIT 40");
                                $num_rows = mysqli_num_rows($result);
                                // var_dump($num_rows);die;
                                $i=$num_rows;
                                if ($num_rows > 0) {
                                while ( $row = mysqli_fetch_array($result) ) {
                                    if ($i>=($num_rows-$max_show)) {
                                    if ($i%2) {
                                        $def_bg_color = "#33FFFF";
                                    } else {
                                        $def_bg_color = "white";
                                        }
                                        $layout .= "<tr align=\"center\" id=\"row-cms\" style=\"cursor:pointer;\">";
                                        $fid[$i] = $row['fid'];
                                        $tr_time[$i] = $row['ftr_time']; $pos_sp = strpos($tr_time[$i]," ");
                                        $tr_time[$i] = substr($tr_time[$i],($pos_sp+1),(strlen($tr_time[$i])-$pos_sp-4));
                                        $furnace[$i] = substr($row['ffurnace'],2,(strlen($row['ffurnace'])-2));
                                        $samples[$i] = $row['fsample'];
                                        $laddle[$i] = $row['fladdle'];
                                        $spektro[$i] = $row['fspektro'];
                                        $kodeloe[$i] = $row['fsample'];
                                        $spek[$i] = $row['fspektro'];
                                        // var_dump($spek[$i]);die; 

                                        $idx_data_next = strpos($spektro[$i],"#"); //int(8) #=8 :=6
                                        // echo $spektro[$i];
                                        
                                        $data_element[0] = "";
                                        $data_value[0] = "";
                                        $data_status[0] = "";
                                        $j=1;
                                        while ($idx_data_next > 0) {
                                        $data = substr($spektro[$i],$idx_data_last,($idx_data_next-$idx_data_last)); //string(8) "C=3.35:0"
                                        $pos_eq = strpos($data,"="); //int(3)
                                        $pos_dz = strpos($data,":"); //int(10)
                                        $data_element[$j] = substr($data,0,$pos_eq); //element unsur
                                        $data_value[$j] = substr($data,($pos_eq+1),($pos_dz-$pos_eq-1)); //value hasil cms
                                        $data_status[$j] = substr($data,($pos_dz+1),(strlen($data)-$pos_dz)); //status 0 belakang
                                            $cms =  $data_element[$j]."=".$data_value[$j];
                                            $spektro[$i] = substr($spektro[$i],($idx_data_next + 1),(strlen($spektro[$i])-$idx_data_next));
                                            $idx_data_next = strpos($spektro[$i],"#");
                                        if ((empty($idx_data_next))) {
                                            $idx_data_next = strlen($spektro[$i]);
                                        }
                                        $j++;
                                        }
                                        $spektro_data = str_replace('#','-',$spek[$i]);
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" align=\"center\" ><span class=\"tablecell\" >".($i)."</span></td>";
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"8%\" align=\"center\" ><span class=\"tablecell\" >".$tr_time[$i]."</span></td>";
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" ><span class=\"tablecell\" >".$furnace[$i]."</span></td>";
                                        
                                        $num_rows1 = 0;
                                        $query1 = "SELECT fcode FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}'";
                                        
                                        $query_count = mysqli_query($conn_composition, "SELECT COUNT(fcode) fcode FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}'");
                                        // var_dump($query_count->num_rows, mysqli_query($conn_composition, $query1));die();
                                        $num_rows1 = $query_count->num_rows;
                                        
                                        // echo $num_rows1."<br/>";
                                        if ($num_rows1 > 0) {
                                            $layout .= "<td class=\"bg-warning\" width=\"10%\" >".$samples[$i]."</td>";
                                        } else {
                                            $layout .= "<td class=\"bg-warning\"  width=\"10%\" >".$samples[$i]."</td>";
                                        }

                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"4%\" ><span class=\"tablecell\" >".$laddle[$i]."</span></td>";

                                        for ($k=0;$k<count($elements);$k++) {
                                            $searchTerm = $elements[$k];
                                            $pos = array_search($searchTerm,$data_element);

                                            $red_min = 0;
                                            $red_max = 0;
                                            $blue_min = 0;
                                            $blue_max = 0;

                                            $sql = "SELECT fid, fcode, felement, fmin, fmax FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}' and felement = '$searchTerm'";
                                    $resultsqlstd = mysqli_query($conn_composition,$sql);
                                    
                                    while($queryku2 = mysqli_fetch_array($resultsqlstd))	
                                    {
                                    $red_min = $queryku2['fmin'];
                                    $red_max = $queryku2['fmax'];
                                    }
                                    
                                    $adasample = 0;
                                    if($red_min != ""){
                                    $adasample = 1; 
                                    }

                                    if ($i%2) {
                                        $def_bg_color = "#33FFFF";
                                    } else {
                                        $def_bg_color = "white";
                                    }
                                    if (empty($pos)) { $disp_value = ""; }
                                    else {
                                        $disp_value = $data_value[$pos];
                                        $disp_status = intval($data_status[$pos]);
                                        switch ($disp_status) {
                                            case 1 :
                                            if ($odd_sec) { $def_bg_color = "red"; }
                                            break;
                                            case 2 :
                                            $def_bg_color = "yellow";
                                            break;
                                        }
                                    }
                                    
                                    $disp_value = $data_value[$pos]; 
                                    if($disp_value < $red_min || $disp_value > $red_max){
                                        $def_bg_color = "red";
                                        
                                    }else{
                                            if ($i%2) {
                                            $def_bg_color = "#33FFFF";
                                            } else {
                                            $def_bg_color = "white";
                                            }
                                    }
                                    
                                    
                                    if($red_max == ""){
                                            if ($i%2) {
                                            $def_bg_color = "#33FFFF";
                                            } else {
                                            $def_bg_color = "white";
                                            }
                                        }else{
                                            $def_bg_color = $def_bg_color;
                                        }


                                        if($adasample == 0){
                                            $def_bg_color = "#FFFF00";
                                        }
                                    if($def_bg_color == "blue" || $def_bg_color == "red"){
                                        $blink = $def_bg_color;
                                    }else{
                                        $blink = $def_bg_color;
                                    }
                                            $layout .= "<td bgcolor=\"".$blink."\" align=\"center\" ><span class=\"tablecell\" >".$disp_value."</span></td>";
                                        }
                                        $layout .= "</tr>";
                                    }
                                    $i--;
                                    }
                                } else {
                                    $colspan = count($elements)+5;
                                    $layout .= "<tr align=\"center\" >";
                                    $layout .= "<td colspan=\"".$colspan."\" align=\"center\" ><span class=\"tablecell\" >No Data on Display</span></td>";
                                    $layout .= "</tr>";
                                }
                                $layout .="</table>";
                                echo $layout;
                                ?>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <h2>Connection Error !</h2>
                        <?php } ?>
                        <?php } ?>
                        
                        <div class="form-group table-responsive mt-2">
                            <table class="table table-sm" id="composition-table">
                                <thead>
                                    <tr class="table-primary">
                                        <th>Standar</th>
                                        <th>No</th>
                                        <th>Time</th>
                                        <th>FM</th>
                                        <th>Sample</th>
                                        <th>Laddle</th>
                                        <th>C</th>
                                        <th>Si</th>
                                        <th>Mn</th>
                                        <th>S</th>
                                        <th>Cu</th>
                                        <th>Sn</th>
                                        <th>Cr</th>
                                        <th>P</th>
                                        <th>Zn</th>
                                        <th>Al</th>
                                        <th>Ti</th>
                                        <th>Mg</th>
                                        <th>Ni</th>
                                        <th>V</th>
                                        <th>Mo</th>
                                        <th>Sb</th>
                                        <th>Fe1</th>
                                        <th>Fe2</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <form action="POST">
                            <div class="row">
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label form-control-sm mb-0">Cari Part </label>
                                    <div class="col-sm-5">
                                        <input class="form-control form-control-sm" type="text" id="PartName" name="PartName" placeholder="Cari Part" data-bs-toggle="modal" data-bs-target="#modal_get_part" style="font-size:12px">
                                        <input class="form-control form-control-sm" type="text" id="ProductCode" name="ProductCode" hidden>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 form-control-label form-control-sm mb-0">Customer Name </label>
                                    <div class="col-sm-5">
                                        <input class="form-control form-control-sm" type="text" id="CustomerName" name="CustomerName" placeholder="Customer Name" style="font-size:12px">
                                        <input class="form-control form-control-sm" type="text" id="CustomerCode" name="CustomerCode" hidden>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control form-control-sm" type="text" id="MaterialType" name="MaterialType" hidden>
                            <input class="form-control form-control-sm" type="text" id="MaterialGrade" name="MaterialGrade" hidden>
                        </form>

                        <!-- Loop Column -->
                        <div class="form-group mt-2 row btn-post-col">
                            <div class="col-lg-12">
                                <button type="submit" name="submitProses" id="submitProses"
                                    class="btn btn-primary float-right" style="font-size : 20px; font-weight: bold;"><i
                                        class="far fa-save"></i>
                                    SIMPAN</button>
                                <button type="submit" name="reset" id="reset" class="btn btn-secondary float-right mr-2"
                                    style="font-size : 20px; font-weight: bold;"><i class="fa fa-undo"
                                        aria-hidden="true"></i>
                                    RESET</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-2">
                <div class="card">
                    <div class="row">
                        <div class="img-square-wrapper img-responsive col-lg-4 col-sm-12">
                            <?php if(!$row_get_trans['Picture']){?>
                                <img class="p-3 pr-0"
                                src="../assets/img/boy.png" width="80"
                                height="95" alt="Card image cap">
                            <?php }else{ ?>
                                <img class="p-3 pr-0"
                                src="data:image;base64, <?= base64_encode($row_get_trans['Picture']) ?>" width="80"
                                height="95" alt="Card image cap">
                            <?php } ?>
                        </div>
                        <div class="card-body col-lg-8 col-sm-12">
                            <p class="card-text" style="font-size:11px; margin-bottom: 2px;"><b>
                                    <?= $row_get_trans['EmployeeName'] ?> </b></p>
                            <p class="card-text" style="font-size:10px; margin-bottom: 2px;">
                                <?= date('d M Y', strtotime($row_get_trans['WorkingDate'])) ?></p>
                            <p class="card-text" style="font-size:10px; margin-bottom: 2px;">
                                <?= $row_get_trans['ShiftName'] ?></p>
                        </div>
                    </div>
                </div>
                <a href="proses-manual.php"
                    class="btn btn-info float-right btn-lg btn-block"
                    style="font-size : 18px; font-weight: bold;"><i
                        class="far fa-edit"></i> Buat Manual</a>
                <button type="submit" name="finishInspection" id="finishInspection"
                    class="btn btn-success float-right btn-lg btn-block"
                    style="font-size : 26px; font-weight: bold; margin-bottom:0.5rem"><i
                        class="far fa-check-square"></i> Finish</button>
            </div>
        </div>

        <!-- Live Monitor -->
        <div class="row">
            <div class="col-12">
                <div class="p-0">
                    <div class="card-header p-1 bg-primary">
                    </div>
                </div>
                <div class="card card-outline-secondary">
                    <div class="card-body">
                        <div class="table-responsive table-responsive-sm">
                            <table id="tbl_close" class="table table-sm table-striped table-hover"
                                style="font-size : 12px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <!-- <th>Session</th> -->
                                        <th>Product Code</th>
                                        <th>Part Name</th>
                                        <th>Area</th>
                                        <th>FM</th>
                                        <th>Sample</th>
                                        <th>Laddle</th>
                                        <th>C</th>
                                        <th>Si</th>
                                        <th>Mn</th>
                                        <th>S</th>
                                        <th>Cu</th>
                                        <th>Sn</th>
                                        <th>Cr</th>
                                        <th>P</th>
                                        <th>Zn</th>
                                        <th>Al</th>
                                        <th>Ti</th>
                                        <th>Mg</th>
                                        <th>Ni</th>
                                        <th>V</th>
                                        <th>Mo</th>
                                        <th>Sb</th>
                                        <th>Fe1</th>
                                        <th>Fe2</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; while($row_data = sqlsrv_fetch_array($stmt_tbl_live, SQLSRV_FETCH_ASSOC))	{ ?>
                                            <tr class="text-center">
                                                <td><?= $i++ ?></td>
                                                <!-- <td><?= $row_data['SessionID']?></td> -->
                                                <td><?= $row_data['ProductCode']?></td>
                                                <td><?= $row_data['PartName']?></td>
                                                <td><?= $row_data['LineName']?></td>
                                                <td><?= $row_data['FurnanceNumber']?></td>
                                                <td><?= $row_data['Sample']?></td>
                                                <td><?= $row_data['Laddle']?></td>
                                                <td><?= $row_data['Comp_CAct']?></td>
                                                <td><?= $row_data['Comp_SiAct']?></td>
                                                <td><?= $row_data['Comp_MnAct']?></td>
                                                <td><?= $row_data['Comp_SAct']?></td>
                                                <td><?= $row_data['Comp_CuAct']?></td>
                                                <td><?= $row_data['Comp_SnAct']?></td>
                                                <td><?= $row_data['Comp_CrAct']?></td>
                                                <td><?= $row_data['Comp_PAct']?></td>
                                                <td><?= $row_data['Comp_ZnAct']?></td>
                                                <td><?= $row_data['Comp_AlAct']?></td>
                                                <td><?= $row_data['Comp_TiAct']?></td>
                                                <td><?= $row_data['Comp_MgAct']?></td>
                                                <td><?= $row_data['Comp_NiAct']?></td>
                                                <td><?= $row_data['Comp_VAct']?></td>
                                                <td><?= $row_data['Comp_MoAct']?></td>
                                                <td><?= $row_data['Comp_SbAct']?></td>
                                                <td><?= $row_data['Comp_Fe1']?></td>
                                                <td><?= $row_data['Comp_Fe2']?></td>
                                                    <?php
                                                        if ($row_data['CF_Active']){
                                                            echo '<td class="bg-success">Finish</td>';
                                                        }else{
                                                            echo '<td class="bg-info">Process</td>';
                                                        }
                                                    ?>
                                        </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script type="text/javascript">
    $('.mdl-get').click(function () {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: 'action/update_state_cms.php',
            data: {
                id
            },
            success: function (data) {
                console.log(data)
            }
        })
    });

    arr_composition = []
    $('#table-cms').on('click', 'tr', function () {
        var row = jQuery(this).closest("tr");
        let td = $(this).closest('tr').find('td');
        let Obj = {
            arr : []
        }
        result = []
        
        for(var i in td.get())
        result.push([td.get(i).innerText])
        
        Obj.arr.push(result)
        arr_composition.push(Obj.arr)

        console.log(arr_composition);
        blank_html = ''
        $.each(Obj.arr, function (key, value) {
            // console.log(value, value[0])
            val = {
                No          : value[0],
                Time        : value[1],
                FM          : value[2],
                Sample      : value[3],
                Laddle      : value[4],
                Comp_CAct   : value[5],
                Comp_SiAct  : value[6],
                Comp_MnAct  : value[7],
                Comp_SAct   : value[8],
                Comp_CuAct  : value[9],
                Comp_SnAct  : value[10],
                Comp_CrAct  : value[11],
                Comp_PAct   : value[12],
                Comp_ZnAct  : value[13],
                Comp_AlAct  : value[14],
                Comp_TiAct  : value[15],
                Comp_MgAct  : value[16],
                Comp_NiAct  : value[17],
                Comp_VAct   : value[18],
                Comp_MoAct  : value[19],
                Comp_SbAct  : value[20],
                Fe1         : value[21],
                Fe2         : value[22]
            };
                    blank_html += '<tr class="text-center blank-row" id="blank_row">' +
                        '<th>Inspection</th>' +
                        '<td id="No_CMS">'   +  val.No  + '</td>' +
                        '<td id="Time_CMS">'   +  val.Time  + '</td>' +
                        '<td id="FM">'   +  val.FM  + '</td>' +
                        '<td id="Sample">'   +  val.Sample  + '</td>' +
                        '<td id="Laddle">'   +  val.Laddle  + '</td>' +
                        '<td id="Comp_CAct">'   +  val.Comp_CAct  + '</td>' +
                        '<td id="Comp_SiAct">'  +  val.Comp_SiAct + '</td>' +
                        '<td id="Comp_MnAct">'  +  val.Comp_MnAct + '</td>' +
                        '<td id="Comp_SAct">'   +  val.Comp_SAct  + '</td>' +
                        '<td id="Comp_CuAct">'  +  val.Comp_CuAct + '</td>' +
                        '<td id="Comp_SnAct">'  +  val.Comp_SnAct + '</td>' +
                        '<td id="Comp_CrAct">'  +  val.Comp_CrAct + '</td>' +
                        '<td id="Comp_PAct">'   +  val.Comp_PAct  + '</td>' +
                        '<td id="Comp_ZnAct">'  +  val.Comp_ZnAct + '</td>' +
                        '<td id="Comp_AlAct">'  +  val.Comp_AlAct + '</td>' +
                        '<td id="Comp_TiAct">'  +  val.Comp_TiAct + '</td>' +
                        '<td id="Comp_MgAct">'  +  val.Comp_MgAct + '</td>' +
                        '<td id="Comp_NiAct">'  +  val.Comp_NiAct + '</td>' +
                        '<td id="Comp_VAct">'   +  val.Comp_VAct  + '</td>' +
                        '<td id="Comp_MoAct">'  +  val.Comp_MoAct + '</td>' +
                        '<td id="Comp_SbAct">'  +  val.Comp_SbAct + '</td>' +
                        '<td id="Comp_Fe1">'  +  val.Fe1 + '</td>' +
                        '<td id="Comp_Fe2">'  +  val.Fe2 + '</td>' +
                        '</tr>';
                })
                $('#composition-table tr:last').after(blank_html).fadeIn("slow");
    });

        // Fungsi Submit
    $("#submitProses").click(function (e) {
        e.preventDefault();
        Composition = {
            No_CMS:     $('#No_CMS').text() ? $('#No_CMS').text() : 0,
            Time_CMS:   $('#Time_CMS').text() ? $('#Time_CMS').text() : 0,
            FM:         $('#FM').text() ? $('#FM').text() : 0,
            Sample:     $('#Sample').text() ? $('#Sample').text() : '',
            Laddle:     $('#Laddle').text() ? $('#Laddle').text() : 0,
            Comp_CAct: $('#Comp_CAct').text() ? $('#Comp_CAct').text() : 0,
            Comp_SiAct: $('#Comp_SiAct').text() ? $('#Comp_SiAct').text() : 0,
            Comp_MnAct: $('#Comp_MnAct').text() ? $('#Comp_MnAct').text() : 0,
            Comp_SAct: $('#Comp_SAct').text() ? $('#Comp_SAct').text() : 0,
            Comp_CuAct: $('#Comp_CuAct').text() ? $('#Comp_CuAct').text() : 0,
            Comp_SnAct: $('#Comp_SnAct').text() ? $('#Comp_SnAct').text() : 0,
            Comp_CrAct: $('#Comp_CrAct').text() ? $('#Comp_CrAct').text() : 0,
            Comp_PAct: $('#Comp_PAct').text() ? $('#Comp_PAct').text() : 0,
            Comp_ZnAct: $('#Comp_ZnAct').text() ? $('#Comp_ZnAct').text() : 0,
            Comp_AlAct: $('#Comp_AlAct').text() ? $('#Comp_AlAct').text() : 0,
            Comp_TiAct: $('#Comp_TiAct').text() ? $('#Comp_TiAct').text() : 0,
            Comp_MgAct: $('#Comp_MgAct').text() ? $('#Comp_MgAct').text() : 0,
            Comp_NiAct: $('#Comp_NiAct').text() ? $('#Comp_NiAct').text() : 0,
            Comp_VAct: $('#Comp_VAct').text() ? $('#Comp_VAct').text() : 0,
            Comp_MoAct: $('#Comp_MoAct').text() ? $('#Comp_MoAct').text() : 0,
            Comp_SbAct: $('#Comp_SbAct').text() ? $('#Comp_SbAct').text() : 0,
            Comp_Fe1: $('#Comp_Fe1').text() ? $('#Comp_Fe1').text() : 0,
            Comp_Fe2: $('#Comp_Fe2').text() ? $('#Comp_Fe2').text() : 0,
        }

        Product = {
            ProductCode     : $('#ProductCode').val(),
            PartName        : $('#PartName').val(),
            CustomerName    : $('#CustomerName').val(),
            CustomerCode    : $('#CustomerCode').val(),
            MaterialType    : $('#MaterialType').val(),
            MaterialGrade    : $('#MaterialGrade').val(),
        }

        shiftName = "<?= $shiftName ?>";
        workdate_get = "<?= $workdate_get ?>";
        transid = "<?= $TransID ?>";
        area = "<?= $AreaName ?>";
        
        var is_row = $('#composition-table .blank-row').length;
        if (is_row == 0) {
            $('.err-search').css('display', 'block');
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                width: 300,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'error',
                title: 'Composition Harus diisi!'
            })
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: 'action/post_proses_up.php',
                data: {
                    transid: transid,
                    shiftName: shiftName,
                    workdate_get: workdate_get,
                    area: area,
                    composition: arr_composition,
                    compositionOne: Composition,
                    product: Product,
                },
                success: function (data) {
                    console.log(data)
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        text: data,
                        showConfirmButton: true,
                        closeOnClickOutside: true,
                        allowOutsideClick: true

                    }).then(okay => {
                        if (okay) {
                            $('.fields-column').html('');
                            window.location.reload();
                        }
                    });
                },
                error: function (data) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'error',
                        text: 'ERROR' + data,
                        showConfirmButton: true,
                        closeOnClickOutside: false,
                        allowOutsideClick: false

                    }).then(okay => {
                        if (okay) {
                            location.reload();
                        }
                    });
                }
            });
        }
    });

    $('#tbl_part tbody').on('click', 'tr', function () {
        $('#ProductCode').text('');
        $('#CustomerCode').text('');
        var row = jQuery(this).closest("tr");
        ProductCode = row.data('prdcode');
        AliasName = row.data('alias');
        CustomerCode = row.data('cuscode');
        CustomerName = row.data('cusname');
        MaterialType = row.data('type');
        MaterialGrade = row.data('grade');

        $('#ProductCode').val(ProductCode)
        $('#PartName').val(AliasName)
        $('#CustomerName').val(CustomerName)
        $('#CustomerCode').val(CustomerCode)
        $('#MaterialType').val(MaterialType)
        $('#MaterialGrade').val(MaterialGrade)

        $("#modal_get_part .btn-close").click();
    });

    $("#reset").click(function (e) {
        $('#composition-table .blank-row').html('');
        $('#PartName').val('')
        $('#ProductCode').val('')
        $('#CustomerName').val('')
        $('#CustomerCode').val('')
        arr_composition = [];
    });

    $("#finishInspection").click(function (e) {
        shiftName = "<?= $shiftName ?>";
        workdate_get = "<?= $workdate_get ?>";
        transid = "<?= $TransID ?>";
        operatorID = "<?= $operatorID ?>";
        leaderID = "<?= $leaderID ?>";
        foremanID = "<?= $foremanID ?>";
        $.ajax({
            type: "POST",
            url: 'action/finish_proses.php',
            data: {
                shiftName,
                workdate_get,
                transid,
                operatorID,
                leaderID,
                foremanID
            },
            success: function (data) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'success',
                    text: data,
                    showConfirmButton: true,
                    closeOnClickOutside: true,
                    allowOutsideClick: true
                }).then(okay => {
                    if (okay) {
                        window.location.href = 'start.php';
                    }
                });
            },
            error: function (data) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    text: 'ERROR' + data,
                    showConfirmButton: true,
                    closeOnClickOutside: false,
                    allowOutsideClick: false
                }).then(okay => {
                    if (okay) {
                        location.reload();
                    }
                });
            }
        });
    });
























    function get_detail(TransID, ProductCode) {
        $.ajax({
            type: "POST",
            url: 'action/get_report_session.php',
            data: {
                TransID,
                ProductCode
            },
            success: function (data) {
                console.log(data)
            }
        })
    }

    $('#tbl_close').DataTable({
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging: true,
        scrollX: false,
        scrollCollapse: false,
        fixedColumns: true,
        lengthChange: false,
        responsive: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    $('#tbl_casting').DataTable({
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging: true,
        scrollX: false,
        scrollCollapse: false,
        fixedColumns: true,
        lengthChange: false,
        responsive: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    tbl_part = $('#tbl_part').DataTable({
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging: true,
        scrollX: false,
        scrollCollapse: false,
        fixedColumns: true,
        lengthChange: false,
        responsive: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    $('body').on('keydown', 'input, select', function (e) {
        if (e.key === "Enter") {
            var self = $(this),
                form = self.parents('form:eq(0)'),
                focusable, next;
            focusable = form.find('input,a,select,button,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this) + 1);
            if (next.length) {
                next.focus();
            } else {
                form.submit();
            }
            return false;
        }
    });
</script>