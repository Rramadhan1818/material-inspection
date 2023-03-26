<!-- Modal-->
<style>
    body {
        margin: 0;
        height: 100%;
        overflow-x: hidden
    }
</style>

<div class="modal fade" id="modal_get_cms" tabindex="-1" aria-labelledby="modal_get_cmsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width:1345px;">
        <div class="modal-content pt-2">
            <div class="modal-header p-0" style="width: 100%">
                <h5 class="modal-title" id="modal_get_cmsLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="preloader">
                    <div class="loading">
                        <center><img src="../../assets/img/load.gif" width="70"><br>
                            <label style="color :white;">Processing..</label></center>
                    </div>
                </div>

                <?php
                $get_area = "SELECT TOP 1 * FROM [$databaseName].[dbo].[mt_state] WHERE IP = '$HostName' " ;
                $stmt_area = sqlsrv_query($conn, $get_area);
                $row_get_area = sqlsrv_fetch_array($stmt_area, SQLSRV_FETCH_ASSOC);

                var_dump($row_get_area['LineName']);
                    $AreaName = $row_get_area['LineName'];
                    if($AreaName == 'ACE')
                    {
                        $serverComposition = "10.123.230.185";
                    }else{
                        $serverComposition = "10.123.230.186";
                    }
                    $userComposition = "aas";
                    $passwordComposition = "andon";
                    $conn_composition = mysqli_connect($serverComposition, $userComposition, $passwordComposition);
                ?>
                <?php if($conn_composition) { ?>
                <div class="card-body">
                    <div class="row">
                        <?php
                                // *** Initialize Local Variables & Constants
                                $second = date('s');
                                $odd_sec = $second%2;
                                $values = array("","","","","","","","","","","","","","","","","","","","");
                                $tr_time = array("","","","","","","","","","","","","","","","","","","","");
                                $furnace = array("","","","","","","","","","","","","","","","","","","","");
                                $samples = array("","","","","","","","","","","","","","","","","","","","");
                                $laddle = array("","","","","","","","","","","","","","","","","","","","");
                                $spektro = array("","","","","","","","","","","","","","","","","","","","");
                                $max_show = 15;
                                $data = "";
                                $idx_data_last = 0;
                                $idx_data_next = 0;
                                $idx_sample = 0;
                                $idx_value = 0;
                                $idx_status = 0;
                                
                                //make blink
                                $detiku = date('s');
                                $modi = fmod($detiku,2); 
                                ?>

                        <?php
                                //table output layout
                                $elements = array("C","Si","Mn","S","Cu","Sn","Cr","P","Zn","Al","Ti","Mg","Ni","V","Mo","Sb","Fe1","Fe2");
                                $layout = "";
                                $layout .= "<table class=\"table-striped\">";
                                // Table Header
                                $layout .= "<tr align=\"center\">";
                                $layout .= "<th width=\"2%\" ><span class=\"tableheader\" >NO</span></th>";
                                $layout .= "<th width=\"8%\" ><span class=\"tableheader\" >TIME</span></th>";
                                $layout .= "<th width=\"2%\" ><span class=\"tableheader\" >FM</span></th>";
                                $layout .= "<th width=\"10%\" ><span class=\"tableheader\" >SAMPLE</span></th>";
                                $layout .= "<th width=\"4%\" ><span class=\"tableheader\" >LADDLE</span></th>";
                                $width_cell = round(80/count($elements),2);
                                for ($i=0;$i<count($elements);$i++) {
                                    $layout .= "<th width=\"".$width_cell."%\" ><span class=\"tableheader\" >".$elements[$i]."</span></th>";
                                }
                                //$layout .= "<th width=\"2%\" ><span class=\"tableheader\" >OPSI</span></th>";
                                $layout .= "</tr>";

                                // Table Data
                                //$query = "SELECT * FROM tdata_log ORDER BY fid DESC LIMIT $max_show";
                                // $query = "SELECT * FROM tdisplay WHERE fdisplay_name = 'LOCAL' ORDER BY fid DESC";
                                $result = mysqli_query($conn_composition, "SELECT fid
                                , ftr_time
                                , ffurnace
                                , fsample
                                , fladdle
                                , fspektro
                                , fdisplay_name
                                , fshow
                                FROM db_cms.tdisplay");
                                $num_rows = mysqli_num_rows($result);
                                // var_dump($num_rows);die;
                                $i=$num_rows;
                                if ($num_rows > 0) {
                                while ( $row = mysqli_fetch_array($result) ) {
                                    if ($i>=($num_rows-$max_show)) {
                                    if ($i%2) {
                                        $def_bg_color = "#33FFFF";
                                    } else {
                                        $def_bg_color = "white";
                                        }
                                        $layout .= "<tr align=\"center\" >";
                                        $fid[$i] = $row['fid'];
                                        $tr_time[$i] = $row['ftr_time']; $pos_sp = strpos($tr_time[$i]," ");
                                        $tr_time[$i] = substr($tr_time[$i],($pos_sp+1),(strlen($tr_time[$i])-$pos_sp-4));
                                        $furnace[$i] = substr($row['ffurnace'],2,(strlen($row['ffurnace'])-2));
                                        $samples[$i] = $row['fsample'];
                                        $laddle[$i] = $row['fladdle'];
                                        $spektro[$i] = $row['fspektro'];
                                        $kodeloe[$i] = $row['fsample'];
                                        $spek[$i] = $row['fspektro'];
                                        // var_dump($spek[$i]);die; 

                                        $idx_data_next = strpos($spektro[$i],"#"); //int(8) #=8 :=6
                                        // echo $spektro[$i];
                                        
                                        $data_element[0] = "";
                                        $data_value[0] = "";
                                        $data_status[0] = "";
                                        $j=1;
                                        while ($idx_data_next > 0) {
                                        $data = substr($spektro[$i],$idx_data_last,($idx_data_next-$idx_data_last)); //string(8) "C=3.35:0"
                                        $pos_eq = strpos($data,"="); //int(3)
                                        $pos_dz = strpos($data,":"); //int(10)
                                        $data_element[$j] = substr($data,0,$pos_eq); //element unsur
                                        $data_value[$j] = substr($data,($pos_eq+1),($pos_dz-$pos_eq-1)); //value hasil cms
                                        $data_status[$j] = substr($data,($pos_dz+1),(strlen($data)-$pos_dz)); //status 0 belakang
                                            $cms =  $data_element[$j]."=".$data_value[$j];
                                            $spektro[$i] = substr($spektro[$i],($idx_data_next + 1),(strlen($spektro[$i])-$idx_data_next));
                                            $idx_data_next = strpos($spektro[$i],"#");
                                        if ((empty($idx_data_next))) {
                                            $idx_data_next = strlen($spektro[$i]);
                                        }
                                        $j++;
                                        }
                                        $spektro_data = str_replace('#','-',$spek[$i]);
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" align=\"center\" ><span class=\"tablecell\" >".($i)."</span></td>";
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"8%\" align=\"center\" ><span class=\"tablecell\" >".$tr_time[$i]."</span></td>";
                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"2%\" ><span class=\"tablecell\" >".$furnace[$i]."</span></td>";
                                        
                                        $num_rows1 = 0;
                                        $query1 = "SELECT fcode FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}'";
                                        
                                        $query_count = mysqli_query($conn_composition, "SELECT COUNT(fcode) fcode FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}'");
                                        // var_dump($query_count);die();
                                        $num_rows1 = $query_count;
                                        
                                        // echo $num_rows1."<br/>";
                                        // if ($num_rows1 > 0) {
                                        //     $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"10%\" ><span class=\"tablecell bg-warning\" ><a type=\"submit\" class=\"button btn-lg btn-primary\" href=\"composition_konfirm.php?mode=AMBIL&recid=$recid&analyst=$tr_time[$i]&fm=$furnace[$i]&sample=$kodeloe[$i]&laddle=$laddle[$i]&qty=$spektro_data&svr=$serverComposition\">".$samples[$i]."</a></span></td>";
                                        // } else {
                                        //     $layout .= "<td bgcolor=\"bg-warning\"  width=\"10%\" ><span class=\"tablecell\" ><a type=\"submit\" class=\"button btn-lg btn-success btn-block\" href=\"composition_konfirm.php?mode_page=$mode_page&mode=AMBIL&recid=$recid&analyst=$tr_time[$i]&fm=$furnace[$i]&sample=$kodeloe[$i]&laddle=$laddle[$i]&qty=$spektro_data&svr=$serverComposition\">".$samples[$i]."</a></span></td>";
                                        // }

                                        $layout .= "<td bgcolor=\"".$def_bg_color."\" width=\"4%\" ><span class=\"tablecell\" >".$laddle[$i]."</span></td>";

                                        for ($k=0;$k<count($elements);$k++) {
                                            $searchTerm = $elements[$k];
                                            $pos = array_search($searchTerm,$data_element);

                                            $red_min = 0;
                                            $red_max = 0;
                                            $blue_min = 0;
                                            $blue_max = 0;

                                            $sql = "SELECT fid, fcode, felement, fmin, fmax FROM db_cms.tstandar WHERE fcode = '{$kodeloe[$i]}' and felement = '$searchTerm'";
                                    $resultsqlstd = mysqli_query($conn_composition,$sql);
                                    
                                    while($queryku2 = mysqli_fetch_array($resultsqlstd))	
                                    {
                                    $red_min = $queryku2['fmin'];
                                    $red_max = $queryku2['fmax'];
                                    }
                                    
                                    $adasample = 0;
                                    if($red_min != ""){
                                    $adasample = 1; 
                                    }

                                    if ($i%2) {
                                        $def_bg_color = "#33FFFF";
                                    } else {
                                        $def_bg_color = "white";
                                    }
                                    if (empty($pos)) { $disp_value = ""; }
                                    else {
                                        $disp_value = $data_value[$pos];
                                        $disp_status = intval($data_status[$pos]);
                                        switch ($disp_status) {
                                            case 1 :
                                            if ($odd_sec) { $def_bg_color = "red"; }
                                            break;
                                            case 2 :
                                            $def_bg_color = "yellow";
                                            break;
                                        }
                                    }
                                    
                                    $disp_value = $data_value[$pos]; 
                                    if($disp_value < $red_min || $disp_value > $red_max){
                                        $def_bg_color = "red";
                                        
                                    }else{
                                            if ($i%2) {
                                            $def_bg_color = "#33FFFF";
                                            } else {
                                            $def_bg_color = "white";
                                            }
                                    }
                                    
                                    
                                    if($red_max == ""){
                                            if ($i%2) {
                                            $def_bg_color = "#33FFFF";
                                            } else {
                                            $def_bg_color = "white";
                                            }
                                        }else{
                                            $def_bg_color = $def_bg_color;
                                        }


                                        if($adasample == 0){
                                            $def_bg_color = "#FFFF00";
                                        }
                                    if($def_bg_color == "blue" || $def_bg_color == "red"){
                                        $blink = $def_bg_color;
                                    }else{
                                        $blink = $def_bg_color;
                                    }
                                            $layout .= "<td bgcolor=\"".$blink."\" align=\"center\" ><span class=\"tablecell\" >".$disp_value."</span></td>";
                                        }
                                        $layout .= "</tr>";
                                    }
                                    $i--;
                                    }
                                } else {
                                    $colspan = count($elements)+5;
                                    $layout .= "<tr align=\"center\" >";
                                    $layout .= "<td colspan=\"".$colspan."\" align=\"center\" ><span class=\"tablecell\" >No Data on Display</span></td>";
                                    $layout .= "</tr>";
                                }
                                $layout .="</table>";
                                echo $layout;
                                ?>
                    </div>
                </div>
                <?php }else{ ?>
                <h2>Connection Error !</h2>
                <?php } ?>
                <!-- <div class="table-responsive">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-block btn-success btn-xl" style="font-size:22px">DISA</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-block btn-success btn-xl" style="font-size:22px">ACE</button>
                        </div>
                    </div>
                    <div class="row">
                        <table id="tbl_casting" class="table table-sm table-striped table-hover text-center" style="font-size:11px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Time</th>
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
                                    <th>Fe</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>