<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$host = gethostname();
$HostName = $_SERVER['REMOTE_ADDR'];

$serverName = "$host\SQLEXPRESS"; 
$uid = "u_prd";   
$pwd = "atiprd"; 
$databaseName = "QA_INS"; 

// $serverName = "ATIS05"; 
// $uid = "u_prd";   
// $pwd = "atiprd"; 
// $databaseName = "PRD"; 

$connectionInfo = array( 
                        "UID"=>$uid,                            
                        "PWD"=>$pwd,                            
                        "Database"=>$databaseName,
                        "ReturnDatesAsStrings" => true
                        ); 
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// Under Develop
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

// GET STATE
    $get_state = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
    $stmt_state = sqlsrv_query($conn, $get_state);
    while($row_get_state = sqlsrv_fetch_array($stmt_state, SQLSRV_FETCH_ASSOC))	
    {
        $Session = $row_get_state['Session'];
        $AssetID = $row_get_state['AssetID'];
    }

$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://".$_SERVER['HTTP_HOST'];
$config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

// if($conn){
//     echo 'CONNECT';
// }else {
//     echo 'NOT';
// }
?>