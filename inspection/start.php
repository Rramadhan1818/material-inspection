<!doctype html>
<html lang="en">

<head>
	<?php
	require "conf/dbkoneksi.php";
	require "ui/header.php";
	require "ui/datatable_plugin.php";
	// require "session.php";

	$sql_cek = "SELECT * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' AND Session = 'ACTIVE'";
	$sql_cek =  sqlsrv_query($conn, $sql_cek);
	while($row_cek = sqlsrv_fetch_array($sql_cek, SQLSRV_FETCH_ASSOC))
	{
		$sessionID = $row_cek['SessionID'];
	}

	date_default_timezone_set("Asia/Jakarta");
	$workingdate_new = date('Y-m-d H:i');
	$StartTime = date('H:i');

?>
	<style>
		body {
			background-color: #eee;
		}

		.the-fieldset {
			border: 1px solid #e0e0e0;
			padding: 10px;
		}

		legend {
			background-color: #fff;
			/* border: 1px solid #ddd; */
			border-radius: 4px;
			color: var(--purple);
			font-size: 17px;
			font-weight: bold;
			padding: 3px 5px 3px 7px;
			width: auto;
		}

		.container-custom {
			/* max-width: 1250px; */
			max-width: 95%;
			padding-right: 5.5px;
			padding-left: 5.5px;
			margin-right: auto;
			margin-left: auto;
		}

		.error {
            color: red ;
        }
		
		.form-group {
			margin-bottom:0rem !important;
		}

			.select2-container--default .select2-selection--single .select2-selection__rendered {
			margin-top: -7px;
		}

		.select2-container .select2-selection--single {
			height: 38px;
		}
	</style>
	<?php 

// GET OPERATOR
$get_mt_op = "SELECT * FROM [ATI].[dbo].[HRD_EMPLOYEE_TABLE]  WHERE DeptName = 'QC' AND PositionCode = 'OPR' ORDER BY EmployeeName ASC";
$stmt_op = sqlsrv_query($conn, $get_mt_op);

// GET LEADER
$get_mt_leader = "SELECT * FROM [ATI].[dbo].[HRD_EMPLOYEE_TABLE] WHERE DeptName = 'QC' AND PositionCode = 'LEA' ORDER BY EmployeeName ASC";
$stmt_led = sqlsrv_query($conn, $get_mt_leader);

//  GET FOREMAN
$get_mt_foreman = "SELECT * FROM [ATI].[dbo].[HRD_EMPLOYEE_TABLE] WHERE DeptName = 'QC' AND PositionCode = 'FRM' ORDER BY EmployeeName ASC";
$stmt_fore = sqlsrv_query($conn, $get_mt_foreman);

?>

</head>

<body>
	<?php require "ui/navbar.php"; ?>
    <center><div id="loading" class="pt-2 pb-2"><div class="spinner-border text-primary text-center" role="status"></div> Loading ...</div></center>

	<div class="container-custom">
		<div class="p-0">
			<div class="card-header p-1 bg-primary">
			</div>
        </div>
		<div class="card">
			<div class="card-body">
					<form name="formTrans" id="formTrans" action="" method="POST">
					<div class="row">
						<div class="col-md-4">
							<center>
								<div class="form-group">
									<fieldset class="the-fieldset">
										<legend class="the-legend">TENTUKAN SHIFT</legend>
										<div class="form-group">
											<div class="form-check form-check-inline">
												<input style="font-size: 22px; font-weight: bold;"
													class="form-check-input" type="radio" id="sp" value="NSP"
													name="ShiftName" checked>
												<label style="font-size: 22px; font-weight: bold;"
													class="form-check-label" for="sp">NS Pagi</label>
											</div>
											<div class="form-check form-check-inline">
												<input style="font-size: 22px; font-weight: bold;"
													class="form-check-input" type="radio" id="sm" value="NSM"
													name="ShiftName">
												<label style="font-size: 22px; font-weight: bold;"
													class="form-check-label" for="sm">NS Malam</label>
											</div>
										</div>
									</fieldset>
								</div>
								<input type="hidden" id="WorkingDate" name="WorkingDate"
									value="<?php echo $workingdate_new; ?>" />
								<input type="hidden" id="StartTime" name="StartTime"
									value="<?php echo $StartTime; ?>" />
							</center>
						</div>
						<div class="col-md-8">
							<center>
								<div class="form-group">
									<fieldset class="the-fieldset">
										<legend class="the-legend">OPERATOR</legend>
										<div class="form-group">
											<div class="form-check form-check-inline">
												<input style="font-size: 22px; font-weight: bold;"
													class="form-check-input" type="radio" id="cms" value="OPCMS"
													name="CK_Operator" checked>
												<label style="font-size: 22px; font-weight: bold;"
													class="form-check-label" for="cms">Operator Atas</label>
											</div>
											<div class="form-check form-check-inline">
												<input style="font-size: 22px; font-weight: bold;"
													class="form-check-input" type="radio" id="check" value="OPCHECK"
													name="CK_Operator">
												<label style="font-size: 22px; font-weight: bold;"
													class="form-check-label" for="sm">Operator Checksheet</label>
											</div>
										</div>
									</fieldset>
								</div>
								<input type="hidden" id="WorkingDate" name="WorkingDate"
									value="<?php echo $workingdate_new; ?>" />
								<input type="hidden" id="StartTime" name="StartTime"
									value="<?php echo $StartTime; ?>" />
							</center>
							<div class="form-group">
								<label class="col-form-label form-control-label">PILIH OPERATOR <span class="error">*</span></label>
								<select class="form-control select2" name="Operator" id="Operator" required>
									<option></option>
									<?php
										while($row = sqlsrv_fetch_array($stmt_op, SQLSRV_FETCH_ASSOC)) { 
										?>
									<option value="<?= $row['EmpID'] ?>"><?= $row['EmployeeName'] ?></option>
									<?php } ?>
								</select>
                                        <span class="err-operator" style="display: none;color: red;">Operator Harus di Isi !</span>
							</div>
							<div class="form-group">
								<label class="col-form-label form-control-label">PILIH LEADER</label>
								<select class="form-control select2" name="Leader" id="Leader">
									<option></option>
									<?php
										while($row = sqlsrv_fetch_array($stmt_led, SQLSRV_FETCH_ASSOC)) { 
										?>
									<option value="<?= $row['EmpID'] ?>"><?= $row['EmployeeName'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="col-form-label form-control-label">PILIH FOREMAN</label>
								<select class="form-control select2" name="Foreman" id="Foreman">
									<option></option>
									<?php
										while($row = sqlsrv_fetch_array($stmt_fore, SQLSRV_FETCH_ASSOC)) { 
										?>
										<option value="<?= $row['EmpID'] ?>"><?= $row['EmployeeName'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group mt-2 row">
								<div class="col-lg-12">
									<div class="btn btn-groups float-right">
										<button class="btn btn-secondary text-white reset" id="reset-start"
											style="font-size : 28px; font-weight: bold;"><i class="fa fa-history"></i>
											RESET</button>
										<button type="submit" name="submitTrans" id="submitTrans" class="btn btn-primary"
											style="font-size : 28px; font-weight: bold;"><i
												class="far fa-save"></i> SIMPAN</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				</div>
		</div>
	</div>

<script>
	$(document).ready(function() {
	
	$('.select2').select2({
	placeholder: "Select an employe",
    allowClear: true
	})

	var op = $('#Operator').val().length;
	$("#reset-start").click(function (e) {
			$('#Operator').val('') 
			$('#Leader').val('') 
			$('#Foreman').val('') 
    });

	$("#submitTrans").click(function (e) {
		e.preventDefault();
			if($('#Operator') == ''){
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
					title: 'Form Tidak Boleh Kosong!'
				})
			}else{
				var form = $("#formTrans");
				$.ajax({
					type: "POST",
					url: 'action/post_start.php',
					data: form.serializeArray(),
					dataType: "json",
					success: function (data) {
						console.log(data.state[0].CK_Operator);

						if(data.state[0].CK_Operator == 'OPCMS'){
							Swal.fire({
								position: 'top-center',
								icon: 'success',
								text: data.msg,
								showConfirmButton: true,
								closeOnClickOutside: true,
								allowOutsideClick: true
	
							}).then(okay => {
									if (okay) {
										window.location.href = 'proses-up.php';
									}
							});
						}else{
							Swal.fire({
								position: 'top-center',
								icon: 'success',
								text: data.msg,
								showConfirmButton: true,
								closeOnClickOutside: true,
								allowOutsideClick: true
							}).then(okay => {
									if (okay) {
										window.location.href = 'proses.php';
									}
							});
						}
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
								form.val('');
								location.reload();
							}
						});
					}
				});
			}
			
		})
	})
</script>
</body>
</html>