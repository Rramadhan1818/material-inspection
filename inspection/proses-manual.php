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
                            <form action="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Cari Part </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="text" id="PartName" name="PartName" placeholder="Cari Part" data-bs-toggle="modal" data-bs-target="#modal_get_part" style="font-size:12px">
                                            <input class="form-control form-control-sm" type="text" id="ProductCode" name="ProductCode" style="font-size:12px" hidden>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Customer Name </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="text" id="CustomerName" name="CustomerName" placeholder="Customer Name" style="font-size:12px">
                                            <input class="form-control form-control-sm" type="text" id="CustomerCode" name="CustomerCode" style="font-size:12px" hidden>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Area </label>
                                        <div class="col-sm-6">
                                            <select class="form-select form-select-sm select-custom" type="text" id="LineName" name="LineName" placeholder="Choose Area" style="font-size:14px">
                                                <option value="DISA">DISA</option>
                                                <option value="ACE">ACE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Time </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="Time" id="Time_CMS" name="Time_CMS" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">FM </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="text" id="FurnanceNumber" name="FurnanceNumber" placeholder="FM Number" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Sample </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="text" id="Sample" name="Sample" placeholder="Sample Name" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label form-control-sm mb-0">Laddle </label>
                                        <div class="col-sm-6">
                                            <input class="form-control form-control-sm" type="text" id="Laddle" name="Laddle" placeholder="Laddle" style="font-size:12px">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">C </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_CAct" name="Comp_CAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Si </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_SiAct" name="Comp_SiAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Mn </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_MnAct" name="Comp_MnAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">S </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_SAct" name="Comp_SAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Cu </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_CuAct" name="Comp_CuAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Sn </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_SnAct" name="Comp_SnAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Cr </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_CrAct" name="Comp_CrAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">P </label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_PAct" name="Comp_PAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Zn</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_ZnAct" name="Comp_ZnAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                        <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Al</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_AlAct" name="Comp_AlAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Ti</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_TiAct" name="Comp_TiAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Mg</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_MgAct" name="Comp_MgAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Ni</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_NiAct" name="Comp_NiAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">V</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_VAct" name="Comp_VAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Mo</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_MoAct" name="Comp_MoAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Sb</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_SbAct" name="Comp_SbAct" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Fe1</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_Fe1" name="Comp_Fe1" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label form-control-sm mb-0">Fe2</label>
                                        <div class="col-sm-9">
                                            <input class="form-control form-control-sm" type="text" id="Comp_Fe2" name="Comp_Fe2" placeholder="0.000" style="font-size:12px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                <a href="proses-up.php"
                    class="btn btn-secondary float-right btn-lg btn-block"
                    style="font-size : 22px; font-weight: bold; margin-bottom:0.5rem"><i
                        class="fa fa-home"></i> Kembali</a>
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
                            <table id="tbl_manual" class="table table-sm table-striped table-hover"
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
    arr_composition = []

        // Fungsi Submit
    $("#submitProses").click(function (e) {
        e.preventDefault();
        Composition = {
            Time_CMS:   $('#Time_CMS').val() ? $('#Time_CMS').val() : 0,
            FM:         $('#FurniceNumber').val() ? $('#FurniceNumber').val() : 0,
            Sample:     $('#Sample').val() ? $('#Sample').val() : '',
            Laddle:     $('#Laddle').val() ? $('#Laddle').val() : 0,
            Comp_CAct: $('#Comp_CAct').val() ? $('#Comp_CAct').val() : 0,
            Comp_SiAct: $('#Comp_SiAct').val() ? $('#Comp_SiAct').val() : 0,
            Comp_MnAct: $('#Comp_MnAct').val() ? $('#Comp_MnAct').val() : 0,
            Comp_SAct: $('#Comp_SAct').val() ? $('#Comp_SAct').val() : 0,
            Comp_CuAct: $('#Comp_CuAct').val() ? $('#Comp_CuAct').val() : 0,
            Comp_SnAct: $('#Comp_SnAct').val() ? $('#Comp_SnAct').val() : 0,
            Comp_CrAct: $('#Comp_CrAct').val() ? $('#Comp_CrAct').val() : 0,
            Comp_PAct: $('#Comp_PAct').val() ? $('#Comp_PAct').val() : 0,
            Comp_ZnAct: $('#Comp_ZnAct').val() ? $('#Comp_ZnAct').val() : 0,
            Comp_AlAct: $('#Comp_AlAct').val() ? $('#Comp_AlAct').val() : 0,
            Comp_TiAct: $('#Comp_TiAct').val() ? $('#Comp_TiAct').val() : 0,
            Comp_MgAct: $('#Comp_MgAct').val() ? $('#Comp_MgAct').val() : 0,
            Comp_NiAct: $('#Comp_NiAct').val() ? $('#Comp_NiAct').val() : 0,
            Comp_VAct: $('#Comp_VAct').val() ? $('#Comp_VAct').val() : 0,
            Comp_MoAct: $('#Comp_MoAct').val() ? $('#Comp_MoAct').val() : 0,
            Comp_SbAct: $('#Comp_SbAct').val() ? $('#Comp_SbAct').val() : 0,
            Comp_Fe1: $('#Comp_Fe1').val() ? $('#Comp_Fe1').val() : 0,
            Comp_Fe2: $('#Comp_Fe2').val() ? $('#Comp_Fe2').val() : 0,
        }

        Product = {
            ProductCode     : $('#ProductCode').val(),
            PartName        : $('#PartName').val(),
            Area            : $('#LineName').val()
        }

        shiftName = "<?= $shiftName ?>";
        workdate_get = "<?= $workdate_get ?>";
        transid = "<?= $TransID ?>";
        
        var prdcode = $('#ProductCode').length;
        if (prdcode == 0) {
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
                title: 'Part Harus diisi!'
            })
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: 'action/post_proses_manual.php',
                data: {
                    transid: transid,
                    shiftName: shiftName,
                    workdate_get: workdate_get,
                    composition: Composition,
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
                            window.location.href = 'proses-up.php';
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


        $('#ProductCode').val(ProductCode)
        $('#PartName').val(AliasName)
        $('#CustomerName').val(CustomerName)
        $('#CustomerCode').val(CustomerCode)

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

    $('#tbl_manual').DataTable({
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