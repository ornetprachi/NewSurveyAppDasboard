
<section id="dashboard-analytics">

<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

$fromDate = "2023-05-25";
$toDate = date('Y-m-d');

if(
    (isset($_SESSION['SurveyUA_BLReport_fromDate']) && !empty($_SESSION['SurveyUA_BLReport_fromDate'])) &&
    (isset($_SESSION['SurveyUA_BLReport_toDate']) && !empty($_SESSION['SurveyUA_BLReport_toDate'])) 
){
    $fromDate = $_SESSION['SurveyUA_BLReport_fromDate'];
    $toDate = $_SESSION['SurveyUA_BLReport_toDate'];
}


$sql2 = "SELECT 
            COALESCE(sm.ElectionName, '') AS ElectionName,
            COALESCE(sm.SiteName, '') AS SiteName,
            COALESCE(em.ExecutiveName, '') AS ExecutiveName,
            COALESCE(em.MobileNo, '') AS ExecutiveMobile,
            COALESCE(sm.BList_UpdatedByUser, '') AS Username, 
            COALESCE(Count(sm.SocietyName), 0) AS TotalCount,
            COALESCE(SUM(CASE WHEN BList_QC_UpdatedFlag = 1 THEN 1 ELSE 0 END),0) AS QC_Done,
            COALESCE(SUM(CASE WHEN sm.BList_QC_UpdatedFlag = 2 THEN 1 ELSE 0 END),0) AS QC_Rejected,
            COALESCE(SUM(CASE WHEN BList_QC_UpdatedFlag = 0 THEN 1 ELSE 0 END),0) AS QC_Pending
        FROM Society_Master sm 
        INNER JOIN User_Master um ON (um.UserName = sm.BList_UpdatedByUser)
        INNER JOIN Executive_Master em ON (em.Executive_Cd = um.Executive_Cd)
        WHERE 
        CONVERT(VARCHAR, sm.BList_UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate' 
        GROUP BY em.ExecutiveName, sm.ElectionName, sm.SiteName, sm.BList_UpdatedByUser, em.MobileNo 
        ORDER BY sm.ElectionName";


$CountListMain = $db->ExecutveQueryMultipleRowSALData($sql2, $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($CountListMain);
// print_r("</pre>");

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #e6f4f4;
}
</style>


<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <div class="controls"> 
                                        <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="getDatesForBLSummaryReport()">
                                            Refresh 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
<div id='spinnerLoader2' style='display:none'>
    <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
</div>
<div class="row match-height" id="tblBuildingListingQCtbl">
    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Building Listing Summary Report
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
                                            <table class="table table-hover-animation table-striped table-hover" id="BListTable">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;width:90px;">Election</th>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Site</th>
                                                        <th style="background-color:#36abb9;color: white;width:300px;word-wrap: break-word;">Executive</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Total Count</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">QC Done</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">QC Rejected</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">QC Pending</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(sizeof($CountListMain) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($CountListMain as $key => $value) {
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo $value["ElectionName"]; ?></td>
                                                                <td><?php echo $value["SiteName"]; ?></td>
                                                                <td><?php echo "<b>" . $value["ExecutiveName"] . "</b><br>". $value["ExecutiveMobile"]; ?></td>
                                                                <td style="cursor:pointer" onclick="getBLExecutiveWiseDetailedData('<?php echo $value['ElectionName'] ?>','<?php echo $value['Username'] ?>','<?php echo $value['ExecutiveName'] ?>','<?php echo $fromDate ?>','<?php echo $toDate ?>')"><b><?php echo $value["TotalCount"]; ?></b></td>
                                                                <td><?php echo $value["QC_Done"]; ?></td>
                                                                <td><?php echo $value["QC_Rejected"]; ?></td>
                                                                <td><?php echo $value["QC_Pending"]; ?></td>
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
</div>
  
<div class="row match-height" id="BLExecutiveWiseDetailedRecord">                               
                
</div> 


</section>
