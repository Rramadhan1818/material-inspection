<?php

include('query/Q_get_lines_cms.php');
?>
<!-- Modal-->
<div class="modal fade" id="modal_casting" tabindex="-1" aria-labelledby="modal_castingLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width:1245px;">
        <div class="modal-content pt-2">
            <div class="modal-header p-0" style="width: 100%">
                <h5 class="modal-title" id="modal_castingLabel">Pilih Composition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <!-- <div class="row mb-2">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-block btn-success btn-xl" style="font-size:22px">DISA</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-block btn-success btn-xl" style="font-size:22px">ACE</button>
                        </div>
                    </div> -->
                    <div class="row">
                        <table id="tbl_casting" class="table table-sm table-striped table-hover text-center" style="font-size:11px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>PartName</th>
                                    <th>Line Name</th>
                                    <th>FM</th>
                                    <th>Sample</th>
                                    <th>Laddle</th>
                                    <th>C</th>
                                    <th>Si</th>
                                    <th>Mn</th>
                                    <th>S</th>
                                    <th>Cu</th>
                                    <th>Sn</th>
                                    <th>Cr</th>
                                    <th>P</th>
                                    <th>Zn</th>
                                    <th>Al</th>
                                    <th>Ti</th>
                                    <th>Mg</th>
                                    <th>Ni</th>
                                    <th>V</th>
                                    <th>Mo</th>
                                    <th>Sb</th>
                                    <th>Fe1</th>
                                    <th>Fe2</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 1;
                                while($row = sqlsrv_fetch_array($stmt_report, SQLSRV_FETCH_ASSOC)) { 
                            ?>
                            <tr id="row-casting" 
                            data-prdcode="<?= $row['ProductCode'] ?>" 
                            data-session="<?= $row['SessionID'] ?>" 
                            data-alias="<?= $row['AliasName'] ?>" 
                            data-cuscode="<?= $row['CustomerCode'] ?>" 
                            data-cusname="<?= $row['CustomerName'] ?>"
                            data-sample="<?= $row['Sample']?>"
                            style="cursor:pointer;">
                                <td><?= $i++ ?></td>
                                <td><?= $row['PartName'] ?></td>
                                <td><?= $row['LineName'] ?></td>
                                <td><?= $row['FurnanceNumber']?></td>
                                <td><?= $row['Sample']?></td>
                                <td><?= $row['Laddle']?></td>
                                <td><?= $row['Comp_CAct']?></td>
                                <td><?= $row['Comp_SiAct']?></td>
                                <td><?= $row['Comp_MnAct']?></td>
                                <td><?= $row['Comp_SAct']?></td>
                                <td><?= $row['Comp_CuAct']?></td>
                                <td><?= $row['Comp_SnAct']?></td>
                                <td><?= $row['Comp_CrAct']?></td>
                                <td><?= $row['Comp_PAct']?></td>
                                <td><?= $row['Comp_ZnAct']?></td>
                                <td><?= $row['Comp_AlAct']?></td>
                                <td><?= $row['Comp_TiAct']?></td>
                                <td><?= $row['Comp_MgAct']?></td>
                                <td><?= $row['Comp_NiAct']?></td>
                                <td><?= $row['Comp_VAct']?></td>
                                <td><?= $row['Comp_MoAct']?></td>
                                <td><?= $row['Comp_SbAct']?></td>
                                <td><?= $row['Comp_Fe1']?></td>
                                <td><?= $row['Comp_Fe2']?></td>
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




