<?php
require "../conf/dbkoneksi.php";
// require "../session.php";
// header('Content-Type: application/json; charset=utf-8');
session_start();

    if($_POST){
        $TransID = $_POST['TransID'];
	    $ProductCode = $_POST['ProductCode'];
        $_SESSION['TransID_report'] = $TransID;
        $_SESSION['ProductCode_report'] = $ProductCode;
        echo json_encode( [ 'transid' => $_SESSION['TransID_report'], 'productcode' => $_SESSION['ProductCode_report']] );
    }else{
        echo json_encode( [ 'data' => 'error' ] );
    }
