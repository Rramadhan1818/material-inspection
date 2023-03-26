<?php
date_default_timezone_set("Asia/Jakarta");
$workingdate_new = date('Y-m-d H:i');
$workingdate = date('Y-m-d');
$tgl_pertama = date('Y-m-01', strtotime($workingdate));
$tgl_terakhir = date('Y-m-t', strtotime($workingdate));

// $get_partlist ="SELECT DISTINCT 
// PRD.[ProductCode] 
// ,PRD.[PartNumber] 
// ,PRD.[CustomerCode] 
// ,PRD.[CustomerCode] 
// ,PRD.[PartName] 
// ,INS.[AliasName] 
// ,INS.[MaterialGrade] 
// ,CAS.[MaterialType] 
// ,CAS.[PartType]  
// ,CUS.[CustomerName] 
// FROM [ATI].[dbo].[PRODUCT_TABLE] PRD 
// LEFT OUTER JOIN [QA_INS].[dbo].[mt_part_inspection] INS ON INS.ProductCode = PRD.ProductCode 
// LEFT OUTER JOIN [PRD].[dbo].[mt_product_casting] CAS ON CAS.ProductCode = PRD.ProductCode 
// LEFT OUTER JOIN [ATI].[dbo].[CUSTOMER_TABLE] CUS ON CUS.CustomerCode = PRD.CustomerCode";

$get_partlist = "SELECT * FROM (
	SELECT PRD.*
	, CUS.CustomerName
	,CAS.[AliasName] 
	,CAS.[MaterialGrade] 
	,CAS.[MaterialType] 
	,CAS.[PartType] 
	FROM (
		SELECT ProductCode
		, CustomerCode
		, PartNumber
		, PartName
		FROM [ATI].[dbo].[PRODUCT_TABLE]
		WHERE CustomerCode IS NOT NULL
	) PRD
	LEFT OUTER JOIN [ATI].[dbo].[CUSTOMER_TABLE] CUS ON CUS.CustomerCode = PRD.CustomerCode
	OUTER APPLY 
	(
		SELECT TOP 1  ProductCode, [AliasName],[MaterialGrade],[MaterialType], [PartType] FROM [PRD].[dbo].[mt_product_casting] 
		WHERE ProductCode = PRD.ProductCode
	) CAS
) TM
WHERE MaterialType IS NOT NULL";

// var_dump($get_partlist);
$stmt_part = sqlsrv_query($conn, $get_partlist);
?>
<!-- Modal-->
<div class="modal fade" id="modal_get_part" tabindex="-1" aria-labelledby="modal_castingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width:1245px;">
        <div class="modal-content pt-2">
            <div class="modal-header p-0" style="width: 100%">
                <h5 class="modal-title" id="modal_castingLabel">Pilih Composition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <table id="tbl_part" class="table table-sm table-striped table-hover text-center" style="font-size:11px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Production Code</th>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Customer Name</th>
                                    <th>Material Grade</th>
                                    <th>Material Type</th>
                                    <!-- <th>Picture</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 1;
                                while($row = sqlsrv_fetch_array($stmt_part, SQLSRV_FETCH_ASSOC)) { 
                            ?>
                            <tr id="row-part" 
                            data-prdcode="<?= $row['ProductCode'] ?>" 
                            data-alias="<?= $row['AliasName'] ?>" 
                            data-cuscode="<?= $row['CustomerCode'] ?>" 
                            data-cusname="<?= $row['CustomerName'] ?>"
                            data-type="<?= $row['MaterialType'] ?>"
                            data-grade="<?= $row['MaterialGrade'] ?>"
                            style="cursor:pointer;">
                                <td><?= $i++ ?></td>
                                <td><?= $row['ProductCode'] ?></td>
                                <td><?= $row['PartNumber'] ?></td>
                                <td><?= $row['AliasName'] ?></td>
                                <td><?= $row['CustomerName'] ?></td>
                                <td><?= $row['MaterialGrade'] ?></td>
                                <td><?= $row['MaterialType'] ?></td>
                                <!-- <td> 
                                    <?php if(isset($row['Picture'])){ ?>
                                        <img src="data:image;base64, <?= $row['Picture'] ?>" width="100px !important;" style="margin: auto; display: block;">
                                    <?php }else{ ?>
                                        <img src="http://placehold.it/100" width="50px !important;" style="margin: auto; display: block;">
                                    <?php } ?>
                                </td> -->
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




