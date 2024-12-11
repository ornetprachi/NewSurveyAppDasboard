<div id="Executivedetail">
<?php
if(isset($_SESSION['SurveyUtility_ULB'])){
    $ULB=$_SESSION['SurveyUtility_ULB'];
}
if(
    (isset($_SESSION['SurveyUA_Executive_Name']) && !empty($_SESSION['SurveyUA_Executive_Name']))
){
    $Executive_Name = $_SESSION['SurveyUA_Executive_Name'];
}

$Query = "SELECT  
        COALESCE(ssd.ElectionName, '') AS ElectionName,
        COALESCE(sm.Ward_No, '') AS Ward_No, 
		COALESCE(um.UserName, '') AS UserName,
        COALESCE(em.ExecutiveName, '') AS ExecutiveName,
        COALESCE(CONVERT(DATE,ss.SDate,103), '') AS SurveyDate, 
        COALESCE(COUNT(ss.Society_Cd),0) AS Societies, 
        COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
        COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
        COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
        COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
        COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
        COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
        COALESCE(sum(ss.LBS),0) AS LBS, 
        COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount 
        FROM DataAnalysis..SurveySummaryDateWise as ss 
        INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
        INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName)  
        LEFT JOIN Survey_Entry_Data..Site_Master as sm on (ssd.SiteName = sm.SiteName)
        INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
        WHERE em.ExecutiveName = '$Executive_Name' AND elm.ULB = '$ULB'
        GROUP BY em.ExecutiveName,um.UserName,ssd.ElectionName,CONVERT(DATE,ss.SDate,103),sm.Ward_No 
        ORDER BY CONVERT(DATE,ss.SDate,103) DESC;";


$ExecutiveDataCount = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);

?>
<div class="row match-height mb-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-1" id="">
                <div class="card-header">
                    <h4 class="card-title">Execuitve Details - <?php echo $Executive_Name . " (" . sizeof($ExecutiveDataCount) . ")";?></h4>
                    <?php if($ExcelExportButton == "show"){ ?>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ExecutiveWiseDetail')">Excel</button>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;height: auto;" id="ExecutiveWiseDetail">
                                <thead>
                                <tr>
                                    <th class="text-center" style=''>SrNo</th>
                                    <th class="text-center" style=''>Action</th>
                                    <!--class="text-center" <th  style=''>Executive Name</th> -->
                                    <th class="text-center" style=''>Survey Date</th>
                                    <th class="text-center" style=''>Ward No</th>
                                    <th class="text-center" style=''>Society</th>
                                    <th class="text-center" style=''>Rooms</th>
                                    <th class="text-center" style="">Voters</th>
                                    <th class="text-center" style="">NonVoters</th>
                                    <th class="text-center" style="">Lockroom</th>
                                    <th class="text-center" style="">LBS</th>
                                    <th class="text-center" style="">Birthday</th>
                                    <th class="text-center" style="">Mobile</th>
                                    <th class="text-center" Title ="Voters Ratio">V %</th>
                                    <th class="text-center" Title ="NonVoters Ratio">NV %</th>
                                    <th class="text-center" Title ="LockRoom Ratio">LR %</th>
                                    <th class="text-center" Title ="Locked But Survey Ratio">LBS %</th>
                                    <th class="text-center" Title ="Birthdate Ratio">BirtDt %</th>
                                    <th class="text-center" Title ="Mobile Ratio">Mob %</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($ExecutiveDataCount) > 0){
                                        $srNo = 1;
                                        foreach($ExecutiveDataCount AS $Key=>$value){  
                                        ?>
                                        <tr>
                                            <td style="margin:15px;"><?php echo $srNo++; ?></td>
                                            <td style="color: #36abb9;align-items:center;text-center;">
                                                <a href="index.php?p=Survey-QC-Details-View&electionName=<?php echo $value['ElectionName'] ?>&SurveyDate=<?php echo date_format($value['SurveyDate'],"d/m/Y") ?>&UserName=<?php echo $value['UserName'] ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>" target="_blank" class="">
                                                    <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                </a>
                                            </td>
                                            <!-- <td><?php //echo $value['ExecutiveName']; ?></td> -->
                                            <td class="text-center" style="margin:15px;"><?php echo date_format($value['SurveyDate'],"d/m/Y"); ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['Ward_No']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['Societies']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['RoomSurveyDone']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalVoters']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalNonVoters']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['LockRoom']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['LBS']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['BirthdaysCount']; ?></td>
                                            <td class="text-center" style="margin:15px;"><?php echo $value['TotalMobileCount']; ?></td>
                                            <td><?php echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)." %"; ?></td>
                                            <td><?php echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)." %"; ?></td>
                                            <td><?php echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)." %"; ?></td>
                                            <td><?php echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)." %"; ?></td>
                                            <td><?php echo CEIL(($value["BirthdaysCount"]/$value["RoomSurveyDone"])*100)." %"; ?></td>
                                            <td><?php echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)." %"; ?></td>
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
</div>
</div>