<style>
  /* Flexbox */
.flex-container {
  display: flex;
  align-items: center;
}
</style>
<?php $date = date('l, d-M-Y'); 

// GET STATE
    $get_state = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
    // var_dump($get_state);
    $stmt_state = sqlsrv_query($conn, $get_state);
    while($row_get_state = sqlsrv_fetch_array($stmt_state, SQLSRV_FETCH_ASSOC))	
    {
        $Session = $row_get_state['Session'];
        $AssetID = $row_get_state['AssetID'];
        $TransID = $row_get_state['TransID'];
    }

    //GET TRANS
    $sql_trans = "SELECT TOP 1
    TR.[TransID]
    ,TR.[WorkingDate]
    ,FORMAT(TR.[WorkingDate], 'dd-MMM-yyyy') HariKerja
    ,TR.[ShiftName]
    ,US.[Picture]
    ,US.[EmployeeName]
    ,FORMAT(TR.[StartTime], 'HH:mm') StartTime
    ,FORMAT(TR.[FinishTime], 'HH:mm') FinishTime
    FROM [$databaseName].[dbo].[inspection_trans] TR
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] US ON US.[EmpID] = TR.[OperatorID]
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_state] ST ON ST.[TransID] = TR.[TransID]
    WHERE ST.Session = 'ACTIVE' AND TR.TransID = '$TransID'";
    $stmt_trans = sqlsrv_query($conn, $sql_trans);
    $row_get_trans = sqlsrv_fetch_array($stmt_trans, SQLSRV_FETCH_ASSOC);
?>
<nav class="navbar navbar-expand-md navbar-info fixed-top bg-primary" style="padding:0px;">
	<div class="container-fluid">
		<a class="navbar-brand" href="<?php echo  $config['base_url']; ?>">
			<span class="d-inline-block align-text-bottom">
				<img src="../assets/img/ATIbig.png" alt="" width="40rem" height="20rem">
			</span>
			<span class="d-inline-block align-text-middle"
				style="text-indent: 0.1em; color : white; font-weight : bold; font-size : 1.2rem;">
				MATERIAL INSPECTION
			</span>
		</a>
    
		<div class="d-flex text-left">
      <h6 style="font-size:12px;"><?php echo $date . '<br>' . '<span id="jam"></span>'; ?></h6>
      <?php if($Session != 'ACTIVE'){ ?>
         <a href="report.php" class="btn btn-sm ml-2 btn-success"><i class="fa fa-sign-in-alt"></i>Login</a>
       <?php } ?>
     <!-- <div class="card" style="width: 100px; height: 60px ; margin:0px; padding:0px">
                    <div class="row">
                        <div class="img-square-wrapper img-responsive col-lg-4">
                            <img class="p-3 pr-0"
                                src="data:image;base64, <?= base64_encode($row_get_trans['Picture']) ?>" width="10"
                                height="20" alt="Card image cap">
                        </div>
                        <div class="card-body col-lg-8">
                            <p class="card-text" style="font-size:8px; margin-bottom: 2px;"><b>
                                <?= $row_get_trans['EmployeeName'] ?> </b></p>
                            <p class="card-text" style="font-size:8px; margin-bottom: 2px;">
                                <?= date('d M Y', strtotime($row_get_trans['WorkingDate'])) ?></p>
                            <p class="card-text" style="font-size:8px; margin-bottom: 2px;">
                                <?= $row_get_trans['ShiftName'] ?></p>
                        </div>
                    </div> -->
      <!-- <div class="flex-container float-right">
            <img src="data:image;base64, <?= base64_encode($row_get_trans['Picture']) ?>" width="40" height="50" alt="Card image cap">
            <div>
              <span class="card-text" style="font-size:11px;"><b><?= $row_get_trans['EmployeeName'] ?> </b</span><br />
              <span class="card-text" style="font-size:11px;"><?= date('d M Y', strtotime($row_get_trans['WorkingDate'])) ?></span><br/>
              <span class="card-text" style="font-size:11px;"><?= $row_get_trans['ShiftName'] ?></span>
            </div>
      </div> -->
    </div>
  </div>
</nav>

<style>
	#overlay{	
  position: fixed;
  top: 0;
  z-index: 100;
  width: 100%;
  height:100%;
  display: none;
  background: rgba(0,0,0,0.6);
}
.cv-spinner {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;  
}
.spinner {
  width: 40px;
  height: 40px;
  border: 4px #ddd solid;
  border-top: 4px #2e93e6 solid;
  border-radius: 50%;
  animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
  100% { 
    transform: rotate(360deg); 
  }
}
.is-hide{
  display:none;
}
</style>
