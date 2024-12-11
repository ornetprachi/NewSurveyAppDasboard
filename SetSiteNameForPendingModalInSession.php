<?php
    session_start();
include 'api/includes/DbOperation.php'; 
include_once 'includes/ajaxscript.php'; 
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
            
            $_SESSION['SurveyUA_Site_For_PendinmgSoc_Modal'] = $_GET['Site'];
            $SiteName =  $_SESSION['SurveyUA_Site_For_PendinmgSoc_Modal'];
            
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
<style>
  .card-header {
    padding: 5px;
    margin-bottom: 0;
    background-color: rgba(34, 41, 47, 0.03);
    border-bottom: 1px solid rgba(34, 41, 47, 0.125);
}
</style>
<div class = "SiteData">
    <?php 
    $Site = $_SESSION['SurveyUA_Site_For_PendinmgSoc_Modal'];
    // echo "$Site";
    $SiteWiseQuery = "SELECT 
                    COALESCE(ssd.SiteName, '') AS SiteName,
                    COALESCE(ssd.SocietyName,'') AS SocietyName,
                    COALESCE(em.ExecutiveName,'') AS ExecutiveName,  
                    COALESCE(em.MobileNo,'') AS MobileNo, 
                    COALESCE(sum(ssd.RoomSurveyDone),0) AS RoomSurveyDone,
                    COALESCE(sum(ssd.NewRooms),0) AS TotalRooms,
                    COALESCE(sum(ssd.TotalVoters),0) AS TotalVoters,
                    COALESCE(sum(ssd.TotalNonVoters),0) AS TotalNonVoters,
                    COALESCE(sum(ssd.LockRoom),0) AS LockRoom,
                    COALESCE(sum(ssd.BirthdaysCount),0) AS BirthdaysCount,
                    COALESCE(count(DISTINCT ssd.ListedBy),0) AS ListedBy,
                    COALESCE(sum(ssd.LBS),0) AS LBS,
                    COALESCE(sum(ssd.TotalMobileCount),0) AS TotalMobileCount
                    FROM  DataAnalysis..SurveySummary as ssd 
                    LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd)
                    LEFT JOIN  Survey_Entry_Data..Executive_Master as em on (ssd.ListedBy = em.UserName)
                    WHERE ssd.ULB = '$ULB' AND ssd.SiteName = '$Site' AND ss.Society_Cd is null
                    GROUP BY ssd.SiteName,ssd.SocietyName,em.ExecutiveName,em.MobileNo
                    ORDER BY ssd.SiteName;";

    $SiteWise = $db->ExecutveQueryMultipleRowSALData($SiteWiseQuery, $userName, $appName, $developmentMode);
    ?>
<style>
    .myModal {
  display: none;
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 80%;
  height: 80%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

</style>
    <center>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div id="MODAL_VIEW" class="modal">
    <!-- <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" > -->
        <div class="modal-content" style="width:80%;">
            <div class="card-header">
              <div class = "row">
                <div class = "col-10">
                  <h4 class="card-title"> <?php echo $Site ?> Detail</h4>
                </div>
                <div class="col-2">
                  <?php if($ExcelExportButton == "show"){ ?>
                  <span><button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWiseSociety')">Excel</button></span>
                  <?php } ?>
                  <span class="close" onclick="CloseModal()">&times;</span>
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
                                    <table class="table table-hover-animation table-hover" id="SiteWiseSociety">
                                        <thead>
                                            <tr>
                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                <th style="background-color:#36abb9;color: white;">Society Name</th>
                                                <th style="background-color:#36abb9;color: white;">Executive Name</th>
                                                <th style="background-color:#36abb9;color: white;">Total Rooms</th>
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
                                                        <td><?php echo "<b>" . $value["SocietyName"] . "</b>" ?></td>
                                                        <td Title="<?php echo $value["MobileNo"]; ?>" style="cursor:pointer;"><?php echo $value["ExecutiveName"]; ?></td>
                                                        <td ><?php echo $value["TotalRooms"]; ?></td>
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
    </div>
</div>
</center>
</div>
   
</div>
<script>
    $(document).ready(function() {
  $(".close").click(function() {
    $("#myModal").hide();
  });
});
</script>
