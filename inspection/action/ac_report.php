<?php
require "../conf/dbkoneksi.php";
header('Content-Type: application/json; charset=utf-8');

    if(isset($_POST['submit_laporan'])) {
	$range1 = $_GET['date_range'];
	$range2 = $_GET['date_range2'];
	$tool = $_GET['tool_name'];
	
	// GET Form FN
        $sql_report = "DECLARE
	@D1 datetime = '$range1'
	,@D2 datetime = '$range2'
	,@ShiftName varchar(30) = 'ALL'
	,@AlatUkur varchar(250) = '$tool'
	,@StandarID varchar(8) = 'STD1'
	SELECT * FROM [PRD].[dbo].[fn_kalibrasi_qa_dialgauge](@D1,@D2,@ShiftName,@AlatUkur,@StandarID)";

	$stmt_report = sqlsrv_query($conn, $sql_report);
        $stmt_column = sqlsrv_query($conn, $sql_col);
        $stmt7 = sqlsrv_prepare( $conn, $sql7);
				sqlsrv_execute( $stmt7 );
		
        header('Location: ../../../application');
    }