<?php
require "../conf/dbkoneksi.php";

try {
        $transid = $_POST['transid'];
        $productcode = $_POST['productcode'];
        $sql_picture = '';
        $sql_picture .= "INSERT INTO [$databaseName].[dbo].[inspection_lines_microstructure]
                ([TransID], [ProductCode], [Picture], [PictureNo])
            VALUES";
        $eventCount = count($_FILES);
        for ($i = 0; $i < $eventCount; $i++) {
            $path = $_FILES['file'. $i+1]['full_path'];
            $temp = $_FILES['file'. $i+1]['tmp_name'];
            
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($temp);
            
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $noloop = $i+1; 
            $sql_picture .= "(
                    '$transid',
                    '$productcode',
                    '$base64',
                    '$i'" . (($i + 1) == $eventCount ? ')' : '),'); 						 
        }	
    // var_dump($sql_picture);die();

    $stmtLineHardness = sqlsrv_query($conn, $sql_picture);

    if($stmtLineHardness){
        echo $msg = "Data Berhasil di Input";
    }
}catch(Exception $e) {
	echo $msg = "Data Gagal di Input!". $e;
}