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

    $get_lines = "SELECT 
    CL.[WorkingDate],
    CL.[ShiftName],
    CL.[TransID],
    CL.[IdentificationNumber],
    CL.[EventCode],
    CL.[Value],
    CL.[RecID],
    MT.[ParamName],
    MT.[TypeFields],
    MTT.[ClassName] AS ToolName,
    CT.[OperatorID],
    UL.[EmployeeName]
    FROM [$databaseName].[dbo].[calibration_lines] CL
    LEFT OUTER JOIN [$databaseName].[dbo].[calibration_trans] CT ON CT.[TransID] = CL.[TransID]  
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] UL ON UL.[EmpID] = CT.[OperatorID]  
    LEFT OUTER JOIN [$databaseName].[dbo].[mt_measure] MT ON MT.[EventCode] = CL.[EventCode]  
    LEFT OUTER JOIN [ATI].[dbo].[QA_MEA_TOOL_M_CLASS] MTT ON MTT.[ToolCode] = CL.[ToolCode]  
    WHERE CL.ToolCode = 'T001' AND MT.[TypeFields] = 'Text'
    ORDER BY CL.[IdentificationNumber] ASC";
    $stmt_lines = sqlsrv_query($conn, $get_lines);
    $rowtool = sqlsrv_fetch_array($stmt_lines, SQLSRV_FETCH_ASSOC);
    if(isset($rowtool)){
        $toolName = $rowtool['ToolName'];
    }
?>

<style>
        body {
            background-color: #eee;
        }

        .container-custom {
            /* max-width: 1250px; */
			max-width: 90%;
            padding-right: 6.5px;
            padding-left: 6.5px;
            margin-right: auto;
            margin-left: auto;
        }

        .card-horizontal {
            display: flex;
            flex: 1 1 auto;
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

        div.dt-buttons {
            position: relative;
            float: left;
            margin-left: 1rem;
        }

        .error {
            color: red ;
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
        <div class="row">
            <div class="card bg-warning" style="margin-bottom:0.5rem">
                <div class="card-body">
                <h6 style="font-weight: bold;">UPDATE REPORT</h6>
                <form class="px-2 form" id="filter-update" name="filter-update" action="" method="POST">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Jenis Alat</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" aria-label="Default select example" id="tool_name" name="tool_name">
                            <option value="T001" selected>DIAL GAUGE</option>
                            <option value="T004" >TORQUE WRENCH</option>
                        </select>
                    </div>
                    <!-- <div class="col-auto stdClass">
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
                    </div> -->
                    <div class="col-auto">
						<label for="inputPassword6" class="col-form-label">Date</label>
					</div>
					<div class="col-5">
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" name="date_range" id="date_range" class="form-control" value="">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
					<div class="col-auto">	
						<button type="submit" id="submit_update" name="submit_update" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>		
					</div>
                </div>
                </form>
                <?php 
                    if(isset($_POST['submit_update'])) {
                    $date_range = '';    
                    $tool = ''; 

                    $date_range = $_POST['date_range'];
                    $tool = $_POST['tool_name'];
                    
                    $get_lines = "SELECT 
                    CL.[WorkingDate],
                    CL.[ShiftName],
                    CL.[TransID],
                    CL.[IdentificationNumber],
                    CL.[EventCode],
                    CL.[Value],
                    CL.[RecID],
                    MT.[ParamName],
                    MT.[TypeFields],
                    MTT.[ToolName],
                    CT.[OperatorID],
                    UL.[EmployeeName]
                    FROM [$databaseName].[dbo].[calibration_lines] CL
                    LEFT OUTER JOIN [$databaseName].[dbo].[calibration_trans] CT ON CT.[TransID] = CL.[TransID]  
                    LEFT OUTER JOIN [$databaseName].[dbo].[mt_user_line] UL ON UL.[EmpID] = CT.[OperatorID]  
                    LEFT OUTER JOIN [$databaseName].[dbo].[mt_measure] MT ON MT.[EventCode] = CL.[EventCode]  
                    LEFT OUTER JOIN [$databaseName].[dbo].[mt_tool] MTT ON MTT.[ToolCode] = CL.[ToolCode]
                    WHERE CL.WorkingDate = '$date_range' AND CL.ToolCode = '$tool' AND MT.[TypeFields] = 'Text'
                    ORDER BY CL.[IdentificationNumber] ASC";
                        // var_dump($get_lines);die();
                    $stmt_lines = sqlsrv_query($conn, $get_lines);
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
                    <div class="table-responsive">
                        <h6 style="font-weight: bold;">DATA  <?php  if(isset($toolName)){ $toolName; }else{ echo 'DIAL GAUGE';} ?></h6>
                        <table id="tbl_update" class="table table-striped"
                            style=" font-size : 12px;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Operator</th>
                                    <th>TransID</th>
                                    <th>Identification Number</th>
                                    <th>Working Date</th>
                                    <th>Shift Name</th>
                                    <th>Column Name</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; while($rowtool = sqlsrv_fetch_array($stmt_lines, SQLSRV_FETCH_ASSOC)) { 
                                $toolName = $rowtool['ToolName']
                                    ?>
                                <?php if($rowtool){ ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $rowtool['EmployeeName'] ?></td>
                                    <td><?= $rowtool['TransID'] ?></td>
                                    <td><?= $rowtool['IdentificationNumber'] ?></td>
                                    <td><?= date('d M Y', strtotime($rowtool['WorkingDate'])) ?></td>
                                    <td><?= $rowtool['ShiftName'] ?></td>
                                    <td class="bg-primary"><?= $rowtool['ParamName'] ?></td>
                                    <td class="editable-col"><?= $rowtool['Value'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success btn-recid" data-recid="<?= $rowtool['RecID'] ?>" data-val="<?= $rowtool['Value'] ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php }else {  echo 'Data Belum Ada'; } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
            </div>
        </div>
    </div>
    <?php require "ui/modal_update.php"; ?>

    <script>
        $(document).on("click", ".btn-recid", function () {
        var val = $(this).data('val');
        var recid = $(this).data('recid');
        $("#Value").val(val);
        $("#RecID").val(recid);
    });
    
        $("#submitUpdate").click(function (e) {
            e.preventDefault();

            var value_update = $("#Value").val();
            var recid = $("#RecID").val();

            $.ajax({   
                type: "POST",  
                url: "action/ac_update_calibration.php",  
                data: { recid, value_update },
                success: function(data)  
                {   
                    Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            text: data,
                            showConfirmButton: true,
                            closeOnClickOutside: true,
                            allowOutsideClick: true
        
                        }).then(okay => {
                            if (okay) {
                                window.location.href = 'update-page.php';
                            }
                        });
                }
                ,error: function(data)
                {
                    Swal.fire({
                            position: 'top-center',
                            icon: 'error',
                            text: 'ERROR' + data,
                            showConfirmButton: true,
                            closeOnClickOutside: false,
                            allowOutsideClick: false
        
                            }).then(okay => {
                                if (okay) {
                                    location.reload();
                                }
                            });
                }
            });
        });
        // $('td.editable-col').on('focusout', function() {
        //     console.log(data, 'cek');
        //     data = {};
        //     data['val'] = $(this).text();
        //     data['id'] = $(this).parent('tr').attr('data-row-id');
        //     data['index'] = $(this).attr('col-index');

        //     if($(this).attr('value') === data['val'])
        //     return false;

        //     $.ajax({   
        //         type: "POST",  
        //         url: "ac_update_taping.php",  
        //         cache:false,  
        //         data: data,
        //         dataType: "json",				
        //         success: function(response)  
        //         {   
        //             //$("#loading").hide();
        //             if(!response.error) {
                        
        //                 //window.location.reload();
                        
        //             } else {
        //                 $("#msg").removeClass('alert-success');
        //                 $("#msg").addClass('alert alert-danger').html(response.msg);
        //             }
        //         }   
        //     });
	    // });

        $('.input-append.date').datepicker({
            format: 'mm/dd/yyyy',
            startDate: '-3d'
        });

        $('#tbl_update').DataTable( {
			searchBuilder: true,
            dom: 'Blfrtip',
            pageLength: 30,
            lengthMenu: [30, 60, 80, 100, 150, 250, 500],
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

    // $("#date-range").daterangepicker({
	// 	autoUpdateInput: true,
    //     dateFormat: 'YYYY/MM/DD',
	// 	locale: {
	// 	cancelLabel: 'Clear',
	// 	}
	// });

	$("#date-range").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
	});

	$("#date-range").on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
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