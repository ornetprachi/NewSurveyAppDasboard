<div id="Executivedetail">
<?php
if(
    (isset($_SESSION['SurveyUA_Executive_Name']) && !empty($_SESSION['SurveyUA_Executive_Name']))
){
    $Executive_Name = $_SESSION['SurveyUA_Executive_Name'];
}

// $Query = "SELECT  
//         COALESCE(em.ExecutiveName, '') AS ExecutiveName,
//         COALESCE(CONVERT(varchar,ss.SDate,103), '') AS SurveyDate, 
//         COALESCE(COUNT(ss.Society_Cd),0) AS Societies, 
//         COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone, 
//         COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
//         COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
//         COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
//         COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount, 
//         COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy, 
//         COALESCE(sum(ss.LBS),0) AS LBS, 
//         COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount 
//         FROM DataAnalysis..SurveySummaryDateWise as ss 
//         INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
//         INNER JOIN Survey_Entry_Data..User_Master as um on (ss.SurveyBy = um.UserName) 
//         INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
//         INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
//         WHERE em.ExecutiveName = '$Executive_Name' AND elm.ULB = '$ULB'
//         GROUP BY em.ExecutiveName,CONVERT(varchar,ss.SDate,103) 
//         ORDER BY em.ExecutiveName,CONVERT(varchar,ss.SDate,103);";

$Query = "SELECT  
        COALESCE(ssd.ElectionName, '') AS ElectionName,
		COALESCE(ssd.Society_Cd, '') AS Society_Cd,
        COALESCE(em.ExecutiveName, '') AS ExecutiveName,
        COALESCE(CONVERT(varchar,ss.SDate,103), '') AS SurveyDate, 
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
        INNER JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd) 
        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName) 
        WHERE em.ExecutiveName = '$Executive_Name' AND elm.ULB = '$ULB'
        GROUP BY em.ExecutiveName,ssd.ElectionName,ssd.Society_Cd,CONVERT(varchar,ss.SDate,103) 
        ORDER BY em.ExecutiveName,CONVERT(varchar,ss.SDate,103);";


$ExecutiveDataCount = $db->ExecutveQueryMultipleRowSALData($Query, $userName, $appName, $developmentMode);

?>
<div class="row match-height mb-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-1" id="">
                <div class="card-header">
                    <h4 class="card-title">Execuitve Details - <?php echo $Executive_Name . " (" . sizeof($ExecutiveDataCount) . ")";?></h4>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','ExecutiveWiseDetail')">Excel</button>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;height: auto;" id="ExecutiveWiseDetail">
                                <thead>
                                <tr>
                                    <th  style=''>SrNo</th>
                                    <th  style=''>Action</th>
                                    <!-- <th  style=''>Executive Name</th> -->
                                    <th  style=''>Survey Date</th>
                                    <th  style=''>Society</th>
                                    <th  style=''>Rooms</th>
                                    <th style="">Voters</th>
                                    <th style="">NonVoters</th>
                                    <th style="">Lockroom</th>
                                    <th style="">LBS</th>
                                    <th style="">Mobile</th>
                                    <th style="">Birthday</th>
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
                                                <a href="index.php?p=Survey-QC-Details-View&Society_Cd=<?php echo $value['Society_Cd']?>&electionName=<?php echo $value['ElectionName'] ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>" target="_blank" class="">
                                                    <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                </a>
                                            </td>
                                            <!-- <td><?php //echo $value['ExecutiveName']; ?></td> -->
                                            <td style="margin:15px;"><?php echo $value['SurveyDate']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['Societies']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['RoomSurveyDone']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['TotalVoters']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['TotalNonVoters']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['LockRoom']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['LBS']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['TotalMobileCount']; ?></td>
                                            <td style="margin:15px;"><?php echo $value['BirthdaysCount']; ?></td>
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