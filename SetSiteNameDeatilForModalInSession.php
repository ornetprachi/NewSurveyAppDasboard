<?php
    session_start();
include 'api/includes/DbOperation.php'; 
// include_once 'includes/ajaxscript.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Site']) && !empty($_GET['Site']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Site_For_DetailModal'] = $_GET['Site'];
            $SiteName =  $_SESSION['SurveyUA_Site_For_DetailModal'];
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }else{
    //echo "ddd";
  }

}
if(
    isset($_GET['date']) && !empty($_GET['date']) &&
    isset($_GET['Tdate']) && !empty($_GET['Tdate']) 
    ){
    $fromdate = $_GET['date'];
    $todate = $_GET['Tdate'];
    $dwCon = "AND CONVERT(varchar,dw.AddedDate,23) BETWEEN '$fromdate' AND '$todate'";
    $nvCon = "AND CONVERT(varchar,nv.added_date,23) BETWEEN '$fromdate' AND '$todate'";
    $lrCon = "AND CONVERT(varchar,lr.added_date,23) BETWEEN '$fromdate' AND '$todate'";
}else{
    $fromdate = date('Y-m-d');
    $todate = date('Y-m-d');
    $dwCon = "";
    $nvCon = "";
    $lrCon = "";
}
// echo $fromdate;
?>


    <?php 
    $Site = $_SESSION['SurveyUA_Site_For_DetailModal'];
    // echo "$Site";
 
    // $SiteWiseQuery = "SELECT 
    //                     COALESCE(ss.SiteName, '') AS SiteName, 
    //                     COALESCE(ss.ElectionName, '') AS ElectionName, 
    //                     COALESCE(ss.SocietyName,'') AS SocietyName,
    //                     COALESCE(em.ExecutiveName,'') AS ListedBy,
    //                     COALESCE(sm.PlotNo,'') AS PlotNo, 
    //                     COALESCE(em.MobileNo,'') AS MobileNo,
    //                     COALESCE(pm.PocketName,'') AS PocketName, 
    //                     COALESCE(pm.PocketNo,'') as PocketNo,
	// 	                COALESCE(sum(ss.NewRooms),'') AS Rooms,
    //                     COALESCE(sum(sm.Rooms),'') AS TotalRoom, 
    //                     COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
    //                     COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
    //                     COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
    //                     COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
    //                     COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
    //                     COALESCE(sum(ss.LBS),0) AS LBS, 
    //                     COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
    //                     FROM DataAnalysis..SurveySummary as ss 
    //                     INNER JOIN Survey_Entry_Data..Election_Master as elm on (ss.ElectionName = elm.ElectionName) 
    //                     LEFT JOIN Survey_Entry_Data..Executive_Master as em on (ss.ListedBy = em.UserName)
    //                     LEFT  JOIN Survey_Entry_Data..Pocket_Master as pm on (ss.Pocket_Cd = pm.Pocket_Cd)
    //                     LEFT JOIN Survey_Entry_Data..Society_Master as sm on (ss.Society_Cd =sm.Society_Cd) 
    //                     WHERE elm.ULB = '$ULB' AND ss.SiteName = '$Site' $Con
    //                     GROUP BY ss.SiteName,ss.SocietyName,em.ExecutiveName,em.MobileNo,ss.ElectionName,pm.PocketName,pm.PocketNo,sm.PlotNo
    //                     ORDER BY ss.SiteName;";

    $SiteWiseQuery = "WITH unionTable AS (SELECT
                        Combined.AddedBy AS AddedBy,
                        Combined.AddedDate,
                        COALESCE(Combined.Society_Cd, 0) AS Society_Cd,
                        COALESCE(COUNT(DISTINCT Combined.RoomNo), 0) AS RoomCount, 
                        COALESCE(COUNT(DISTINCT CASE 
                                                WHEN Combined.Mobileno <> '' AND Combined.Mobileno IS NOT NULL AND LEN(Combined.Mobileno) > 9 
                                                THEN Combined.Mobileno 
                                            END), 0) AS Mobileno,
                        COALESCE(COUNT(CASE WHEN Combined.Source = 'Dw_VotersInfo' AND Combined.IdCard_No IS NOT NULL AND Combined.IdCard_No <> '' THEN 1 END), 0) AS TotalVoters,
                        COALESCE(COUNT(CASE WHEN Combined.Source = 'NewVoterRegistration' AND Combined.Voter_Cd IS NOT NULL AND Combined.Voter_Cd <> ''  THEN 1 END), 0) AS TotalNonVoters,
                        COALESCE(COUNT(CASE WHEN Combined.Source = 'LockRoom' THEN 1 END), 0) AS LockRoom,
                        COALESCE(COUNT(CASE WHEN Combined.BirthDate IS NOT NULL AND Combined.BirthDate <> '01/01/1900' THEN 1 END), 0) AS BirthdaysCount,
                        COALESCE(COUNT(CASE WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN 1 END), 0) AS LBS
                        FROM (
                            SELECT 
                                    dw.IdCard_No,
                                    dw.Voter_Cd AS Voter_Cd,
                                    dw.Society_Cd, 
                                    dw.RoomNo, 
                                    dw.AddedBy, 
                                    Convert(varchar,dw.AddedDate,23) AS AddedDate, 
                                    'Dw_VotersInfo' AS Source, 
                                    dw.LockedButSurvey AS LBS, 
                                    dw.MobileNo AS Mobileno,
                                    CASE
                                        WHEN TRY_CONVERT(date, dw.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 101), 101)
                                        WHEN TRY_CONVERT(date, dw.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 105), 101)
                                        WHEN TRY_CONVERT(date, dw.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, dw.BirthDate, 23), 101)
                                        ELSE NULL
                                    END AS BirthDate 
                                FROM Dw_VotersInfo AS dw
                                WHERE dw.Society_Cd IS NOT NULL 
                                AND dw.Society_Cd <> 0 AND COALESCE(dw.Ward_no, 0) != 0
                                 AND dw.SF = 1
                                AND dw.SiteName = '$Site' $dwCon

                                UNION ALL

                                SELECT 
                                    NULL AS IdCard_No,
                                    nv.Voter_Cd AS Voter_Cd,
                                    nv.Society_Cd, 
                                    nv.RoomNo, 
                                    nv.added_by AS AddedBy, 
                                    Convert(varchar,nv.added_date,23) AS AddedDate, 
                                    'NewVoterRegistration' AS Source, 
                                    nv.LockedButSurvey AS LBS, 
                                    nv.Mobileno AS Mobileno,
                                    CASE
                                        WHEN TRY_CONVERT(date, nv.BirthDate, 101) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 101), 101)
                                        WHEN TRY_CONVERT(date, nv.BirthDate, 105) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 105), 101)
                                        WHEN TRY_CONVERT(date, nv.BirthDate, 23) IS NOT NULL THEN CONVERT(varchar, TRY_CONVERT(date, nv.BirthDate, 23), 101)
                                        ELSE NULL
                                    END AS BirthDate
                                FROM NewVoterRegistration AS nv
                                WHERE nv.Society_Cd IS NOT NULL 
                                AND nv.Society_Cd <> 0 AND COALESCE(nv.Ward_No, 0) != 0
                                AND nv.SiteName = '$Site' $nvCon

                                UNION ALL

                                SELECT 
                                    NULL AS IdCard_No,
                                    NULL AS Voter_Cd,
                                    lr.Society_Cd, 
                                    lr.RoomNo, 
                                    lr.added_by AS AddedBy, 
                                    Convert(varchar,lr.added_date,23) AS AddedDate,
                                    'LockRoom' AS Source, 
                                    NULL AS LBS, 
                                    NULL AS Mobileno, 
                                    NULL AS BirthDate
                                FROM LockRoom AS lr
                                WHERE lr.Society_Cd IS NOT NULL 
                                AND lr.Society_Cd <> 0 
                                AND COALESCE(lr.Ward_No, 0) != 0
                                AND lr.SiteName = '$Site' $lrCon
                        ) AS Combined
                        GROUP BY Combined.AddedBy,Combined.Society_Cd,Combined.AddedDate)
                        SELECT  
                        COALESCE(um.ExecutiveName,'') AS ListedBy,
                        COALESCE(som.ElectionName,'') AS ElectionName,
                        COALESCE(um.Mobile, '') AS MobileNo,
                        COALESCE(pom.PocketName,'') AS PocketName,
                        COALESCE(pom.PocketNo,0) AS PocketNo,
                        COALESCE(som.SocietyName,'') AS SocietyName,
                        COALESCE(som.Plot_No,'') AS PlotNo,
                        COALESCE(som.SiteName,'') AS SiteName,
                        COALESCE(sum(som.NewRooms),'') AS Rooms,
                        COALESCE(sum(som.Rooms),'') AS TotalRoom,
                        SUM(ut.Mobileno) AS TotalMobileCount,
                        SUM(ut.TotalVoters) AS TotalVoters,
                        SUM(ut.TotalNonVoters) AS TotalNonVoters,
                        SUM(ut.LockRoom) AS LockRoom,
                        SUM(ut.BirthdaysCount) AS BirthdaysCount,
                        SUM(ut.LBS) AS LBS,
                        SUM(ut.RoomCount) AS RoomSurveyDone,
                        COALESCE(count(DISTINCT ut.AddedBy),0) AS SurveyBy
                        from unionTable  AS ut 
                        Inner Join Society_Master as som ON ut.Society_Cd = som.Society_Cd
                        LEFT JOIN Pocket_Master as pom ON som.Pocket_Cd = pom.Pocket_Cd
                        LEFT JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
                        AND um.ElectionName = '$ULB'
                        LEFT JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
                        GROUP BY um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile,ut.Society_Cd,
                        som.SocietyName,pom.PocketName,som.SiteName,som.ElectionName,som.Plot_No,pom.PocketNo
                        ORDER BY SiteName;";
    
    $SiteWise = $db->ExecutveQueryMultipleRowSALData($ULB,$SiteWiseQuery, $userName, $appName, $developmentMode);
    ?>
   
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <div id="MODAL_VIEW" class="modal"> -->
<div class = "DetailSiteData">
    <!-- <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId"> -->
    <!-- <div class="modal-content"> -->
            <div class="card-header">
                <div class = "row">
                    <div class="col-md-12">
                        <h4 class="card-title" style="align:center;color:rgb(54, 171, 185);"><b> <?php echo $Site ?> </b></h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="text-start">Date</label>
                            <input type="date" name="fdate" id="fdate" value="<?php echo $fromdate; ?>"  class="form-control" placeholder="From Date">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="text-start">Date</label>
                            <input type="date" name="tdate" id="tdate" value="<?php echo $todate; ?>"  class="form-control" placeholder="To Date">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="controls" style="padding-top:20px;">
                            <button type="button" class="btn btn-primary" onclick="dateforlist()"  id="RefreshBtn">
                                Refresh 
                            </button>
                        </div>
                    </div>
                </div>
                <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWiseAllSociety')">Excel</button>
            </div>
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation table-hover" id="SiteWiseAllSociety">
                                        <thead>
                                            <tr>
                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                <th style="background-color:#36abb9;color: white;">View</th>
                                                <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                <th style="background-color:#36abb9;color: white;">Plot No</th>
                                                <th style="background-color:#36abb9;color: white;">PocketNo</th>
                                                <th style="background-color:#36abb9;color: white;">Pocket Name</th>
                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                <th style="background-color:#36abb9;color: white;">Rooms</th>
                                                <th style="background-color:#36abb9;color: white;">RoomsDone</th>
                                                <th style="background-color:#36abb9;color: white;" Title="LockRoom">LockRoom</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Voters">Voters</th>
                                                <th style="background-color:#36abb9;color: white;" Title="NonVoters">NonVoters</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Locked But Survey">LBS</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Mobile">Mobile</th>
                                                <th style="background-color:#36abb9;color: white;" Title="Birthdate">BirDt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(sizeof($SiteWise) > 0 ){
                                                $srNo = 1;
                                                foreach ($SiteWise as $key => $value) {
                                                ?> 
                                                    <tr style="padding-top:0px;">
                                                        <td><?php echo $srNo++; ?></td>
                                                        <td>
                                                            <a href="index.php?p=Survey_Society_Detail&electionName=<?php echo $value['ElectionName'] ?>&SocietyName=<?php echo $value['SocietyName'] ?>&SiteName=<?php echo $value['SiteName'] ?>" target="_blank" class="">
                                                                <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                            </a>
                                                        </td>
                                                        <td><?php echo "<b>" . $value["SocietyName"] . "</b>" ?></td>
                                                        <td class="text-center"><?php echo $value["PlotNo"]; ?></td>
                                                        <td class="text-center"><?php echo $value["PocketNo"]; ?></td>
                                                        <td class="text-center"><?php echo $value["PocketName"]; ?></td>
                                                        <td Title="<?php echo $value["MobileNo"]; ?>" style="cursor:pointer;"><?php echo $value["ListedBy"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalRoom"]; ?></td>
                                                        <td class="text-center"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                        <td class="text-center"><?php echo $value["LockRoom"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalVoters"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalNonVoters"]; ?></td>
                                                        <td class="text-center"><?php echo $value["LBS"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalMobileCount"]; ?></td>
                                                        <td class="text-center"><?php echo $value["BirthdaysCount"]; ?></td>
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
    <!-- </div> -->
    <!-- </div> -->
</div>
<!-- </div> -->
</div>
<script>

</script>