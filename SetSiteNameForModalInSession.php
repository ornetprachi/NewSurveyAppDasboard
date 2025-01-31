<?php
session_start();

//Changes by prachi for report
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
            
            $_SESSION['SurveyUA_Site_For_Modal'] = $_GET['Site'];
            $SiteName =  $_SESSION['SurveyUA_Site_For_Modal'];
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }else{
    //echo "ddd";
  }

}
?>


    <?php 
    $Site = $_SESSION['SurveyUA_Site_For_Modal'];
    // echo "$Site";


    // $SiteWiseQuery = "SELECT 
    // COALESCE(ssd.SiteName, '') AS SiteName,
    // COALESCE(ssd.ElectionName, '') AS ElectionName,
    // COALESCE(ssd.SocietyName,'') AS SocietyName,
    // COALESCE(em.ExecutiveName,'') AS ExecutiveName,  
    // COALESCE(em.MobileNo,'') AS MobileNo,
    // COALESCE(pm.PocketName,'') AS PocketName,
    // COALESCE(sum(ssd.NewRooms),'') AS Rooms, //
    // COALESCE(sum(sm.Rooms),'') AS TotalRoom,//
    // COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
    // COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
    // COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
    // COALESCE(sum(ss.LockRoom),0) AS LockRoom,
    // COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
    // COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,//
    // COALESCE(sum(ss.LBS),0) AS LBS,
    // COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
    // FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss
    // INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
    // LEFT JOIN Survey_Entry_Data..Society_Master as sm on (ss.Society_Cd = sm.Society_Cd)
    // LEFT JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
    // LEFT JOIN  Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName COLLATE Latin1_General_CI_AI)
    // LEFT JOIN Survey_Entry_Data..Pocket_Master as pm on (ssd.Pocket_Cd = pm.Pocket_Cd)
    // WHERE elm.ULB = '$ULB' AND ssd.SiteName = '$Site'
    // GROUP BY ssd.SiteName,ssd.SocietyName,em.ExecutiveName,em.MobileNo,ssd.ElectionName,pm.PocketName
    // ORDER BY ssd.SiteName;";



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
                    COALESCE(COUNT(DISTINCT CASE 
                        WHEN Combined.LBS IS NOT NULL AND Combined.LBS <> '' THEN Combined.RoomNo 
                    END), 0) AS LBS
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
                            AND dw.Society_Cd <> 0 
                            AND COALESCE(dw.Ward_no, 0) != 0
                            -- AND (dw.BirthDate <> '' AND dw.BirthDate IS NOT NULL OR CONVERT(date,dw.BirthDate,23) = '1900-01-01') AND dw.SF = 1
                            AND dw.SF = 1
                            AND dw.SiteName = '$Site'

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
                            AND nv.Society_Cd <> 0 
                            AND COALESCE(nv.Ward_No, 0) != 0
                            AND nv.SiteName = '$Site' 

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
                            AND lr.SiteName = '$Site'
                    ) AS Combined
                    GROUP BY Combined.AddedBy,Combined.Society_Cd,Combined.AddedDate)
                    SELECT  
                    COALESCE(um.ExecutiveName,'') AS ExecutiveName,
                    COALESCE(um.ElectionName,'') AS ElectionName,
                    COALESCE(um.Mobile, '') AS MobileNo,
                    COALESCE(pom.PocketName,'') AS PocketName,
                    som.SocietyName AS SocietyName,
                    som.SiteName AS SiteName,
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
                    LEFT Join Society_Master as som ON ut.Society_Cd = som.Society_Cd
                    LEFT JOIN Pocket_Master as pom ON som.Pocket_Cd = pom.Pocket_Cd
                    Inner JOIN Survey_Entry_Data..User_Master AS um ON um.Executive_Cd = ut.AddedBy 
                    AND um.ElectionName = '$ULB'
                    Inner JOIN Survey_Entry_Data..Executive_Master AS em ON um.Executive_Cd = em.Executive_Cd
                    GROUP BY um.ExecutiveName,em.JoiningDate,um.DeactiveFlag,ExpDate,em.Designation,um.Mobile,ut.Society_Cd,
                    som.SocietyName,pom.PocketName,som.SiteName,um.ElectionName
                    ORDER BY SiteName;";

//    $SiteWiseQuery = "SELECT 
//    COALESCE(ssd.SiteName, '') AS SiteName,
//    COALESCE(ssd.ElectionName, '') AS ElectionName,
//    COALESCE(ssd.SocietyName,'') AS SocietyName,
//    COALESCE(em.ExecutiveName,'') AS ExecutiveName,  
//    COALESCE(em.MobileNo,'') AS MobileNo,
//    COALESCE(pm.PocketName,'') AS PocketName,
//    COALESCE(sum(ssd.NewRooms),'') AS Rooms, 
//    COALESCE(sum(sm.Rooms),'') AS TotalRoom,
//    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
//    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
//    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
//    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
//    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
//    COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
//    COALESCE(sum(ss.LBS),0) AS LBS,
//    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
//    FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss
//    INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
//    LEFT JOIN Survey_Entry_Data..Society_Master as sm on (ss.Society_Cd = sm.Society_Cd)
//    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
//    LEFT JOIN  Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName COLLATE Latin1_General_CI_AI)
//    INNER JOIN Survey_Entry_Data..Pocket_Master as pm on (ssd.Pocket_Cd = pm.Pocket_Cd)
//    WHERE elm.ULB = '$ULB' AND ssd.SiteName = '$Site'
//    GROUP BY ssd.SiteName,ssd.SocietyName,em.ExecutiveName,em.MobileNo,ssd.ElectionName,pm.PocketName
//    ORDER BY ssd.SiteName;";

    $SiteWise = $db->ExecutveQueryMultipleRowSALData($ULB,$SiteWiseQuery, $userName, $appName, $developmentMode);
    // print_r("<pre>");
    // print_r($SiteWise);
    // print_r("</pre>");
    ?>
    <center>
    <script src="app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<!-- <div id="MODAL_VIEW" class="modal"> -->
<div class = "SiteData">
    <!-- <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId"> -->
    <!-- <div class="modal-content"> -->
            <div class="card-header">
              <div class = "row">
                    <!-- <center> -->
                        <div class = "col-12">
                            <h4 class="card-title" style="align:center;"> <?php echo $Site ?> Detail</h4>
                        </div>
                    <!-- </center> -->
              </div>
              <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteNameWiseSurveyTable')">Excel</button>
            </div>
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation table-hover" id="SiteNameWiseSurveyTable" name="SiteNameWiseSurveyTable">
                                        <thead>
                                            <tr>
                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                <th style="background-color:#36abb9;color: white;">View</th>
                                                <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                <th style="background-color:#36abb9;color: white;">Pocket Name</th>
                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                <th style="background-color:#36abb9;color: white;">Total Rooms</th>
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
                                                        <td><?php echo "<b>" . $value["PocketName"] . "</b>" ?></td>
                                                        <td Title="<?php echo $value["MobileNo"]; ?>" style="cursor:pointer;"><?php echo $value["ExecutiveName"]; ?></td>
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
</center>
<!-- </div> -->
</div>
