<!-- <section id="dashboard-analytics"> -->
<?php
session_start();
include 'api/includes/DbOperation.php'; 

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];


if(
    (isset($_GET['ElectionName']) && !empty($_GET['ElectionName'])) &&
    (isset($_GET['Username']) && !empty($_GET['Username'])) &&
    (isset($_GET['fromDate']) && !empty($_GET['fromDate'])) &&
    (isset($_GET['toDate']) && !empty($_GET['toDate'])) 
) 
{
    $ElectionName = $_GET['ElectionName'];
    $Username = $_GET['Username'];
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];

    if(isset($_GET['ExecutiveName']) && !empty($_GET['ExecutiveName'])){
        $ExecutiveName = $_GET['ExecutiveName'];
    }else{
        if(strpos($Username, "_") !== false){
            $executiveArr = explode("_", $Username);
            $ExecutiveName = $executiveArr[0];
        }else{
            $ExecutiveName = $Username;
        }
    }
    


    $sql2 = "SELECT 
                COALESCE(sm.SocietyName, '') AS SocietyName,
                COALESCE(CONVERT(VARCHAR,sm.BList_UpdatedDate,23), '') AS BList_UpdatedDate, 
                COALESCE(sm.PocketName,  '') AS PocketName,
                COALESCE(sm.Area,  '') AS Area,
                COALESCE(sm.Floor, '') AS Floor,
                COALESCE(sm.Rooms,  '') AS Rooms,
	            COALESCE(sm.Remark1,  '') AS Remark1,
                COALESCE(sm.BList_QC_UpdatedFlag, 0) AS BList_QC_UpdatedFlag,
                COALESCE(em.ExecutiveName,  '') AS ExecutiveName,
                COALESCE(CONVERT(VARCHAR,sm.BList_QC_UpdatedDate,23), '') AS BList_QC_UpdatedDate
            FROM Society_Master sm 
            LEFT JOIN User_Master um ON (um.UserName = sm.BList_QC_UpdatedByUser)
            LEFT JOIN Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
            WHERE sm.BList_UpdatedByUser = '$Username'
            AND sm.ElectionName = '$ElectionName'
            AND CONVERT(VARCHAR,sm.BList_UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate' 
            ORDER BY sm.BList_UpdatedDate DESC";


    $ExeWiseList = $db->ExecutveQueryMultipleRowSALData($sql2, $userName, $appName, $developmentMode);

    // print_r("<pre>");
    // print_r($ExeWiseList);
    // print_r("</pre>");

}
?>
<script>document.body.style.zoom="80%"</script>

<!-- <div class="row match-height" id=""> -->
    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Building Listing Detailed Report - <?php echo $ExecutiveName . " (" . sizeof($ExeWiseList) . ")"; ?>
                </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table zero-configuration table-hover-animation table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;width:17px;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;width:150px;word-wrap: break-word;">Society</th>
                                                        <th style="background-color:#36abb9;color: white;">Updated Date</th>
                                                        <th style="background-color:#36abb9;color: white;">Floors</th>
                                                        <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                        <th style="background-color:#36abb9;color: white;">Pocket</th>
                                                        <th style="background-color:#36abb9;color: white;">Area</th>
                                                        <th style="background-color:#36abb9;color: white;">QC Status</th>
                                                        <th style="background-color:#36abb9;color: white;width:150px;word-wrap: break-word;">Executive</th>
                                                        <th style="background-color:#36abb9;color: white;width:50px;">QC Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($ExeWiseList) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($ExeWiseList as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo "<b>" . $value["SocietyName"] . "</b>"; ?></td>
                                                                <td><?php echo $value["BList_UpdatedDate"]; ?></td>
                                                                <td><?php echo $value["Floor"]; ?></td>
                                                                <td><?php echo $value["Rooms"]; ?></td>
                                                                <td><?php echo $value["PocketName"]; ?></td>
                                                                <td><?php echo $value["Area"]; ?></td>
                                                                <!-- <td><?php //echo $value["BList_QC_UpdatedFlag"]; ?></td> -->
                                                                <td>
                                                                    <div id="check_QC" name="check_QC" style="cursor: pointer" title="<?php if($value['BList_QC_UpdatedFlag'] == 2){echo $value['Remark1'];} ?>" class="badge badge-<?php  if ($value['BList_QC_UpdatedFlag'] == 1) { ?>success<?php } elseif($value['BList_QC_UpdatedFlag'] == 0) { ?>warning<?php }elseif($value['BList_QC_UpdatedFlag'] == 2){ ?>danger<?php } ?>">
                                                                        
                                                                        <?php if($value['BList_QC_UpdatedFlag'] == 1){echo "Done";}elseif($value['BList_QC_UpdatedFlag'] == 0){echo "Pending";}elseif($value['BList_QC_UpdatedFlag'] == 2){echo "Rejected";} ?>
                                                                    </div>
                                                                </td>
                                                                <td><?php echo $value["ExecutiveName"]; ?></td>
                                                                <td><?php echo $value["BList_QC_UpdatedDate"]; ?></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
<!-- </div> -->


<!-- </section> -->