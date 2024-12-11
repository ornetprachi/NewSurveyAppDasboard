<?php 

// include 'api/includes/DbOperation.php';

// $db=new DbOperation();
// $userName=$_SESSION['SurveyUA_UserName'];
// $appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
// $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
// $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
 $SiteQcQury = "SELECT 
                COALESCE(ssm.SupervisorName,'') AS SupervisorName,
                COALESCE(em.MobileNo,'') AS MobileNo,
                COALESCE(ssd.SiteName,'') AS Sites,
                COALESCE(ssm.SiteStatus,'') AS SiteStatus,
                COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
                (SELECT 
                SUM(CASE WHEN BList_QC_UpdatedFlag = 1 THEN 1 else 0 end) as BLQc
                FROM Survey_Entry_Data..Society_Master WHERE SiteName = ssd.SiteName AND CONVERT(varchar,BList_QC_UpdatedDate,23) < CONVERT(varchar,GETDATE(),23)) ListingQc,
                COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
                COALESCE(sum(ss.VoterQCDone),0) AS VoterQCDone,
                COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                COALESCE(sum(ss.NonVoterQCDone),0) AS NonVoterQCDone,
                COALESCE(sum(ss.NonVotersConverted),0) AS NonVotersConverted,
                COALESCE(sum(ss.WrongMobileNo),0) AS WrongMobileNo,
                COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
                FROM DataAnalysis..SurveySummary as ssd
                LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd)
                INNER JOIN Survey_Entry_Data..Site_Master as ssm on(ssd.Site_Cd = ssm.Site_Cd) 
                LEFT JOIN Survey_Entry_Data..Executive_Master as em on (ssm.SupervisorName =  em.ExecutiveName)
                WHERE ssd.ULB = '$ULB' AND ssm.Ward_No <> 100
                GROUP BY ssm.SupervisorName,ssm.ManagerName,em.MobileNo,ssm.SiteStatus,ssd.SiteName
                ORDER BY ssm.SupervisorName
                ";


$SiteQcData = $db->ExecutveQueryMultipleRowSALData($SiteQcQury, $userName, $appName, $developmentMode);
// print_r("<pre>");
// print_r($SiteQcData);
// print_r("</pre");
?>
<div class="card-header">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title" style="padding:5px;margin-left:10px;">Site Wise Qc</h4>
        </div>
        <div class="col-md-2">
            <?php if($ExcelExportButton == "show"){ ?>
                <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWiseQcTable')">Excel</button>
            <?php } ?>
        </div>
    </div>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">  
        <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                        
                            <table class="table table-striped table-bordered complex-headers" id="SiteWiseQcTable" width="100%"  border=1>
                            <thead>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" rowspan="2">No</th>
                                        <!-- <th style="background-color:#36abb9;color: white;" class="text-center" rowspan="2">View</th> -->
                                        <th style="background-color:#36abb9;color: white;" class="text-center" rowspan="2">Site Name</th>
                                        <!-- <th class="text-center" >Assembly</th> -->
                                        <th style="background-color:#36abb9;color: white;" class="text-center"  colspan=3>Listing</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"  colspan=3>Voters</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"  colspan=4>NonVoters</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center"  colspan=3>Mobile No</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Total</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Pending</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Total</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Pending</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Total</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Qc</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Pending</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Converted</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Total</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Wrong</th>
                                        <th style="background-color:#36abb9;color: white;" class="text-center" >Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Percentage = 0;
                                    if(sizeof($SiteQcData) > 0 ){
                                        $srNo = 1;
                                        foreach ($SiteQcData as $key => $value) {
                                        ?> 
                                            <tr style="padding-top:0px;">
                                                <td class="text-center" style="align:center;"><?php echo $srNo++; ?></td>
                                                <!-- <td class="text-center"style="color: #36abb9;align-items:center;text-center;">
                                                    <a class="" onclick="GetSiteDetailQc('<?php echo $value['Sites']?>')">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td> -->
                                                <td class="text-center" style="align:center;"><?php echo $value["Sites"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["Listing"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["ListingQc"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["Listing"]-$value["ListingQc"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["TotalVoters"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["VoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["TotalVoters"]-$value["VoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["TotalNonVoters"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["NonVoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["TotalNonVoters"]-$value["NonVoterQCDone"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["NonVotersConverted"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["TotalMobileCount"]; ?></td>
                                                <td class="text-center" style="align:center;"><?php echo $value["WrongMobileNo"]; ?></td>
                                                <td class="text-center" style="align:center;">
                                                    <?php 
                                                        if($value["TotalMobileCount"] != 0){
                                                            $Percentage = ($value["WrongMobileNo"]/$value["TotalMobileCount"])*100;
                                                            echo $Percentage = number_format($Percentage, 2)." %";
                                                        }else{
                                                            echo "0.00 %";
                                                        }
                                                    ?>        
                                                </td>
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
<script>
  $(document).ready(function () {
    $('#SiteWiseQcTable').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>