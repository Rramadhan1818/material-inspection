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
        .table thead th{
            border-bottom: 0;
            vertical-align: middle;
        }
        table {
            font-size:12px;
            margin-bottom:0px !important;
        }

        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 1rem;
        }

        .error {
            color: red ;
        }

            table.dataTable.table-sm>thead>tr>th:not(.sorting_disabled) {
            padding-right: 0px;
            /* padding-left: 0px; */
        }
        
        button.dt-button, div.dt-button, a.dt-button, input.dt-button {
            border-radius: 0px !important;
            color: white;
            background-color: #007bff;
            padding: 7px;
        }

        button.dt-button:hover:not(.disabled), div.dt-button:hover:not(.disabled), a.dt-button:hover:not(.disabled), input.dt-button:hover:not(.disabled) {
            border: 0px solid #f8f8f8;
            background-color: #343a40;
        }
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0.85em;
            padding-left: 1.1rem;
            font-size:12px !important;
        }

        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 1rem;
        }

        .form-group {
            margin-bottom : 0.5rem
        }

        body {
            margin: 0;
            height: 100%;
            overflow-x: hidden
        }

        .custom-select2 {
            display: inline-block;
            width: 100%;
            padding: 0.375rem 1.75rem 0.375rem 0.75rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            vertical-align: middle;
            background: #fff url(data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E) right 0.75rem center/8px 10px no-repeat;
            /* border: 1px solid #ced4da; */
            /* border-radius: 0.25rem; */
            /* box-shadow: inset 0 1px 2px rgb(0 0 0 / 8%); */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
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
    $stmt_trans = sqlsrv_query($conn, $sql_trans);
    $row_get_trans = sqlsrv_fetch_array($stmt_trans, SQLSRV_FETCH_ASSOC);

    //GET DATA TABLE
    $get_table_live = "SELECT 
    *
    FROM [$databaseName].[dbo].[Inspection_lines] LN
    LEFT OUTER JOIN [$databaseName].[dbo].[Inspection_trans] TR ON TR.[TransID] = LN.[TransID] ";
    $stmt_tbl_live = sqlsrv_query($conn, $get_table_live);

?>
</head>


<style>
/* Modal Img */

.img-preview {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.img-preview:hover {opacity: 0.7;}

/* The Modal (background) */
.modal-preview-img {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 100; /* Sit on top */
  padding: 70px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content-preview {
  margin: auto;
  width: 80%;
  max-width: 500px;
}

/* Add Animation */
.modal-content-preview, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close-modal-preview {
  position: absolute;
  top: 52px;
  right: 250px;
  color: #FFF;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close-modal-preview:hover,
.close-modal-preview:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
/* @media only screen and (max-width: 700px){
  .modal-content-preview {
    width: 100%;
  }
} */
</style>
<body>
    <?php require "ui/navbar.php"; ?>
    <?php require "modal/mdl_get_composition.php"; ?>
    <script type="text/javascript" src="ui/action.js"></script> 
    
    <center><div id="loading" class="pt-2 pb-2"><div class="spinner-border text-primary text-center" role="status"></div> Loading ...</div></center>
    <div class="container-custom">
        <div class="row">
            <div class="col-10">
                <div class="p-0">
                    <div class="card-header p-1 bg-primary">
                    </div>
                </div>
                <div class="card card-outline-secondary">
                    <div class="card-body">
                        <form autocomplete="off" method="POST" class="form" id="formPostProses">
                            <div class="row">
                                <input name="SessionID" id="SessionID" type="text" hidden>
                                <input name="FurnanceNumber" id="FurnanceNumber" type="text" hidden>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label form-control-sm mb-0">Cari Composition </label>
                                        <div class="col-sm-8">
                                            <input class="form-control form-control-sm" type="text" id="searchData" placeholder="Cari Compotition" data-bs-toggle="modal" data-bs-target="#modal_casting" style="font-size:12px">
                                            <span class="err-search" style="display: none;color: red;"><small>Silahkan pilih composition !</small></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label form-control-sm mb-0">Cari Part </label>
                                        <div class="col-sm-8">
                                            <input class="form-control form-control-sm" type="text" id="PartName" name="PartName" placeholder="Cari Part" style="font-size:12px" readonly>
                                            <input class="form-control form-control-sm" type="text" id="ProductCode" name="ProductCode" hidden>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label form-control-sm mb-0">Customer Name </label>
                                        <div class="col-sm-8">
                                            <input class="form-control form-control-sm" type="text" id="CustomerName" name="CustomerName" placeholder="Customer Name" style="font-size:12px">
                                            <input class="form-control form-control-sm" type="text" id="CustomerCode" name="CustomerCode" hidden>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label form-control-sm mb-0"> Casting Date</label>
                                        <div class="col-sm-8">
                                            <input class="form-control form-control-sm" name="DateCode" id="DateCode" type="text" placeholder="Casting Date" style="font-size:12px;" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label form-control-sm mb-0"> Cavity No</label>
                                        <div class="col-sm-8">
                                            <input class="form-control form-control-sm" name="CavityNo" id="CavityNo" type="number" placeholder="0" style="font-size:12px">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group table-responsive mt-2">
                                <table class="table table-sm" id="compotition-table">
                                    <thead>
                                        <tr class="table-primary">
                                            <th rowspan="2">Standar</th>
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
                                        <tr>
                                            <td>3.50-4.20</td>
                                            <td>2.00-3.30</td>
                                            <td>0.80 MAX</td>
                                            <td>0.1 MAX</td>
                                            <td>0.020 MAX</td>
                                            <td>0.02-0.06</td>
                                            <td>-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <!-- <td rowspan="2"></td> -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <thead>
                                        <tr class="table-primary">
                                            <th width="25%">Item Check</th>
                                            <th id="HardnessTitle">Hardness</th>
                                        </tr>
                                        <tr>
                                            <th>Standard</th>
                                            <td id="StdHardness"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <form autocomplete="off" method="POST" class="formPostHardness" id="formPostHardness">
                                                    <div class="form-group">
                                                            <!-- <label class="col-form-label col-form-label-sm form-control-label"> Hardness Brinell ( HB )</label> -->
                                                        <input class="form-control form-control-sm mt-2" name="Hardness_Vickers1" id="Hardness_Vickers1" type="number" placeholder="Check 1">
                                                        <input class="form-control form-control-sm mt-2" name="Hardness_Vickers2" id="Hardness_Vickers2" type="number" placeholder="Check 2">
                                                        <input class="form-control form-control-sm mt-2" name="Hardness_Vickers3" id="Hardness_Vickers3" type="number" placeholder="Check 3">
                                                        <input class="form-control form-control-sm mt-2" name="Hardness_Vickers4" id="Hardness_Vickers4" type="number" placeholder="Check 4">
                                                        <input class="form-control form-control-sm mt-2 mb-2" name="Hardness_Vickers5" id="Hardness_Vickers5" type="number" placeholder="Check 5">
                                                        <div id="list_hardness"></div>
                                                        <div align="right">
                                                            <button type="button" name="add" id="add" class="btn btn-success btn-sm">+</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            <!-- <td>
                                                <div class="form-group">
                                                    <input class="form-control form-control-sm mt-2" name="Nodularity" id="Nodularity" type="number" placeholder="75 - 100%">
                                                </div>
                                            </td>
                                            <td>
                                                <input class="form-control form-control-sm mt-2" name="Pearlite" id="Pearlite" type="number" placeholder="5 - 40">
                                            </td> -->
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group table-responsive">
                                <table class="table table-sm" id="compotition-table">
                                    <thead>
                                        <tr class="table-primary">
                                            <th>Upload Microstructure photograph</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td>
                                            <input type='file' id="image1" name="image1" class="form-control form-control-sm mt-2" onchange="readURL(this, '1');" />
                                            <input type='file' id="image2" name="image2" class="form-control form-control-sm mt-2" onchange="readURL(this, '2');" />
                                            <input type='file' id="image3" name="image3" class="form-control form-control-sm mt-2" onchange="readURL(this, '3');" />
                                            <input type='file' id="image4" name="image4" class="form-control form-control-sm mt-2" onchange="readURL(this, '4');" />
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                                <form  enctype="multipart/form-data" method="POST" class="formPostPicture" id="formPostPicture">
                                    <!-- <label class="col-form-label col-form-label-sm form-control-label"> Upload Microstructure photograph</label> -->
                                    <!-- <input type='file' id="image1" name="image1" class="form-control form-control-sm mt-2" onchange="readURL(this, '1');" />
                                    <input type='file' id="image2" name="image2" class="form-control form-control-sm mt-2" onchange="readURL(this, '2');" />
                                    <input type='file' id="image3" name="image3" class="form-control form-control-sm mt-2" onchange="readURL(this, '3');" />
                                    <input type='file' id="image4" name="image4" class="form-control form-control-sm mt-2" onchange="readURL(this, '4');" /> -->
                                    <label class="col-form-label col-form-label-sm form-control-label"> Preview Microstructure photograph 100X</label>
                                    <div class="d-block">
                                        <img id="preview1" name="img-preview" class="mt-2 img-preview" src="../assets/img/180.png" width="115" height="115" alt="your image" />
                                        <img id="preview2" name="img-preview" class="mt-2 img-preview" src="../assets/img/180.png" width="115" height="115" alt="your image" />
                                        <img id="preview3" name="img-preview" class="mt-2 img-preview" src="../assets/img/180.png" width="115" height="115" alt="your image" />
                                        <img id="preview4" name="img-preview" class="mt-2 img-preview" src="../assets/img/180.png" width="115" height="115" alt="your image" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="form-group table-responsive mt-4">
                            <table class="table table-sm">
                                <thead>
                                    <tr class="table-primary">
                                        <th class="StdMatrix">Matrix Structure</th>
                                        <th class="StdGraphite">Graphite Type</th>
                                        <th class="StdNodularity">Nodularity</th>
                                        <th class="StdFerlite">Ferlite (%)</th>
                                        <th class="StdPearlite">Pearlite (%)</th>
                                    </tr>
                                    <tr>
                                        <td class="StdMatrix" id="StdMatrix"></td>
                                        <td class="StdGraphite" id="StdGraphite"></td>
                                        <td class="StdNodularity" id="StdNodularity"></td>
                                        <td class="StdFerlite" id="StdFerlite"></td>
                                        <td class="StdPearlite" id="StdPearlite"></td>
                                    </tr>
                                    <tr>
                                        <td class="StdMatrix">
                                            <select class="form-control form-control-sm mt-2 custom-select2" name="MatrixStructure" id="MatrixStructure">
                                                <option value=" ">-- Pilih Matrix --</option>
                                                <option value="FL">Ferlite</option>
                                                <option value="PL">Pearlite</option>
                                            </select>
                                        </td>
                                        <td class="StdGraphite">
                                            <input class="form-control form-control-sm mt-2" name="Graphite_Type" id="Graphite_Type" type="text" maxLength="1" placeholder="A - C" oninput="this.value = this.value.toUpperCase()">
                                        </td>
                                        <td class="StdNodularity">
                                            <input class="form-control form-control-sm mt-2" name="Nodularity" id="Nodularity" type="number" placeholder="75 - 100%">
                                        </td>
                                        <td class="StdFerlite">
                                            <input class="form-control form-control-sm mt-2" name="Ferlite" id="Ferlite" type="number" placeholder="25 MAX">
                                        </td>
                                        <td class="StdPearlite">
                                            <input class="form-control form-control-sm mt-2" name="Pearlite" id="Pearlite" type="number" placeholder="5 - 40">
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                        <!-- Loop Column -->
                        <div class="form-group mt-2 row btn-post-col">
                            <div class="col-lg-12">
                                <button type="submit" name="submitProses" id="submitProses" class="btn btn-primary float-right"
                                style="font-size : 20px; font-weight: bold;"><i class="far fa-save"></i>
                                SIMPAN</button>
                                <button type="submit" name="reset" id="reset" class="btn btn-secondary float-right mr-2"
                                    style="font-size : 20px; font-weight: bold;"><i class="fa fa-undo" aria-hidden="true"></i>
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
                <button type="submit" name="finishInspection" id="finishInspection" class="btn btn-success float-right btn-lg btn-block" style="font-size : 26px; font-weight: bold; margin-bottom:0.5rem"><i class="far fa-check-square"></i>  FINISH</button>
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
                                <table id="tbl_close" class="table table-sm table-striped table-hover" style="font-size : 12px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product Code</th>
                                            <th>Part Name</th>
                                            <th>Time</th>
                                            <th>FM</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; while($row_data = sqlsrv_fetch_array($stmt_tbl_live, SQLSRV_FETCH_ASSOC))	{ ?>
                                            <tr class="text-center"
                                            data-transid="<?= $row_data['TransID'] ?>"
                                            data-productcode="<?= $row_data['ProductCode'] ?>"
                                            data-sessionid="<?= $row_data['SessionID'] ?>">
                                                <td><?= $i++ ?></td>
                                                <td><?= $row_data['ProductCode'] ?></td>
                                                <td><?= $row_data['PartName'] ?></td>
                                                <td><?= date('d M Y H:m:i', strtotime($row_data['Created_at'])) ?></td>
                                                <td><?= $row_data['FurnanceNumber'] ?></td>
                                                <td>
                                                    <a href="modal/mdl_detil_report.php" class="btn btn-xs btn-primary" id="btn-detail" data-toggle="modal"  data-target="#modal-detail"><i class="fa fa-eye"></i> Detail</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Report -->
        <div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detailLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content p-0">
                    <div class="modal-header pb-1 pt-2">
                        <h5 class="modal-title" id="modal-detailLabel">Detail Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="report-html">

                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div id="modal-preview-img" class="modal modal-preview-img">
            <span class="close-modal-preview">&times;</span>
                <img class="modal-content modal-content-preview" id="img01">
        </div>

    </div>
</body>
</html>

<script type="text/javascript">
    $("#tbl_casting tbody").on('click', 'tr', function (e) {
        // console.log('cek')
        $('#blank_row').remove();
        $('#blank_row').html('');

        var row = jQuery(this).closest("tr");
        SessionID = row.data('session');
        ProductCode = row.data('prdcode');
        AliasName = row.data('alias');
        CustomerCode = row.data('cuscode');
        CustomerName = row.data('cusname');
        Sample = row.data('sample');

        get_standard(Sample, ProductCode)
        // console.log(SessionID, ProductCode, AliasName, CustomerCode, CustomerName, Sample);
        $('#ProductCodeQC').val(ProductCode)
        $('#PartNameQC').val(AliasName)
        $('#CustomerName').val(CustomerName)
        $('#CustomerCode').val(CustomerCode)

        GetCompotition(SessionID, ProductCode)
    })

    function get_standard(sample, ProductCode){
        $.ajax({
            type: "POST",
            url: 'action/get_standar_proses.php',
            data: {sample, ProductCode},
            success: function (data) {
                hard = data.hard
                nodul = data.nodul
                pearl = data.pearl
                ferli = data.ferli
                graph = data.graph
                matrix = data.matrix

                if(hard !== null){
                    if(hard.ItemCheck == 'HB'){
                        $('#HardnessTitle').html('Hardness Brinell '+ '(' + hard.ItemCheck + ')')
                    }else{
                        $('#HardnessTitle').html('Hardness Vickers '+ '(' + hard.ItemCheck + ')')
                    }

                    $('#StdHardness').html(hard.STDMin+' - '+hard.STDMax)
                    for (let i = 0; i < 5; i++) {
                        $('#Hardness_Vickers'+ i).focusout(function(){
                            if($('#Hardness_Vickers'+ i).val() < hard.STDMin ||  $('#Hardness_Vickers'+ i).val() > hard.STDMax){
                                $('#Hardness_Vickers'+ i).addClass('border-danger');
                            }else{
                                $('#Hardness_Vickers'+ i).removeClass('border-danger');
                            }
                        })
                    }
                    
                }
                
                if(nodul !== null){
                    $('#StdNodularity').html(nodul.STDMin +' '+ nodul.Param)
                    $('#Nodularity').focusout(function(){
                        if($('#Nodularity').val() < nodul.STDMin){
                            $('#Nodularity').addClass('border-danger');
                        }else{
                            $('#Nodularity').removeClass('border-danger');
                        }
                    })
                }else{
                    $('.StdNodularity').attr('hidden', true)
                    $('#Nodularity').attr('readonly', true)
                }

                if(pearl !== null){
                    $('#StdPearlite').html(pearl.STDMin +' - '+ pearl.STDMax)
                    $('#Pearlite').focusout(function(){
                        if($('#Pearlite').val() > pearl.STDMax){
                            $('#Pearlite').addClass('border-danger');
                        }else{
                            $('#Pearlite').removeClass('border-danger');
                        }
                    })
                }else{
                    $('.StdPearlite').attr('hidden', true)
                    $('#Pearlite').attr('readonly', true)
                }

                if(ferli !== null){
                    $('#StdFerlite').html(ferli.STDMax +' '+ ferli.STDMax)     
                }else{
                    $('.StdFerlite').attr('hidden', true)
                    $('#Ferlite').attr('readonly', true)
                }
                
                if(graph !== null){
                    $('#StdGraphite').html(graph.STDMax +' '+ graph.STDMax)     
                }else{
                    $('.StdGraphite').attr('hidden', true)
                    $('#Graphite_Type').attr('readonly', true)
                }

                if(matrix !== null){
                    $('#StdMatrix').html(matrix.STDVal)     
                }else{
                    $('.StdMatrix').attr('hidden', true)
                    $('#MatrixStructure').attr('readonly', true)
                }

            }
        })         
    }

    function GetCompotition(session, product){
        id = session 
        prdcode = product
        blank_html = ''
        $.ajax({
            type: "GET",
            url: 'action/get_column.php',
            data : {
                    SessionID:id, 
                    ProductCode: prdcode
                },
            success: function (data) {
                $.each(data.data, function (key, value) {
                $('#SessionID').val(value.SessionID);
                $('#FurnanceNumber').val(value.FurnanceNumber);
                $('#LinesCode').val(value.LinesCode);
                $('#PartName').val(value.PartName);
                $('#ProductCode').val(value.ProductCode);
                $('#searchData').val(value.Sample);
                blank_html +=  '<tr class="text-center" id="blank_row">'+
                                    '<th>Inspection</th>'+
                                    '<td id="Comp_CAct">'+  value.Comp_CAct +'</td>'+
                                    '<td id="Comp_SiAct">'+ value.Comp_SiAct +'</td>'+
                                    '<td id="Comp_MnAct">'+ value.Comp_MnAct +'</td>'+
                                    '<td id="Comp_SAct">'+  value.Comp_SAct +'</td>'+
                                    '<td id="Comp_CuAct">'+ value.Comp_CuAct +'</td>'+
                                    '<td id="Comp_SnAct">'+ value.Comp_SnAct +'</td>'+
                                    '<td id="Comp_CrAct">'+ value.Comp_CrAct +'</td>'+
                                    '<td id="Comp_PAct">'+  value.Comp_PAct +'</td>'+
                                    '<td id="Comp_ZnAct">'+ value.Comp_ZnAct +'</td>'+
                                    '<td id="Comp_AlAct">'+ value.Comp_AlAct +'</td>'+
                                    '<td id="Comp_TiAct">'+ value.Comp_TiAct +'</td>'+
                                    '<td id="Comp_MgAct">'+ value.Comp_MgAct +'</td>'+
                                    '<td id="Comp_NiAct">'+ value.Comp_NiAct +'</td>'+
                                    '<td id="Comp_VAct">'+  value.Comp_VAct +'</td>'+
                                    '<td id="Comp_MoAct">'+ value.Comp_MoAct +'</td>'+
                                    '<td id="Comp_SbAct">'+ value.Comp_SbAct +'</td>'+
                                    '<td id="Comp_Fe1">'+   value.Comp_Fe1 +'</td>'+
                                    '<td id="Comp_Fe2">'+   value.Comp_Fe2 +'</td>'+
                                '</tr>';
                })
                // console.log(blank_html);
                $('#compotition-table tr:last').after(blank_html);
                $("#modal_casting .btn-close").click();
            },
            error: function (data) {
                Swal.fire({
                    position: 'top-center',
                    icon: 'error',
                    text: 'ERROR' + data.data,
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

    // Detail Report
    $('#tbl_close tbody').on('click', 'tr', function(e) {
        e.preventDefault();
        var row = jQuery(this).closest("tr");
        TransID = row.data('transid');
        ProductCode = row.data('productcode');
        SessionID = row.data('sessionid');

        console.log(TransID, ProductCode, SessionID);
        $.ajax({
            type: "POST",
            url: 'modal/mdl_detail_report.php',
            data: {TransID, ProductCode, SessionID},
            success: function (data) {
                $('#report-html').html(data)
            }
        })
        $('#modal-detail').modal("show");
        // console.log(TransID, ProductCode);
        // get_detail(TransID, ProductCode);
        
    });

    function storeImage(transid, productcode){
        trans = [];
        trans['transid'] = transid
        trans['productcode'] = productcode
        var formData = new FormData();
        formData.append('file1', $('#image1')[0].files[0]);
        formData.append('file2', $('#image2')[0].files[0]);
        formData.append('file3', $('#image3')[0].files[0]);
        formData.append('file4', $('#image4')[0].files[0]);
        formData.append('transid', trans['transid']);
        formData.append('productcode', trans['productcode']);
        
        $.ajax({
                type: "POST",
                url: 'action/post_proses_image.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (data, response, textStatus, jqXHR) {
                    console.log(data);
                },
        });
    }

    // Fungsi Submit
    $("#submitProses").click(function (e) {
        e.preventDefault();

        Hardness = {
            Nodularity          : $('#Nodularity').val(),
            Graphite_Type       : $('#Graphite_Type').val(),
            MatrixStructure     : $('#MatrixStructure').val(),
            Pearlite            : $('#Pearlite').val(),
            Ferlite             : $('#Ferlite').val()
        }

        var form_general = $("#formPostProses");
        var form_hardness = $("#formPostHardness");

        shiftName = "<?= $shiftName ?>";
        workdate_get = "<?= $workdate_get ?>";
        transid = "<?= $TransID ?>";
        
        var is_row = $('#blank_row').length;
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
            transid = "<?= $TransID ?>";
            productcode = $('#ProductCode').val();
            storeImage(transid, productcode);
            $.ajax({
                type: "POST",
                url: 'action/post_proses.php',
                data: {
                    form: form_general.serializeArray(),
                    form_hardness: form_hardness.serializeArray(),
                    transid: transid,
                    shiftName: shiftName,
                    workdate_get: workdate_get,
                    Hardness : Hardness
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







    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview'+ id).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('click', '.remove-field', function(){
		var delete_row = $(this).data("row");
		$('#' + delete_row).remove();
        count = count - 1;
	});

    var count = 5;
    $('#add').click(function(){
        // $('.btn-add-row').css('visibility','visible');
        count = count + 1;
        var html_code = 
        '<div class="input-group mb-2" id="row'+count+'">'+
            '<input class="form-control form-control-sm" name="Hardness_Vickers'+count+'" id="Hardness_Vickers'+count+'" type="number" placeholder="Check '+count+'">'+
            '<div class="input-group-append">'+
                '<button type="button" name="remove" data-row="row'+count+'" class="btn btn-danger btn-sm remove-field">-</button></span>'+
            '</div>'+
        '</div>';
        $('#list_hardness').append(html_code);
    });
    
    $("#reset").click(function (e) {
        $('#blank_html').html('');
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

    $('#tbl_close').DataTable( {
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging	: true,
        scrollX			: false,
        scrollCollapse	: false,
        fixedColumns	: true,
        lengthChange	: false,
        responsive	: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    $('#tbl_casting').DataTable( {
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging	: true,
        scrollX			: false,
        scrollCollapse	: false,
        fixedColumns	: true,
        lengthChange	: false,
        responsive	: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    $('#tbl_part').DataTable( {
        searchBuilder: true,
        dom: 'Blfrtip',
        // "responsive": false,
        buttons: [
            'excel', 'csv', 'pdf', 'print'
        ],
        paging	: true,
        scrollX			: false,
        scrollCollapse	: false,
        fixedColumns	: true,
        lengthChange	: false,
        responsive	: false,
        SearchPlaceholder: "Cari ..",
        Search: '',
        SearchPlaceholder: "Cari Data ..",
    });

    $('body').on('keydown', 'input, select', function(e) {
        if (e.key === "Enter") {
            var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
            focusable = form.find('input,a,select,button,textarea').filter(':visible');
            next = focusable.eq(focusable.index(this)+1);
            if (next.length) {
                next.focus();
            } else {
                form.submit();
            }
            return false;
        }
    });
</script>


<script>
// Get the modal
var modal = document.getElementById("modal-preview-img");
var img1 = document.getElementById("preview1");
var img2 = document.getElementById("preview2");
var img3 = document.getElementById("preview3");
var img4 = document.getElementById("preview4");
var modalImg = document.getElementById("img01");
// var captionText = document.getElementById("caption");
img1.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
//   captionText.innerHTML = this.alt;
}

img2.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
//   captionText.innerHTML = this.alt;
}
img3.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
//   captionText.innerHTML = this.alt;
}
img4.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
//   captionText.innerHTML = this.alt;
}
var span = document.getElementsByClassName("close-modal-preview")[0];

span.onclick = function() { 
  modal.style.display = "none";
}
</script>