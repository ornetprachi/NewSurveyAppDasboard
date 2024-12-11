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
    $Con = "AND CONVERT(varchar,ss.ListedDate,23) BETWEEN '$fromdate' AND '$todate'";
}else{
    $fromdate = date('Y-m-d');
    $todate = date('Y-m-d');
    $Con = "";
}
// echo $fromdate;
?>


    <?php 
    $Site = $_SESSION['SurveyUA_Site_For_DetailModal'];
    // echo "$Site";
 
    $SiteWiseQuery = "SELECT 
                        COALESCE(ss.SiteName, '') AS SiteName, 
                        COALESCE(ss.ElectionName, '') AS ElectionName, 
                        COALESCE(ss.SocietyName,'') AS SocietyName,
                        COALESCE(em.ExecutiveName,'') AS ListedBy,
                        COALESCE(sm.PlotNo,'') AS PlotNo, 
                        COALESCE(em.MobileNo,'') AS MobileNo,
                        COALESCE(pm.PocketName,'') AS PocketName, 
                        COALESCE(pm.PocketNo,'') as PocketNo,
		                COALESCE(sum(ss.NewRooms),'') AS Rooms,
                        COALESCE(sum(sm.Rooms),'') AS TotalRoom, 
                        COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
                        COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
                        COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
                        COALESCE(sum(ss.LockRoom),0) AS LockRoom, 
                        COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
                        COALESCE(sum(ss.LBS),0) AS LBS, 
                        COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
                        FROM DataAnalysis..SurveySummary as ss 
                        INNER JOIN Survey_Entry_Data..Election_Master as elm on (ss.ElectionName = elm.ElectionName) 
                        LEFT JOIN Survey_Entry_Data..Executive_Master as em on (ss.ListedBy = em.UserName)
                        LEFT  JOIN Survey_Entry_Data..Pocket_Master as pm on (ss.Pocket_Cd = pm.Pocket_Cd)
                        LEFT JOIN Survey_Entry_Data..Society_Master as sm on (ss.Society_Cd =sm.Society_Cd) 
                        WHERE elm.ULB = '$ULB' AND ss.SiteName = '$Site' $Con
                        GROUP BY ss.SiteName,ss.SocietyName,em.ExecutiveName,em.MobileNo,ss.ElectionName,pm.PocketName,pm.PocketNo,sm.PlotNo
                        ORDER BY ss.SiteName;";
    
    $SiteWise = $db->ExecutveQueryMultipleRowSALData($SiteWiseQuery, $userName, $appName, $developmentMode);
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