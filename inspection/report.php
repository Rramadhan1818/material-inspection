<!doctype html>
<html lang="en">

<head>
    <?php
	require "conf/dbkoneksi.php";
	require "ui/header.php";
	require "ui/datatable_plugin.php";
	// require "session.php";

	date_default_timezone_set("Asia/Jakarta");
	$workingdate_new = date('Y-m-d H:i');
	$StartTime = date('H:i');
    $workingdate = date('Y-m-d');

    $tgl_pertama = date('Y-m-01', strtotime($workingdate));
    $tgl_terakhir = date('Y-m-t', strtotime($workingdate));

    
        $sql_report = "DECLARE
        @D1 datetime = '$tgl_pertama'
        ,@D2 datetime = '$tgl_terakhir'
        ,@ShiftName varchar(30) = 'ALL'
        ,@AlatUkur varchar(250) = 'DIAL GAUGE'
        ,@StandarID varchar(8) = 'ALL'
        SELECT * FROM [$databaseName].[dbo].[fn_kalibrasi_qa_dialgauge](@D1,@D2,@ShiftName,@AlatUkur,@StandarID)";
        $stmt_report = sqlsrv_query($conn, $sql_report);

?>

<style>
        body {
            background-color: #ddd;
        }

        .container-custom {
            /* max-width: 1250px; */
			max-width: 98%;
            padding-right: 6.5px;
            padding-left: 6.5px;
            margin-right: auto;
            margin-left: auto;
        }

        .card-horizontal {
            display: flex;
            flex: 1 1 auto;
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

        .error {
            color: red ;
        }

        /* TAB PAN */
        .nav-item .active {
            color: #fff !important;
            background-color: #0d6efd !important;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .nav-link {
            display: block;
            padding: 0.5rem 0.5rem;
            font-size : 12px;
        }
        .nav-tabs .nav-link {
                border: 1px solid rgba(0,0,0,.125);
                color : grey;
        }
</style>
</head>

<body>
    <?php require "ui/navbar.php"; ?>
    <center>
        <div id="loading" class="pt-2 pb-2">
            <div class="spinner-border text-primary text-center" role="status"></div> Loading ...
        </div>
    </center>

    <div class="container-custom">
        <!-- <div class="row mb-2">
            <div class="col-auto">
                <button class="btn btn-primary">Dial Gauge</button>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Vernier</button>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Torque</button>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Carbon Analizer</button>
            </div>
        </div> -->
        <div class="row">
            <div class="card bg-warning" style="margin-bottom:0.5rem">
                <div class="card-body">
                <h6 style="font-weight: bold;">REPORT FILTER </h6>
                <form class="px-2 form" id="filter-report" name="filter-report" action="" method="POST">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="inputPassword6" class="col-form-label">Jenis Alat</label>
                        </div>
                        <div class="col-auto">
                            <select class="form-select select" aria-label="Default select example" id="tool_name" name="tool_name">
                                <option value="DIAL GAUGE" <?php if (isset($_POST['tool_name']) && $_POST['tool_name'] == 'DIAL GAUGE') { ?>selected="true" <?php }; ?>>DIAL GAUGE</option>
                                <option value="TORQUE WRENCH" <?php if (isset($_POST['tool_name']) && $_POST['tool_name'] == 'TORQUE WRENCH') { ?>selected="true" <?php }; ?>>TORQUE WRENCH</option>
                                <option value="CARBON ANALYZER" <?php if (isset($_POST['tool_name']) && $_POST['tool_name'] == 'CARBON ANALYZER') { ?>selected="true" <?php }; ?>>CARBON ANALYZER</option>
                                <option value="VERNIER" <?php if (isset($_POST['tool_name']) && $_POST['tool_name'] == 'VERNIER') { ?>selected="true" <?php }; ?>>VERNIER</option>
                            </select>
                        </div>
                        <div class="col-auto stdClass">
                            <select class="form-select" aria-label="Default select example" id="std" name="std">
                                <option value="ALL"  <?php if (isset($_POST['std']) && $_POST['std'] == 'ALL') { ?>selected="true" <?php }; ?>>ALL</option>
                                <option value="STD1" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD1') { ?>selected="true" <?php }; ?>>0.01mm - 0~1 mm</option>
                                <option value="STD2" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD2') { ?>selected="true" <?php }; ?>>0.01mm - 0~0.8 mm</option>
                                <option value="STD3" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD3') { ?>selected="true" <?php }; ?>>0.01mm - 0~10 mm</option>
                                <option value="STD4" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD4') { ?>selected="true" <?php }; ?>>0.1mm - 0~4 mm</option>
                                <option value="STD5" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD5') { ?>selected="true" <?php }; ?>>0.001mm - 0~0.2mm</option>
                                <option value="STD6" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD6') { ?>selected="true" <?php }; ?>>0.001mm - 0~1mm</option>
                                <option value="STD7" <?php if (isset($_POST['std']) && $_POST['std'] == 'STD7') { ?>selected="true" <?php }; ?>>0.001mm - 0~0.1mm</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label for="inputPassword6" class="col-form-label">Range Date</label>
                        </div>
                        <div class="col-5">
                            <input id="date-range" type="text" name="date_range" class="form-control"/>		
                        </div>
                        <div class="col-auto">	
                            <button type="submit" id="submit_report" name="submit_report" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>		
                        </div>
                    </div>
                </form>
                <?php 
                    if(isset($_POST['submit_report'])) {
                        $date_range = '';    
                        $date_start = '';    
                        $date_end = '';  
                        $FuncName = '';  
                        $std = '';    
                        $date_range = $_POST['date_range'];
                        // $date_start = isset($_POST['date_range']) ? explode(' - ', $_POST['date_range'])[0] : '';
                        // $date_end = isset($_POST['date_range']) ? explode(' - ', $_POST['date_range'])[1] : '';
                        $date_start = explode(' - ', $_POST['date_range'])[0];
                        $date_end = explode(' - ', $_POST['date_range'])[1];
                        $tool = $_POST['tool_name'];
                        $std = $_POST['std'];

                        if($tool == 'DIAL GAUGE'){
                            $FuncName = 'fn_kalibrasi_qa_dialgauge';
                        }else if($tool == 'TORQUE WRENCH'){
                            $FuncName = 'fn_kalibrasi_qa_torque';
                        }else if($tool == 'CARBON ANALYZER'){
                            $FuncName = 'fn_kalibrasi_qa_carbon';
                        }else if($tool == 'VERNIER'){
                            $FuncName = 'fn_kalibrasi_qa_vernier';
                        }
                            $sql_filter = "DECLARE
                                            @D1 datetime = '$date_start'
                                            ,@D2 datetime = '$date_end'
                                            ,@ShiftName varchar(30) = 'ALL'
                                            ,@AlatUkur varchar(250) = '$tool'
                                            ,@StandarID varchar(8) = '$std'
                                            SELECT * FROM [$databaseName].[dbo].[$FuncName](@D1,@D2,@ShiftName,@AlatUkur,@StandarID)";
                            // var_dump($sql_filter);               
                            $stmt_report = sqlsrv_query($conn, $sql_filter);
                    }
                ?>
            </div>    
            </div>    
        </div>
        <div class="row">
            <div class="p-0">
                <div class="card-header p-1 bg-primary">
                </div>
            </div>
            <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-responsive-sm">
                                <h6 style="font-weight: bold;">DATA <?php if(isset($_POST['tool_name'])){ echo $_POST['tool_name']; }else { echo 'DIAL GAUGE'; } ?></h6>
                                <?php if(isset($_POST['std'])){ ?>
                                    <center style="font-weight: bold;"><?= date('d F Y', strtotime( $date_start ?? '')) .' - '. date('d F Y', strtotime( $date_end ?? '')) ?></center>
                                <?php } ?>
                                <?php if(isset($_POST['tool_name']) && $_POST['tool_name'] == 'TORQUE WRENCH' ){ ?>
                                    <table id="tbl_report" class="table table-sm table-striped" style=" font-size : 9px;" width="100%">
                                            <thead>
                                                <tr>
                                                <th rowspan="3">No</th>							
                                                <th rowspan="3">Identification Number</th>							
                                                <th rowspan="3">Kalibrasi Ke</th>							
                                                <th rowspan="3">Serial No.</th>							
                                                <th rowspan="3">Skala (fcm)</th>							
                                                <th rowspan="3">Nilai Penggunaan</th>							
                                                <th rowspan="3">Angka Setting (N.m)</th>							
                                                <th rowspan="3">Suhu kalibrasi (°C)</th>							
                                                <th rowspan="3">Kelembaban kalibrasi  (%)</th>							
                                                <th rowspan="3">Kondisi Visual alat ukur</th>	
                                            </tr>
                                            <tr>						
                                                <th colspan="8" class="text-center">Hasil Kalibrasi (N.m)</th>							
                                            </tr>
                                            <tr>
                                                <th class="text-center">N1</th>							
                                                <th class="text-center">N2</th>							
                                                <th class="text-center">N3</th>							
                                                <th class="text-center">N4</th>							
                                                <th class="text-center">N5</th>							
                                                <th class="text-center">AVG</th>							
                                                <th class="text-center">Max Penyimpangan</th>							
                                                <th class="text-center">Hasil</th>							
                                                <th >Keputusan Hasil Kalibrasi</th>							
                                                <th >Tgl Kalibrasi</th>							
                                                <th >Keterangan</th>							
                                                <th >Kalibrasi Selanjutnya</th>							
                                            </tr>
                                            </thead>		
                                        <tbody>
                                            <?php $i = 1; while($rowtool = sqlsrv_fetch_array($stmt_report, SQLSRV_FETCH_ASSOC))     { ?>
                                                <?php if($rowtool){ ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= $rowtool['IdentificationNumber'] ?></td>
                                                    <td><?= $rowtool['KALIBRASI KE'] ?></td>
                                                    <td><?= $rowtool['SERIAL NUMBER'] ?></td>
                                                    <td><?= $rowtool['RANGE'] ?></td>
                                                    <td><?= $rowtool['NILAI PENGGUNAAN'] ?></td>
                                                    <td><?= $rowtool['ANGKA SETTING (N.m)'] ?></td>
                                                    <td><?= $rowtool['SUHU KALIBRASI (C)'] ?></td>
                                                    <td><?= $rowtool['KELEMBABAN KALIBRASI (%)'] ?></td>
                                                    <td><?= $rowtool['KONDISI ALAT'] ?></td>
                                                    <td><?= $rowtool['N1_Torque'] ?></td>
                                                    <td><?= $rowtool['N2_Torque'] ?></td>
                                                    <td><?= $rowtool['N3_Torque'] ?></td>
                                                    <td><?= $rowtool['N4_Torque'] ?></td>
                                                    <td><?= $rowtool['N5_Torque'] ?></td>
                                                    <td><?= $rowtool['AVG'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpangan'] . '%' ?></td>
                                                    <td class="<?php echo ($rowtool['Hasil'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['Hasil'] ?></td>
                                                    <td class="<?php echo ($rowtool['KeputusanHasil'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasil'] ?></td>
                                                    <td><?= date('d M Y', strtotime($rowtool['TanggalKalibrasi'])) ?></td>
                                                    <td><?= $rowtool['KETERANGAN'] ?></td>
                                                    <td><?= date('d M Y', strtotime($rowtool['NextKalibrasi']))  ?></td>
                                                </tr>
                                                <?php }else {  echo 'Data Belum Ada'; } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="row ml-2">
                                        <h6 style="font-weight:bold">Penentuan hasil ukur :</h6>
                                        <p style="font-size:10px">Hasil pengukuran OK, jika rata-rata hasil ukur tidak melebihi dari 3% dari setting skala	</p>

                                    <h6 style="font-weight:bold">Item Pemeriksaan Visual :</h6>
                                        <p style="font-size:10px">*) Adaptor, handle, clamp rotary, rotary adaptor, clamp skala = Dalam kondisi baik : OK</p>
                                    </div>
                                <?php }else if(isset($_POST['tool_name']) && $_POST['tool_name'] == 'CARBON ANALYZER' ){ ?>
                                    <table id="tbl_report" class="table table-sm table-striped" style=" font-size : 9px;" width="100%">
                                            <thead>
                                                <tr>
                                                    <th rowspan="3">No</th>							
                                                    <th rowspan="3">Identification Number</th>							
                                                    <th rowspan="3">Kalibrasi Ke</th>							
                                                    <th rowspan="3">Serial No.</th>							
                                                    <th rowspan="3">Range (+%)</th>							
                                                    <th rowspan="3">Tolerance (+%)</th>							
                                                    <th rowspan="3">Suhu kalibrasi (°C)</th>							
                                                    <th rowspan="3">Kelembaban kalibrasi  (%)</th>							
                                                    <th rowspan="3">Kondisi Visual alat ukur</th>	
                                                </tr>
                                                <tr>						
                                                    <th colspan="6" class="text-center">Kalibrasi Master (3.203%)</th>							
                                                </tr>
                                                <tr>
                                                    <th class="text-center">N1</th>							
                                                    <th class="text-center">N2</th>							
                                                    <th class="text-center">N3</th>							
                                                    <th class="text-center">Min Penyimpangan</th>							
                                                    <th class="text-center">Max Penyimpangan</th>							
                                                    <th >Keputusan Hasil Kalibrasi</th>							
                                                    <th >Tgl Kalibrasi</th>							
                                                    <th >Keterangan</th>							
                                                    <th >Kalibrasi Selanjutnya</th>							
                                                </tr>
                                            </thead>		
                                            <tbody>
                                                <?php $i = 1; while($rowtool = sqlsrv_fetch_array($stmt_report, SQLSRV_FETCH_ASSOC))     { ?>
                                                    <?php if($rowtool){ ?>
                                                    <tr>
                                                        <td><?= $i++ ?></td>
                                                        <td><?= $rowtool['IdentificationNumber'] ?></td>
                                                        <td><?= $rowtool['KALIBRASI KE'] ?></td>
                                                        <td><?= $rowtool['SERIAL NUMBER'] ?></td>
                                                        <td><?= $rowtool['RANGE'] ?></td>
                                                        <td><?= $rowtool['TOLERANCE'] ?></td>
                                                        <td><?= $rowtool['SUHU KALIBRASI (C)'] ?></td>
                                                        <td><?= $rowtool['KELEMBABAN KALIBRASI (%)'] ?></td>
                                                        <td><?= $rowtool['KONDISI ALAT'] ?></td>
                                                        <td><?= $rowtool['N1'] ?></td>
                                                        <td><?= $rowtool['N2'] ?></td>
                                                        <td><?= $rowtool['N3'] ?></td>
                                                        <td><?= $rowtool['MinPenyimpangan'] ?></td>
                                                        <td><?= $rowtool['MaxPenyimpangan'] ?></td>
                                                        <td class="<?php echo ($rowtool['KeputusanHasil'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasil'] ?></td>
                                                        <!-- <td><?= $rowtool['DeviasiMaju'] ?></td> -->
                                                        <!-- <td class="<?php echo ($rowtool['KeputusanHasil'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasil'] ?></td> -->
                                                        <td><?= date('d M Y', strtotime($rowtool['TanggalKalibrasi'])) ?></td>
                                                        <td><?= $rowtool['KETERANGAN'] ?></td>
                                                        <td><?= date('d M Y', strtotime($rowtool['NextKalibrasi']))  ?></td>
                                                    </tr>
                                                    <?php }else {  echo 'Data Belum Ada'; } ?>
                                                <?php } ?>
                                            </tbody>
                                    </table>
                                        <div class="row ml-2">
                                            <h6 style="font-weight:bold">Item Pemeriksaan Visual :</h6>
                                            <p style="font-size:10px">a.	Hasil pengukuran OK, jika hasil ukur tidak melebihi dari ± 0.03  dari setting skala<br>					
                                        b.	Jika hasil pengukuran melebihi ± 0.03 maka harus dilakukan adjudgement	</p>

                                        <h6 style="font-weight:bold">Item Pemeriksaan Visual :</h6>
                                            <p style="font-size:10px">*) Display board, Conector, Contact block, Stick, Switch = Dalam keadaan baik</p>
                                        </div>
                                <?php }else if(isset($_POST['tool_name']) && $_POST['tool_name'] == 'VERNIER' ){?>
                                    <table id="tbl_report" class="table table-sm table-hover table-striped" style=" font-size : 9px;">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>							
                                                <th rowspan="2">Identification Number</th>							
                                                <th rowspan="2">Kalibrasi Ke</th>							
                                                <th rowspan="2">Serial No.</th>							
                                                <th rowspan="2">Range (mm)</th>							
                                                <th rowspan="2">Suhu kalibrasi (°C)</th>							
                                                <th rowspan="2">Kelembaban kalibrasi  (%)</th>							
                                                <th rowspan="2">Kondisi Visual alat ukur</th>	
                                                <th rowspan="2">Area Ukur</th>	
                                                <th colspan="6" class="text-center">Kalibrasi Rahang Luar ( mm )</th>							
                                                <th colspan="6" class="text-center">Kalibrasi Rahang Dalam ( mm )</th>							
                                                <th colspan="4" class="text-center">Batang Kedalaman ( mm )</th>							
                                            </tr>
                                            <tr>		
                                                <th>0</th>	
                                                <th>100</th>	
                                                <th>200</th>	
                                                <th>300</th>	
                                                <th>Max Penyimpangan</th>	
                                                <th>Hasil</th>
                                                <th>0</th>	
                                                <th>100</th>	
                                                <th>200</th>	
                                                <th>300</th>	
                                                <th>Max Penyimpangan</th>	
                                                <th>Hasil</th>
                                                <th>0</th>	
                                                <th>Max Penyimpangan</th>	
                                                <th>Hasil</th>	
                                                <th>Keputusan Hasil</th>	
                                                <th>Tgl Kalibrasi</th>	
                                                <th>Ket</th>	
                                                <th>Kalibrasi Selanjutnya</th>	
                                            </tr>
                                        </thead>		
                                        <tbody>
                                            <?php $i = 1; while($rowtool = sqlsrv_fetch_array($stmt_report, SQLSRV_FETCH_ASSOC)) { ?>
                                                <tr>
                                                    <td  rowspan="3"><?= $i++ ?></td>
                                                    <td  rowspan="3"><?= $rowtool['IdentificationNumber'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['KALIBRASI KE'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['SERIAL NUMBER'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['RANGE'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['SUHU KALIBRASI (C)'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['KELEMBABAN KALIBRASI (%)'] ?></td>
                                                    <td  rowspan="3"><?= $rowtool['KONDISI ALAT'] ?></td>

                                                    <td class="text-bold">Depan</td>
                                                    <td><?= $rowtool['0_Depan (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['100_Depan (Rahang Luar)'] ?></td> 
                                                    <td><?= $rowtool['200_Depan (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['300_Depan (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangLuarDepan'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilLuarDepan'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilLuarDepan'] ?></td>
                                                    <td><?= $rowtool['0_Depan (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['100_Depan (Rahang Dalam)'] ?></td> 
                                                    <td><?= $rowtool['200_Depan (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['300_Depan (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangDalamDepan'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilDalamDepan'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilDalamDepan'] ?></td>
                                                    <td><?= $rowtool['0_Depan (Kedalaman)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganKedalamanDepan'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilKedalamanDepan'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilKedalamanDepan'] ?></td>
                                                    <td class="<?php echo ($rowtool['KeputusanHasilDepan'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasilDepan'] ?></td>
                                                    
                                                    <td rowspan="3"><?= $rowtool['TanggalKalibrasi'] ?></td>
                                                    <td rowspan="3"><?= $rowtool['KETERANGAN'] ?> </td>
                                                    <td rowspan="3"><?= $rowtool['NextKalibrasi'] ?></td>   
                                                </tr>
                                                <tr>
                                                    <td class="text-bold">Tengah</td>
                                                    <td><?= $rowtool['0_Tengah (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['100_Tengah (Rahang Luar)'] ?></td> 
                                                    <td><?= $rowtool['200_Tengah (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['300_Tengah (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangLuarTengah'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilLuarTengah'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilLuarTengah'] ?></td>
                                                    <td><?= $rowtool['0_Tengah (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['100_Tengah (Rahang Dalam)'] ?></td> 
                                                    <td><?= $rowtool['200_Tengah (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['300_Tengah (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangDalamTengah'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilDalamTengah'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilDalamTengah'] ?></td>
                                                    <td><?= $rowtool['0_Tengah (Kedalaman)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganKedalamanTengah'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilKedalamanTengah'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilKedalamanTengah'] ?></td>
                                                    <td class="<?php echo ($rowtool['KeputusanHasilTengah'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasilTengah'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-bold">Belakang</td>
                                                    <td><?= $rowtool['0_Belakang (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['100_Belakang (Rahang Luar)'] ?></td> 
                                                    <td><?= $rowtool['200_Belakang (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['300_Belakang (Rahang Luar)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangLuarBelakang'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilLuarBelakang'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilLuarBelakang'] ?></td>
                                                    <td><?= $rowtool['0_Belakang (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['100_Belakang (Rahang Dalam)'] ?></td> 
                                                    <td><?= $rowtool['200_Belakang (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['300_Belakang (Rahang Dalam)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganRahangDalamBelakang'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilDalamBelakang'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilDalamBelakang'] ?></td>
                                                    <td><?= $rowtool['0_Belakang (Kedalaman)'] ?></td>
                                                    <td><?= $rowtool['MaxPenyimpanganKedalamanBelakang'] ?></td>
                                                    <td class="<?php echo ($rowtool['HasilKedalamanBelakang'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['HasilKedalamanBelakang'] ?></td>
                                                    <td class="<?php echo ($rowtool['KeputusanHasilBelakang'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>"><?= $rowtool['KeputusanHasilBelakang'] ?></td>
                                                </tr>
                                                <?php } ?>
                                        </tbody>
                                    </table>
                                        <div class="row ml-2">
                                            <h6 style="font-weight:bold">Item Pemeriksaan Visual :</h6>
                                            <p style="font-size:10px">*) Rahang luar, rahang dalam, pengunci, penekan, garis ukur, batang kedalaman, display (digital) = Dalam keadaan baik</p>
                                        </div>
                                <?php }else { ?>
                                    <table id="tbl_report" class="table table-sm table-striped"
                                        style=" font-size : 9px;">
                                            <thead>
                                            <tr>
                                                <th rowspan="4">No</th>
                                                <th rowspan="4">Identification Number</th>
                                                <th rowspan="4">Kalibrasi Ke</th>
                                                <th rowspan="4">Serial No.</th>
                                                <th rowspan="4">Range (mm)</th>
                                                <th rowspan="4">Ketelitian (MM)</th>
                                                <th rowspan="4">Suhu kalibrasi (°C)</th>
                                                <th rowspan="4">Kelembaban kalibrasi (%)</th>
                                                <th rowspan="4">Kondisi Visual alat ukur</th>
                                            </tr>
                                            <tr>
                                                <!-- <th colspan="16" class="text-center">Ketelitian : 0.01mm, Kalibrasi
                                                    Bertingkat Range: 0 ~ 1 mm</th> -->
                                                    <?php 
                                                        if (isset($_POST['std']) && $_POST['std'] == 'ALL') {
                                                            echo '<th colspan="16" class="text-center">Ketelitian : ALL, Kalibrasi Bertingkat Range: ALL</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD1'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.01mm, Kalibrasi Bertingkat Range: 0 ~ 1 mm </th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD2'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.01mm, Kalibrasi Bertingkat Range: 0 ~ 0.8 mm</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD3'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.01mm, Kalibrasi Bertingkat Range: 0 ~ 10 mm</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD4'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.1mm, Kalibrasi Bertingkat Range: 0 ~ 4 mm</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD5'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.001mm, Kalibrasi Bertingkat Range: 0 ~ 0.2 mm</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD6'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.001mm, Kalibrasi Bertingkat Range: 0 ~ 1 mm</th>';
                                                        }else if(isset($_POST['std']) && $_POST['std'] == 'STD7'){
                                                            echo '<th colspan="16" class="text-center">Ketelitian : 0.001mm, Kalibrasi Bertingkat Range: 0 ~ 0.1 mm</th>';
                                                        }else {
                                                            echo '<th colspan="16" class="text-center">Ketelitian : ALL, Kalibrasi Bertingkat Range: ALL</th>';
                                                        }
                                                    ?>
                                            </tr>
                                            <tr>
                                                <th colspan="8" class="text-center">Kalibrasi Langkah Maju ( mm )
                                                </th>
                                                <th colspan="8" class="text-center">Kalibrasi Langkah Mundur ( mm )
                                                </th>
                                            </tr>
                                            <tr>
                                                <?php if(isset($_POST['std']) && $_POST['std'] == 'STD1'){ ?>
                                                    <th>0,00</th>							
                                                    <th>0,20</th>							
                                                    <th>0,40</th>							
                                                    <th>0,60</th>							
                                                    <th>0,80</th>							
                                                    <th>1,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>1,00</th>							
                                                    <th>0,80</th>							
                                                    <th>0,60</th>							
                                                    <th>0,40</th>							
                                                    <th>0,20</th>							
                                                    <th>0,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD2'){ ?>
                                                    <th>0,00</th>							
                                                    <th>0,20</th>							
                                                    <th>0,40</th>							
                                                    <th>0,60</th>							
                                                    <th>0,80</th>							
                                                    <th>-</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>1,00</th>							
                                                    <th>0,80</th>							
                                                    <th>0,60</th>							
                                                    <th>0,40</th>							
                                                    <th>0,20</th>							
                                                    <th>-</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD3'){ ?>
                                                    <th>0,00</th>							
                                                    <th>2,00</th>							
                                                    <th>4,00</th>							
                                                    <th>6,00</th>							
                                                    <th>8,00</th>							
                                                    <th>10,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>10,00</th>							
                                                    <th>8,00</th>							
                                                    <th>6,00</th>							
                                                    <th>4,00</th>							
                                                    <th>2,00</th>							
                                                    <th>0,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD4'){ ?>
                                                    <th>0,00</th>							
                                                    <th>1,00</th>							
                                                    <th>2,00</th>							
                                                    <th>3,00</th>							
                                                    <th>4,00</th>							
                                                    <th>-</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>4,00</th>							
                                                    <th>3,00</th>							
                                                    <th>2,00</th>							
                                                    <th>1,00</th>							
                                                    <th>0,00</th>							
                                                    <th>-</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD5'){ ?>
                                                    <th>0,00</th>							
                                                    <th>0,04</th>							
                                                    <th>0,08</th>							
                                                    <th>0,12</th>							
                                                    <th>0,16</th>							
                                                    <th>0,20</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>0,20</th>							
                                                    <th>0,16</th>							
                                                    <th>0,12</th>							
                                                    <th>0,08</th>							
                                                    <th>0,04</th>							
                                                    <th>0,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD6'){ ?>
                                                    <th>0,00</th>							
                                                    <th>0,20</th>							
                                                    <th>0,40</th>							
                                                    <th>0,60</th>							
                                                    <th>0,80</th>							
                                                    <th>1,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>1,00</th>							
                                                    <th>0,80</th>							
                                                    <th>0,60</th>							
                                                    <th>0,40</th>							
                                                    <th>0,20</th>							
                                                    <th>0,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php } else if(isset($_POST['std']) && $_POST['std'] == 'STD7'){ ?>
                                                    <th>0,00</th>							
                                                    <th>0,02</th>							
                                                    <th>0,04</th>							
                                                    <th>0,06</th>							
                                                    <th>0,08</th>							
                                                    <th>0,10</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                    <th>0,10</th>							
                                                    <th>0,08</th>							
                                                    <th>0,06</th>							
                                                    <th>0,04</th>							
                                                    <th>0,02</th>							
                                                    <th>0,00</th>							
                                                    <th>Dev</th>							
                                                    <th>Hasil</th>
                                                <?php }else{ ?>
                                                    <th>0,00</th>
                                                    <th>0,20</th>
                                                    <th>0,40</th>
                                                    <th>0,60</th>
                                                    <th>0,80</th>
                                                    <th>1,00</th>
                                                    <th>Dev</th>
                                                    <th>Hasil</th>
                                                    <th>1,00</th>
                                                    <th>0,80</th>
                                                    <th>0,60</th>
                                                    <th>0,40</th>
                                                    <th>0,20</th>
                                                    <th>0,00</th>
                                                    <th>Dev</th>
                                                    <th>Hasil</th>
                                                <?php } ?>
                                                <th>Hasil Kalibrasi</th>
                                                <th>Tgl Kalibrasi</th>
                                                <th>Keterangan</th>
                                                <th>Kalibrasi Selanjutnya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; while($rowtool = sqlsrv_fetch_array($stmt_report, SQLSRV_FETCH_ASSOC))  { ?>
                                            <?php if($rowtool){ ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $rowtool['IdentificationNumber'] ?></td>
                                                <td><?= $rowtool['KALIBRASI KE'] ?></td>
                                                <td><?= $rowtool['SERIAL NUMBER'] ?></td>
                                                <td><?= $rowtool['RANGE'] ?></td>
                                                <td><?= $rowtool['KETELITIAN (MM)'] ?></td>
                                                <td><?= $rowtool['SUHU KALIBRASI (C)'] ?></td>
                                                <td><?= $rowtool['KELEMBABAN KALIBRASI (%)'] ?></td>
                                                <td><?= $rowtool['KONDISI ALAT'] ?></td>
                                                <td><?= $rowtool['0,00_Maju'] ?></td>
                                                <td><?= $rowtool['0,20_Maju'] ?></td>
                                                <td><?= $rowtool['0,40_Maju'] ?></td>
                                                <td><?= $rowtool['0,60_Maju'] ?></td>
                                                <td><?= $rowtool['0,80_Maju'] ?></td>
                                                <td><?= $rowtool['1,00_Maju'] ?></td>
                                                <td><?= $rowtool['DeviasiMaju'] ?></td>
                                                <td
                                                    class="<?php echo ($rowtool['HasilMaju'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>">
                                                    <?= $rowtool['HasilMaju'] ?></td>
                                                <td><?= $rowtool['1,00_Mundur'] ?></td>
                                                <td><?= $rowtool['0,80_Mundur'] ?></td>
                                                <td><?= $rowtool['0,60_Mundur'] ?></td>
                                                <td><?= $rowtool['0,40_Mundur'] ?></td>
                                                <td><?= $rowtool['0,20_Mundur'] ?></td>
                                                <td><?= $rowtool['0,00_Mundur'] ?></td>
                                                <td><?= $rowtool['DeviasiMundur'] ?></td>
                                                <td
                                                    class="<?php echo ($rowtool['HasilMundur'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>">
                                                    <?= $rowtool['HasilMundur'] ?></td>
                                                <td
                                                    class="<?php echo ($rowtool['KeputusanHasil'] == 'OK') ? 'bg-success' : 'bg-danger';  ?>">
                                                    <?= $rowtool['KeputusanHasil'] ?></td>
                                                <td><?= date('d M Y', strtotime($rowtool['TanggalKalibrasi']))  ?></td>
                                                <td><?= $rowtool['KETERANGAN'] ?></td>
                                                <td><?= date('d M Y', strtotime($rowtool['NextKalibrasi'] ))  ?></td>
                                            </tr>
                                            <?php }else {  echo 'Data Belum Ada'; } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div class="row ml-2">
                                        <h6 style="font-weight:bold">Item Pemeriksaan Visual :</h6>
                                        <p style="font-size:10px">*) Spindle, Contact point/Anvil, Tutup spindle,
                                            Stem, Garis dan skala ukur, Jarum ukur = Dalam keadaan baik</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <script>
    // /Initialize Select2 Elements
    // $('.select').select2({
    //     theme: 'bootstrap4'
    // })    

    $('#tbl_report').DataTable( {
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
    } );

    $("#date-range").daterangepicker({
		autoUpdateInput: true,
        dateFormat: 'YYYY/MM/DD',
		locale: {
		cancelLabel: 'Clear',
		}
	});

	$("#date-range").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
	});

	$("#date-range").on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});	

    $("#form1show").click(function(e){
	$('#form1').show();
    $('#form2').hide();
    $('#form3').hide();
	});

	$('#form2show').click(function(e){
	$('#form2').show();
    $('#form1').hide();
    $('#form3').hide();
	});

    $('#form3show').click(function(e){
	$('#form3').show();
    $('#form1').hide();
    $('#form2').hide();
	});


    $('#hideall').click(function(e){
    $('#form3').hide();
    $('#form1').hide();
    $('#form2').hide();
    });

    $("#tool_name").change(function(){
        $(this).find("option:selected").each(function(){
            if($(this).attr("value")=="DIAL GAUGE"){
                $(".stdClass").show();
            }
            else{
                $(".stdClass").hide();
            }
        });
    }).change();


    
    </script>
</body>

</html>