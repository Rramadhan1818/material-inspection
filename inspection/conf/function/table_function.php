<?php

function get_table_header($sql, $conn){
	//echo $sql;
	$stmt = sqlsrv_query( $conn, $sql );
	foreach(sqlsrv_field_metadata($stmt) as $field){
		echo $field['Name'];
		echo $field['Type'];
		echo '<br/>';
	}
	//return isset($sel_result)?$sel_result:0;
}

function get_column($sql, $conn){
	//echo $sql;
	$stmt = sqlsrv_query( $conn, $sql );
	foreach(sqlsrv_field_metadata($stmt) as $field){
		echo '<th>'.$field['Name'].'</th>';
	}
	//return isset($sel_result)?$sel_result:0;
}

function showTable($idTable,$stmt_column,$stmt_value)
{
	echo '<table id="'.$idTable.'" class="table is-striped responsive table-hover nowrap" style="width:100%">';
	echo '<thead>';
	echo '<tr>';						
				foreach(sqlsrv_field_metadata($stmt_column) as $field)
				{
					echo '<th>'.$field['Name'].'</th>';
				}					
	echo '</tr>';
	echo '</thead>';				
	echo '<tbody>';
				while( $row = sqlsrv_fetch_array($stmt_value, SQLSRV_FETCH_NUMERIC))
				{ 
	echo'<tr>';
				$numFields = sqlsrv_num_fields($stmt_value);
				for ($rowNum = 0; $rowNum < $numFields; $rowNum++)
					{ 
						echo '<td>'.$row[$rowNum].'</td>'; 
					}										
	echo'</tr>';
				}
	echo'</tbody>';
	echo'</table>';
}

function showTableNoID($stmt_column,$stmt_value)
{
	echo '<table width="100%" class="table-bordered table-responsive table-striped" style="overflow-x:auto;">';
	echo '<thead class="thead-light">';
	echo '<tr style ="text-align : center; font-size : 11px; vertical-align: middle;" >';						
				foreach(sqlsrv_field_metadata($stmt_column) as $field)
				{
					echo '<th>'.$field['Name'].'</th>';
				}					
	echo '</tr>';
	echo '</thead>';				
	echo '<tbody>';
				while( $row = sqlsrv_fetch_array($stmt_value, SQLSRV_FETCH_NUMERIC))
				{ 
	echo'<tr style ="text-align : center; font-size : 11px; vertical-align: middle;" >';
				$numFields = sqlsrv_num_fields($stmt_value);
				for ($rowNum = 0; $rowNum < $numFields; $rowNum++)
					{ 
						echo '<td>'.$row[$rowNum].'</td>'; 
					}										
	echo'</tr>';
				}
	echo'</tbody>';
	echo'</table>';
}

function tableValue($stmt_value)
{
	while( $row = sqlsrv_fetch_array($stmt_value, SQLSRV_FETCH_NUMERIC))
	{ 
		echo'<tr style ="text-align : center; font-size : 11px; vertical-align: middle;" >';
			$numFields = sqlsrv_num_fields($stmt_value);
			for ($rowNum = 0; $rowNum < $numFields; $rowNum++)
				{ 
					echo '<td>'.$row[$rowNum].'</td>'; 
				}										
		echo'</tr>';
	}
}

?>