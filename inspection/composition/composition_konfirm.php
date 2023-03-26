<!doctype html>
<html lang="en">
<head>
<?php

session_start();
//error_reporting(0);
require "ui/header.php";
require "conf/dbkoneksi.php";
$sessionID_ = $_SESSION['SessionID'];
$lineCode_ = $_SESSION['LineCode'];
$recid = $_SESSION['recid'];
$fm = $_SESSION['fm'];

$mode = $_GET['mode'];
$mode_page = $_GET['mode_page'];

if($mode == 'AMBIL'){
$fm_cms = $_GET['fm']; // GET VARIABEL
$sample = $_GET['sample'];
$laddle = $_GET['laddle'];
$qty = $_GET['qty'];
$analyst = $_GET['analyst'];
$recid = $_GET['recid'];
$serverComposition = $_GET['svr'];

//GET STATE
$get_state = "SELECT * FROM PRD.dbo.casting_state WHERE SessionID = '$sessionID_' AND LineCode = '$lineCode_'";

$stmt_state = sqlsrv_query($conn, $get_state);
while($row_get_state = sqlsrv_fetch_array($stmt_state, SQLSRV_FETCH_ASSOC))	
{
	$lineName =  $row_get_state['LineName'];
	$shiftName =  $row_get_state['ShiftName'];
	$partName =  $row_get_state['PartName'];	
	$lineCode_get = $row_get_state['LineCode'];	
	$workdate_get = $row_get_state['WorkingDate'];
	$sessionID_get = $row_get_state['SessionID'];
	$assetID_get = $row_get_state['AssetID'];
	$SubSessionID_get = $row_get_state['SubSessionID'];
}

	$fm_cms = $_GET['fm']; // GET VARIABEL
	$sample = $_GET['sample'];
	$laddle = $_GET['laddle'];
	$qty = $_GET['qty'];
	$analyst = $_GET['analyst'];
	$recid = $_GET['recid'];
	$serverComposition = $_GET['svr'];

	$userComposition = "aas";
	$passwordComposition = "andon";

	$conn_composition = mysqli_connect($serverComposition, $userComposition, $passwordComposition);
	
	$get_std = mysqli_query($conn_composition, "SELECT fcode,
		MAX(IF(felement = 'C', fmin, NULL)) AS C_min,
		MAX(IF(felement = 'Si', fmin, NULL)) AS Si_min,
		MAX(IF(felement = 'Mn', fmin, NULL)) AS Mn_min,
		MAX(IF(felement = 'P', fmin, NULL)) AS P_min,
		MAX(IF(felement = 'S', fmin, NULL)) AS S_min,
		MAX(IF(felement = 'Cu', fmin, NULL)) AS Cu_min,
		MAX(IF(felement = 'Sn', fmin, NULL)) AS Sn_min,
		MAX(IF(felement = 'Mg', fmin, NULL)) AS Mg_min,
		MAX(IF(felement = 'Ti', fmin, NULL)) AS Ti_min,
		MAX(IF(felement = 'Cr', fmin, NULL)) AS Cr_min,
		MAX(IF(felement = 'Ni', fmin, NULL)) AS Ni_min,
		MAX(IF(felement = 'Al', fmin, NULL)) AS Al_min,
		MAX(IF(felement = 'Mo', fmin, NULL)) AS Mo_min,
		MAX(IF(felement = 'V', fmin, NULL)) AS V_min,
		MAX(IF(felement = 'Zn', fmin, NULL)) AS Zn_min,
		MAX(IF(felement = 'Sb', fmin, NULL)) AS Sb_min,
		MAX(IF(felement = 'Fe1', fmin, NULL)) AS Fe1_min,
		MAX(IF(felement = 'Fe2', fmin, NULL)) AS Fe2_min,
		MAX(IF(felement = 'C', fmax, NULL)) AS C_max,
		MAX(IF(felement = 'Si', fmax, NULL)) AS Si_max,
		MAX(IF(felement = 'Mn', fmax, NULL)) AS Mn_max,
		MAX(IF(felement = 'P', fmax, NULL)) AS P_max,
		MAX(IF(felement = 'S', fmax, NULL)) AS S_max,
		MAX(IF(felement = 'Cu', fmax, NULL)) AS Cu_max,
		MAX(IF(felement = 'Sn', fmax, NULL)) AS Sn_max,
		MAX(IF(felement = 'Mg', fmax, NULL)) AS Mg_max,
		MAX(IF(felement = 'Ti', fmax, NULL)) AS Ti_max,
		MAX(IF(felement = 'Cr', fmax, NULL)) AS Cr_max,
		MAX(IF(felement = 'Ni', fmax, NULL)) AS Ni_max,
		MAX(IF(felement = 'Al', fmax, NULL)) AS Al_max,
		MAX(IF(felement = 'Mo', fmax, NULL)) AS Mo_max,
		MAX(IF(felement = 'V', fmax, NULL)) AS V_max,
		MAX(IF(felement = 'Zn', fmax, NULL)) AS Zn_max,
		MAX(IF(felement = 'Sb', fmax, NULL)) AS Sb_max,
		MAX(IF(felement = 'Fe1', fmax, NULL)) AS Fe1_max,
		MAX(IF(felement = 'Fe2', fmax, NULL)) AS Fe2_max
	FROM db_cms.tstandar
	where fcode = '$sample'
	GROUP BY fcode");
	
	while($rowstd = mysqli_fetch_array($get_std))
	{
		$C_min = $rowstd['C_min'];
		$Si_min = $rowstd['Si_min'];
		$Mn_min = $rowstd['Mn_min'];
		$P_min = $rowstd['P_min'];
		$S_min = $rowstd['S_min'];
		$Cu_min = $rowstd['Cu_min'];
		$Sn_min = $rowstd['Sn_min'];
		$Mg_min = $rowstd['Mg_min'];
		$Ti_min = $rowstd['Ti_min'];
		$Cr_min = $rowstd['Cr_min'];
		$Ni_min = $rowstd['Ni_min'];
		$Al_min = $rowstd['Al_min'];
		$Mo_min = $rowstd['Mo_min'];
		$V_min = $rowstd['V_min'];
		$Zn_min = $rowstd['Zn_min'];
		$Sb_min = $rowstd['Sb_min'];
		$Fe1_min = $rowstd['Fe1_min'];
		$Fe2_min = $rowstd['Fe2_min'];
		$C_max = $rowstd['C_max'];
		$Si_max = $rowstd['Si_max'];
		$Mn_max = $rowstd['Mn_max'];
		$P_max = $rowstd['P_max'];
		$S_max = $rowstd['S_max'];
		$Cu_max = $rowstd['Cu_max'];
		$Sn_max = $rowstd['Sn_max'];
		$Mg_max = $rowstd['Mg_max'];
		$Ti_max = $rowstd['Ti_max'];
		$Cr_max = $rowstd['Cr_max'];
		$Ni_max = $rowstd['Ni_max'];
		$Al_max = $rowstd['Al_max'];
		$Mo_max = $rowstd['Mo_max'];
		$V_max = $rowstd['V_max'];
		$Zn_max = $rowstd['Zn_max'];
		$Sb_max = $rowstd['Sb_max'];
		$Fe1_max = $rowstd['Fe1_max'];
		$Fe2_max = $rowstd['Fe2_max'];
	}
		
	$qty_result = str_replace(':0-','&',$qty);
	$qty_result_ = str_replace(':0','&',$qty_result);

	$c = substr($qty_result_, 2,4);
	$si = substr($qty_result_, 10,4);
	$mn = substr($qty_result_, 18,4);
	$p = substr($qty_result_, 25,5);
	$s = substr($qty_result_, 33,5);
	$cu = substr($qty_result_, 42,5);
	$sn = substr($qty_result_, 51,5);
	$mg = substr($qty_result_, 60,5);
	$ti = substr($qty_result_, 69,5);
	$cr = substr($qty_result_, 78,5);
	$ni = substr($qty_result_, 87,5);
	$al = substr($qty_result_, 96,5);
	$mo = substr($qty_result_, 105,5);
	$v = substr($qty_result_, 113,5);
	$zn = substr($qty_result_, 122,5);
	$sb = substr($qty_result_, 131,5);
	$fe1 = str_replace('=','',substr($qty_result_, 141,6));
	$fe2 = str_replace('=','',substr($qty_result_, 152,6));	

?>
</head>

<body> 
<div class="preloader">
    <div class="loading">
        <center><img src="../../assets/img/load.gif" width="70"><br>
		<label style="color :white;">Processing..</label></center>
    </div>
</div>
<div class="container-fluid py-1">
	<?php $date = date('l, d-M-Y'); ?>
	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand">
				<span class="d-inline-block align-text-bottom">
					<img src="../assets/img/ATIbig.png" alt="" width="60rem" height="30rem">
				</span>
				<span class="d-inline-block align-text-middle" style="text-indent: 0.1em; color : white; font-weight : bold; font-size : 1.95rem;" >
						<?php echo $lineName; ?>
				</span>
			</a>	
			
			<div class="d-flex text-right">				
				<h5><?php echo $date . '<br>' . '<span id="jam"></span>'; ?></h5>							
			</div>
		</div>
	</nav>
	
	<div class="card">	
		<div class="card-header bg-secondary text-center">
			<h2>COMPOSITION CONFIRMATION</h2>
		</div>	
	
		<div class="card-body">	
		
			<h1>Apakah komposisi yang anda pilih telah benar ?<br><h1>
			<h3>pastikan cek kembali, jika dirasa sudah benar silahkan tentukan judgement terhadap kompoisi tersebut.</h3><br>
			<h2><span style="color: green;">OK --> Jika kompoisi STANDAR</span><br>
			<span style="color: red;">NG --> Jika terdapat TIDAK STANDAR</span></h2><br>
			
			<h3>Pastikan cek kembali, jika dirasa sudah benar silahkan tentukan judgement terhadap kompoisi tersebut.</h3><br>
		
			<table class="table">	
				<thead>
					<tr>						
						<th>TIME</th>
						<th>FM</th>
						<th>SAMPLE</th>
						<th>LADLE</th>
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
				<tbody>
					<tr>
						<td><?php echo $analyst;?></td>
						<td><?php echo $fm_cms;?></td>
						<td style="background: green; color: white;"><?php echo $sample;?></td>
						<td><?php echo $laddle;?></td>
						<td style="background: <?php echo $c < $C_min || $c > $C_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $c;?></td>
						
						<td style="background: <?php echo $si < $Si_min || $si > $Si_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $si;?></td>
						<td style="background: <?php echo $mn < $Mn_min || $mn > $Mn_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $mn;?></td>
						<td style="background: <?php echo $s < $S_min || $s > $S_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $s;?></td>
						<td style="background: <?php echo $cu < $Cu_min || $cu > $Cu_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $cu;?></td>
						<td style="background: <?php echo $sn < $Sn_min || $sn > $Sn_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $sn;?></td>
						<td style="background: <?php echo $cr < $Cr_min || $cr > $Cr_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $cr;?></td>
						<td style="background: <?php echo $p < $P_min || $p > $P_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $p;?></td>
						<td style="background: <?php echo $zn < $Zn_min || $zn > $Zn_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $zn;?></td>
						<td style="background: <?php echo $al < $Al_min || $al > $Al_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $al;?></td>
						<td style="background: <?php echo $ti < $Ti_min || $ti > $Ti_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $ti;?></td>
						<td style="background: <?php echo $mg < $Mg_min || $mg > $Mg_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $mg;?></td>
						<td style="background: <?php echo $ni < $Ni_min || $ni > $Ni_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $ni;?></td>
						<td style="background: <?php echo $v < $V_min || $v > $V_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $v;?></td>
						<td style="background: <?php echo $mo < $Mo_min || $mo > $Mo_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $mo;?></td>
						<td style="background: <?php echo $sb < $Sb_min || $sb > $Sb_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $sb;?></td>
						<td style="background: <?php echo $fe1 < $Fe1_min || $fe1 > $Fe1_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $fe1;?></td>
						<td style="background: <?php echo $fe2 < $Fe2_min || $fe2 > $Fe2_max ? 'red; color: white;' : '; color: black;';?>"><?php echo $fe2;?></td>
					</tr>
				</tbody>
			</table>			
			
			<form action="" method="POST">
			
				<div class="row py-3">				
					CATATAN
					<input type="text" class="form-control" name="catatankomposisi">
				</div>
				
				<div class="row py-3">
				  <button type="submit" name="subok" style="font-size: 40px;" class="col-4 btn btn-success">OK</button>
				  <button type="submit" name="subng" style="font-size: 40px;" class="col-4 btn btn-danger">NG</button>
				  <a href="composition" style="font-size: 40px;" class="col-4 btn btn-warning">Pilih Kembali</a>
				</div>
			
				<?php
				if(isset($_POST['subok']))
				{
					$catatankomposisi = $_POST['catatankomposisi'];
					
					$sql_update = "UPDATE [PRD].[dbo].[pouring_lines]
						SET 
						Comp_C = '$c'
						,Comp_Si = '$si'
						,Comp_Mn = '$mn'
						,Comp_Ti = '$ti'
						,Comp_Ni = '$ni'	
						,Comp_Al = '$al'
						,Comp_Zn = '$zn'
						,Comp_Sb = '$sb'
						,Comp_S = '$s'
						,Comp_Cu = '$cu'
						,Comp_Sn = '$sn'
						,Comp_Cr = '$cr'
						,Comp_P = '$p'
						,Comp_V = '$v'
						,Comp_Mo = '$mo'
						,Comp_Mg = '$mg'
						,Comp_Fe1 = '$fe1'
						,Comp_Fe2 = '$fe2'
						,sampleName = '$sample'
						,CompositionJudge = 'OK'
						,CompositionRemark = '$catatankomposisi'
					 WHERE RecID = '$recid'";

					 $update = sqlsrv_query($conn, $sql_update);				 
					 
					 if($update){							
						$state = "UPDATE PRD.dbo.casting_state SET Status = 'proses'
							, RecordTime = getdate()
						WHERE LineCode = '$lineCode_' AND Session = 'ACTIVE'";
						$state_exec =  sqlsrv_query($conn, $state);	
							
						if($state_exec){
							echo '<meta http-equiv="refresh" content="0; url=proses">';
						}
					}
				}		

				if(isset($_POST['subng']))
				{
					$catatankomposisi = $_POST['catatankomposisi'];
					
					if($catatankomposisi == '')
					{
						echo "<script>
							Swal.fire({
								icon: 'error',
								title : 'Submit Gagal !',
								text: 'Jika komposisi NG Catatan WAJIB diisi !',
								timer: 3000,
								showConfirmButton : false
																									
							});
						</script>";
						
					}else{
					
						$sql_update = "UPDATE [PRD].[dbo].[pouring_lines]
							SET 
							Comp_C = '$c'
							,Comp_Si = '$si'
							,Comp_Mn = '$mn'
							,Comp_Ti = '$ti'
							,Comp_Ni = '$ni'	
							,Comp_Al = '$al'
							,Comp_Zn = '$zn'
							,Comp_Sb = '$sb'
							,Comp_S = '$s'
							,Comp_Cu = '$cu'
							,Comp_Sn = '$sn'
							,Comp_Cr = '$cr'
							,Comp_P = '$p'
							,Comp_V = '$v'
							,Comp_Mo = '$mo'
							,Comp_Mg = '$mg'
							,Comp_Fe1 = '$fe1'
							,Comp_Fe2 = '$fe2'
							,sampleName = '$sample'
							,CompositionJudge = 'NG'
							,CompositionRemark = '$catatankomposisi'
						 WHERE RecID = '$recid'";
						 
						 $update = sqlsrv_query($conn, $sql_update);
						 
						 if($update){							
							$state = "UPDATE PRD.dbo.casting_state SET Status = 'proses'
								, RecordTime = getdate()
							WHERE LineCode = '$lineCode_' AND Session = 'ACTIVE'";						
							$state_exec =  sqlsrv_query($conn, $state);				
							if($state_exec){
								echo '<meta http-equiv="refresh" content="0; url=proses">';
							}				
						}
					}
				}
				
				?>
			
			</form>
			
			
		</div>
	</div>

	
<?php } ?>
</div>
</body> 
</html>