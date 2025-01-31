<?php
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

if($electionName == 'PT188'){
    $FromDate = "AND CONVERT(varchar,soc.UpdatedDate,23) > '2024-07-31'";
}else{
    $FromDate = "";
}

    $Query = "SELECT sm.Ac_No,sm.ElectionName,soc.SiteName,sm.ClientName,sm.Ward_No,COUNT(Society_Cd)  as Societies,SUM(Rooms) as Rooms
		FROM Society_Master as soc
		INNER JOIN Site_Master AS sm on (soc.SiteName = sm.SiteName)
        WHERE sm.ElectionName = '$electionName' AND soc.ElectionName = '$electionName'   AND soc.Remark != '' AND soc.Remark is NOT NULL  AND YEAR(soc.UpdatedDate) = 2024 $FromDate 
		GROUP BY sm.Ac_No,sm.ElectionName,soc.SiteName,sm.ClientName,sm.Ward_No
		ORDER BY sm.Ward_No";

    // print_r($Query);
    $SocietyData = $db->ExecutveQueryMultipleRowSALData($ULB,$Query, $userName, $appName, $developmentMode);    

    $societyTotal = array_sum(array_column($SocietyData, 'Societies'));
    $roomTotal = array_sum(array_column($SocietyData, 'Rooms'));
?>
<div class="card-header">
    <div class="row">
        <h4 class="card-title" style="padding:5px;margin-left:10px;"> <b>Society Issue</b> </h4>
        <!-- <button type="button" style="padding:5px;margin-left:10px;" class="btn btn-outline-info square mr-1 mb-1" id="showDateSiteCountBtn" >Count</button> -->
    </div>
    <?php if($ExcelExportButton == "show"){ ?>
        <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWiseSocietyIssue')">Excel</button>
    <?php } ?>
</div>
<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <center>
                        <div class="table-responsive">
                            <table class="table table-hover-animation table-striped table-hover" id="SiteWiseSocietyIssue" width="60%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">Sr.No.</th>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">View</th>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">Client Name</th>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">Ward No</th>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">Societies</th>
                                        <th class="text-center" style="background-color:#36abb9;color: white;">Rooms</th>
                                </thead>
                                <tbody>
                                    <?php
                                        if(sizeof($SocietyData) > 0 ){
                                            $srNo = 1;
                                            foreach ($SocietyData as $key => $value) {
                                        ?>
                                    <tr>
                                        <td class="text-center"><?php echo $srNo++; ?></td>
                                        <td class="text-center"  style="color: #36abb9;">
                                            <a href="index.php?p=SocietyIssue&electionName=<?php echo $value['ElectionName'] ?>&SiteName=<?php echo $value['SiteName'] ?>" target="_blank">
                                                <i class="fa fa-eye" style="color: #36abb9;"></i>
                                            </a>
                                        </td>
                                        <td><b><?php echo $value['ClientName']."(".$value['SiteName'].")"; ?></b></td>
                                        <td class="text-center"><?php echo $value['Ward_No']; ?></td>
                                        <td class="text-center"><?php echo $value['Societies']; ?></td>
                                        <td class="text-center"><?php echo $value['Rooms']; ?></td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th  class="text-center"><?php echo $societyTotal; ?></th>
                                        <th  class="text-center"><?php echo $roomTotal; ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
