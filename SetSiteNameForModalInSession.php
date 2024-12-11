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


    $SiteWiseQuery = "SELECT 
    COALESCE(ssd.SiteName, '') AS SiteName,
    COALESCE(ssd.ElectionName, '') AS ElectionName,
    COALESCE(ssd.SocietyName,'') AS SocietyName,
    COALESCE(em.ExecutiveName,'') AS ExecutiveName,  
    COALESCE(em.MobileNo,'') AS MobileNo,
    COALESCE(pm.PocketName,'') AS PocketName,
    COALESCE(sum(ssd.NewRooms),'') AS Rooms, 
    COALESCE(sum(sm.Rooms),'') AS TotalRoom,
    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
    COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
    COALESCE(sum(ss.LBS),0) AS LBS,
    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
    FROM DataAnalysis..SurveySummaryExecutiveDateWise as ss
    INNER JOIN DataAnalysis..SurveySummary as ssd on (ss.Society_Cd = ssd.Society_Cd)
    LEFT JOIN Survey_Entry_Data..Society_Master as sm on (ss.Society_Cd = sm.Society_Cd)
    LEFT JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
    LEFT JOIN  Survey_Entry_Data..Executive_Master as em on (ss.SurveyBy = em.UserName COLLATE Latin1_General_CI_AI)
    LEFT JOIN Survey_Entry_Data..Pocket_Master as pm on (ssd.Pocket_Cd = pm.Pocket_Cd)
    WHERE elm.ULB = '$ULB' AND ssd.SiteName = '$Site'
    GROUP BY ssd.SiteName,ssd.SocietyName,em.ExecutiveName,em.MobileNo,ssd.ElectionName,pm.PocketName
    ORDER BY ssd.SiteName;";

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

    $SiteWise = $db->ExecutveQueryMultipleRowSALData($SiteWiseQuery, $userName, $appName, $developmentMode);
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
