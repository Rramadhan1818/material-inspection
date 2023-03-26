<!doctype html>
<html lang="en">
<head>
<?php
session_start();
//error_reporting(0);
require "../ui/header.php";
require "../conf/dbkoneksi.php";
// $sessionID_ = $_SESSION['SessionID'];
// $lineCode_ = $_SESSION['LineCode'];
// $recid = $_SESSION['recid'];
// $fm = $_SESSION['fm'];
//$mode_page = $_SESSION['mode'];


$AreaName = $row_get['Area'];

if($AreaName == 'ACE')
{
	$serverComposition = "10.123.230.185";
}else{
	$serverComposition = "10.123.230.186";
}

$userComposition = "aas";
$passwordComposition = "andon";

$conn_composition = mysqli_connect($serverComposition, $userComposition, $passwordComposition);

//$conn_composition = false;

//require "session.php";
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
	
	<div class="container-fluid py-1">
	
	<div class="row mb-3">
		<div class="col-2">

		</div>
		<div class="col-2">
		
		</div>
		<div class="col-4">
		
		</div>
		<div class="col-4 text-right">
			<table>				
				<th><a href="ac_cancel_compostion.php?lc=<?php echo $lineCode_get.'&recid='.$recid; ?>" style="font-weight: bold;" class="btn btn-warning btn-block">CANCEL</a> </th>
			</table>
		</div>	
					
	</div>
	
	<?php if($conn_composition) { ?>
	<div class="card-header bg-success">
		<h4>SELECT COMPOSITION FOR FM <?php echo $FM_get.' - PRODUCT : '.$productCode; ?></h4>
	</div>
	
	
	
	<div class="card-body">	
		
		<div class="row">	
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
                  $max_show = 15;
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
                  $layout .= "<table class=\"table-striped\">";
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
                  FROM db_cms.tdisplay WHERE SUBSTRING(ffurnace, 3) LIKE '%$fm%'  ORDER BY ftr_time DESC LIMIT 40");
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
                        $layout .= "<tr align=\"center\" >";
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
                        //   echo $cms;
                        }
                        // echo $cms;
                        // var_dump($idx_data_next);die;
                        $spektro_data = str_replace('#','-',$spek[$i]);
                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" align=\"center\" ><span class=\"tablecell\" >".($i)."</span></td>";
                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"8%\" align=\"center\" ><span class=\"tablecell\" >".$tr_time[$i]."</span></td>";
                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" ><span class=\"tablecell\" >".$furnace[$i]."</span></td>";
                        
                        $num_rows1 = 0;
                        $query1 = "SELECT fcode FROM tstandar WHERE fcode = '{$kodeloe[$i]}'";
                        
                        $query_count = mysqli_query($conn_composition, "SELECT COUNT(fcode) fcode FROM tstandar WHERE fcode = '{$kodeloe[$i]}'");
                        $num_rows1 = $query_count;
                        
                        echo $num_rows1."<br/>";
                        // var_dump($num_rows1);die;
                        if ($num_rows1 > 0) {
                          $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"10%\" ><span class=\"tablecell bg-warning\" ><a type=\"submit\" class=\"button btn-lg btn-primary\" href=\"composition_konfirm.php?mode=AMBIL&recid=$recid&analyst=$tr_time[$i]&fm=$furnace[$i]&sample=$kodeloe[$i]&laddle=$laddle[$i]&qty=$spektro_data&svr=$serverComposition\">".$samples[$i]."</a></span></td>";
                        } else {
                          $layout .= "<td bgcolor=\"bg-warning\"  width=\"10%\" ><span class=\"tablecell\" ><a type=\"submit\" class=\"button btn-lg btn-success btn-block\" href=\"composition_konfirm.php?mode_page=$mode_page&mode=AMBIL&recid=$recid&analyst=$tr_time[$i]&fm=$furnace[$i]&sample=$kodeloe[$i]&laddle=$laddle[$i]&qty=$spektro_data&svr=$serverComposition\">".$samples[$i]."</a></span></td>";
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
                      
                   //$queryk = mysqli_query($conn_composition, "SELECT * FROM tstandar WHERE fcode = '{$kodeloe[$i]}' and felement = '$searchTerm'");
					
					while($queryku2 = mysqli_fetch_array($resultsqlstd))	
                    {
                    $red_min = $queryku2['fmin'];
                    $red_max = $queryku2['fmax'];
                    //$blue_min = $queryku2['fmin_blue'];
                    //$blue_max = $queryku2['fmax_blue'];
                    }
                    
                    //cek apakah sample ada ?
					 $adasample = 0;
					 if($red_min != ""){
						$adasample = 1; 
					 }
					 
					  
					  //echo $searchTerm." - ".$pos."<br/>";
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
						//echo $pos." | ".$data_element[$pos]." | ".$disp_value." | ".$disp_status."<br/>";
					  }
                      
                      $disp_value = $data_value[$pos]; 
		  
					  //echo "<font color='white'>".$red_max."</font><br>";
					  
					  //if($disp_value <= $red_min || $disp_value >= $red_max){
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
							//echo $searchTerm." - ".$pos."<br/>";
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
					  
					  //make blink
					  if($def_bg_color == "blue" || $def_bg_color == "red"){
						//if($modi == 1){   
							$blink = $def_bg_color;
						/*}else{
							if ($i%2) {
							$blink = "#33FFFF";
							} else {
							$blink = "white";
							}
						}*/
					  }else{
						  $blink = $def_bg_color;
					  }
                      
                          
							$layout .= "             <td bgcolor=\"".$blink."\" align=\"center\" ><span class=\"tablecell\" >".$disp_value."</span></td>";
						  //$layout .= "             <td bgcolor=\"".$def_bg_color."\" align=\"center\" ><span class=\"tablecell\" >".$disp_value."</span></td>";
						}
						$layout .= "             </tr>";
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
	<div class="card-header bg-dark">
		<h4>MANUAL COMPOSITION FOR FM <?php echo $FM_get.' - PRODUCT : '.$productCode; ?></h4>
	</div>
	
	<div class="card-body">	
	<form action="ac_submit_composition.php?mode=MANUAL" method="POST">
	<?php
	$sql = "SELECT [WorkingDate]
	  ,[ShiftName]
	  ,[SessionID]
	  ,[SubSessionID]
	  ,[LineCode]
	  ,[FurnanceNumber]
	  ,[ProductCode]
	  ,[Comp_C]
      ,[Comp_Si]
      ,[Comp_Mn]
      ,[Comp_Ti]
      ,[Comp_Ni]
      ,[Comp_Al]
      ,[Comp_V]
      ,[Comp_Zn]
      ,[Comp_Sb]
      ,[Comp_Mo]
      ,[Comp_S]
      ,[Comp_Cu]
      ,[Comp_Sn]
      ,[Comp_Mg]
      ,[Comp_Cr]
      ,[Comp_P]
      ,[Comp_Fe1]
      ,[Comp_Fe2]
      ,[sampleName]
	  ,[RecID]
	FROM [PRD].[dbo].[pouring_lines]
	WHERE RecID = '$recid'";
	$stmt = sqlsrv_query($conn, $sql);
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
		<div class="row mb-2">				
			<div class="col-2">	
				C
				<input type="hidden" class="form-control" id="recid" name="recid" value="<?php echo $recid; ?>" readonly required>						 
				<input type="text" class="form-control" id="raw_c" name="raw_c" value="<?php echo $row['Comp_C']; ?>" required>						 
			</div>
			<div class="col-2">	
				Si
				<input type="text" class="form-control" id="raw_si" name="raw_si" value="<?php echo number_format((float)$row['Comp_Si'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Mn
				<input type="text" class="form-control" id="raw_mn" name="raw_mn" value="<?php echo number_format((float)$row['Comp_Mn'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				S
				<input type="text" class="form-control" id="raw_s" name="raw_s" value="<?php echo number_format((float)$row['Comp_S'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Cu
				<input type="text" class="form-control" id="raw_cu" name="raw_cu" value="<?php echo number_format((float)$row['Comp_Cu'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Sn
				<input type="text" class="form-control" id="raw_sn" name="raw_sn" value="<?php echo number_format((float)$row['Comp_Sn'], 6, '.', ''); ?>" required>						 
			</div>
		</div>
		
		<div class="row mb-2">				
			<div class="col-2">	
				Cr
				<input type="text" class="form-control" id="raw_cr" name="raw_cr" value="<?php echo number_format((float)$row['Comp_Cr'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				P
				<input type="text" class="form-control" id="raw_p" name="raw_p" value="<?php echo number_format((float)$row['Comp_P'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Zn
				<input type="text" class="form-control" id="raw_zn" name="raw_zn" value="<?php echo number_format((float)$row['Comp_Zn'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Al
				<input type="text" class="form-control" id="raw_al" name="raw_al" value="<?php echo number_format((float)$row['Comp_Al'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Ti
				<input type="text" class="form-control" id="raw_ti" name="raw_ti" value="<?php echo number_format((float)$row['Comp_Ti'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Mg
				<input type="text" class="form-control" id="raw_mg" name="raw_mg" value="<?php echo number_format((float)$row['Comp_Mg'], 6, '.', ''); ?>">						 
			</div>
		</div>
		
		<div class="row mb-2">				
			<div class="col-2">	
				Ni
				<input type="text" class="form-control" id="raw_ni" name="raw_ni" value="<?php echo number_format((float)$row['Comp_Ni'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				V
				<input type="text" class="form-control" id="raw_v" name="raw_v" value="<?php echo number_format((float)$row['Comp_V'], 6, '.', ''); ?>">						 
			</div>
			<div class="col-2">	
				Mo
				<input type="text" class="form-control" id="raw_mo" name="raw_mo" value="<?php echo number_format((float)$row['Comp_Mo'], 6, '.', ''); ?>">						 
			</div>
			<div class="col-2">	
				Sb
				<input type="text" class="form-control" id="raw_sb" name="raw_sb" value="<?php echo number_format((float)$row['Comp_Sb'], 6, '.', ''); ?>" required>						 
			</div>
			<div class="col-2">	
				Fe1
				<input type="text" class="form-control" id="raw_fe1" name="raw_fe1" value="<?php echo number_format((float)$row['Comp_Fe1'], 6, '.', ''); ?>">						 
			</div>
			<div class="col-2">	
				Fe2
				<input type="text" class="form-control" id="raw_fe2" name="raw_fe2" value="<?php echo number_format((float)$row['Comp_Fe2'], 6, '.', ''); ?>">						 
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-12">	
				Nama Sample
				<input type="text" class="form-control" id="sample" name="sample" value="<?php echo $row['sampleName']; ?>">						 
			</div>
		</div>
	<?php } ?>
		<div class="row">	
			<button type="submit" class="btn btn-primary btn-block">SIMPAN</button>
		</div>
	</form>
	</div>
	<?php } ?>
	</div>

</div>


</body> 
</html>