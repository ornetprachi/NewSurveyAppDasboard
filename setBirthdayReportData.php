<section id="dashboard-analytics">

<?php

// include 'api/includes/DbOperation.php';

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

// $fromDate = "2023-05-25";
// $toDate = date('Y-m-d');




// if(
//     (isset($_SESSION['SurveyUA_BirthdayReport_fromDate']) && !empty($_SESSION['SurveyUA_BirthdayReport_fromDate'])) &&
//     (isset($_SESSION['SurveyUA_BirthdayReport_toDate']) && !empty($_SESSION['SurveyUA_BirthdayReport_toDate'])) 
// ){
//     $fromDate = $_SESSION['SurveyUA_BirthdayReport_fromDate'];
//     $toDate = $_SESSION['SurveyUA_BirthdayReport_toDate'];
// }

        $Q ="SELECT 
        COALESCE(ssd.SiteName,'') AS SiteName,
        COALESCE((SELECT TOP 1 ClientName FROM Survey_Entry_Data..Site_Master s WHERE s.Site_Cd = ssd.Site_Cd), '') AS ClientName,
        COUNT(DISTINCT(sbd.Voter_Cd)) AS BirthdaysCount
        FROM DataAnalysis..SurveyBirthdayData sbd
        INNER JOIN DataAnalysis..SurveySummary as ssd on (sbd.Society_Cd = ssd.Society_Cd)
        GROUP BY ssd.SiteName, ssd.Site_Cd
        ORDER BY ClientName";
                
        $MainTableDataArray = $db->ExecutveQueryMultipleRowSALData($Q, $userName, $appName, $developmentMode);

?>

<style>
    table.dataTable.table-striped tbody tr:nth-of-type(odd) {
    background-color: #e6f4f4;
}
</style>

<div class="row">
    <div class="col-md-12" style="align-items:center">
        <center>
            <div id='spinnerLoader2' style='display:none'>
                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
            </div>
        </center>
    </div>
</div>

<div class="row match-height" id="tblBuildingListingQCtbl">
    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Site Wise Birthday Summary
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
                                                        <th style="background-color:#36abb9;color: white;width:90px;">Client Name</th>
                                                        <th style="background-color:#36abb9;color: white;width:33px;">Site</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Birthday Count</th>
                                                        <th style="background-color:#36abb9;color: white;width:45px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $TotalBDCount = 0;
                                                    if(sizeof($MainTableDataArray) > 0 ){
                                                        $srNo = 1;
                                                        foreach ($MainTableDataArray as $key => $value) {
                                                            $TotalBDCount = $TotalBDCount + $value['BirthdaysCount'];
                                                        ?> 
                                                            <tr style="padding-top:0px">
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td><?php echo $value['ClientName']; ?></td>
                                                                <td><?php echo $value['SiteName']; ?></td>
                                                                <td><?php echo $value['BirthdaysCount']; ?></td>
                                                                <td style="color: #36abb9;">
                                                                    <a href="<?php echo 'index.php?p=SiteWiseBirthdateReport&SiteName='.$value['SiteName']; ?>">
                                                                        <i style="color:#36abb9;cursor:pointer;" class="fa fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3">Total</td>
                                                        <td><?php echo $TotalBDCount;?></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
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