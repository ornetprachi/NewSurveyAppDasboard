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
  
  if(isset($_GET['SupervisorName']) && !empty($_GET['SupervisorName']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_SupervisorName_For_Modal'] = $_GET['SupervisorName'];
            $SupervisorName =  $_SESSION['SurveyUA_SupervisorName_For_Modal'];
            
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
    $Supervisor = $_SESSION['SurveyUA_SupervisorName_For_Modal'];
    // echo "$Site";
    $SupervisorWiseQuery = " SELECT 
                    COALESCE(ssm.SupervisorName,'') AS SupervisorName,
                    COALESCE(ssd.SiteName,'') AS SiteName,
                    COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
                    COALESCE(count(DISTINCT(ss.Society_Cd)),'') AS SocietyCount,
                    COALESCE(sum(ss.RoomSurveyDone),0) AS RoomSurveyDone,
                    COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
                    COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
                    COALESCE(sum(ss.LockRoom),0) AS LockRoom,
                    COALESCE(sum(ss.BirthdaysCount),0) AS BirthdaysCount,
                    COALESCE(count(DISTINCT ss.SurveyBy),0) AS SurveyBy,
                    COALESCE(sum(ss.LBS),0) AS LBS,
                    COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount
                    FROM DataAnalysis..SurveySummary as ssd
                    LEFT JOIN DataAnalysis..SurveySummaryDateWise as ss on (ssd.Society_Cd = ss.Society_Cd)
                    INNER JOIN Survey_Entry_Data..Site_Master as ssm on(ssd.Site_Cd = ssm.Site_Cd) 
                    INNER JOIN Survey_Entry_Data..Election_Master as elm on (ssd.ElectionName = elm.ElectionName)
                    WHERE elm.ULB = '$ULB' AND ssm.SupervisorName = '$Supervisor'
                    GROUP BY ssm.SupervisorName,ssd.SiteName
                    ORDER BY ssm.SupervisorName
                    ;";

    $SupervisorNamedata = $db->ExecutveQueryMultipleRowSALData($SupervisorWiseQuery, $userName, $appName, $developmentMode);

    // print_r("<pre>");
    // print_r($SupervisorNamedata);
    // print_r("</pre>");
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
                  <h4 class="card-title"> <?php echo $Supervisor; ?> Detail</h4>
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
                                    <table class="table table-hover-animation table-hover" id="SupervisorWiseSite">
                                    <thead>
                                            <tr>
                                                <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;visible:flase;">Site Name</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;">Listing</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Survey Society">Survey Soc</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Pending Society">Pending Soc</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Rooms">Ro</th>
                                                <th style="background-color:#36abb9;color: white;padding-left:20px;" Title = "Voters">V</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title="NonVoters">NV</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "LockRoom">LR</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title= "Locked But Survey">LBS</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile">Mob</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Birthdate">BirtDt</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Society Ratio">Soc %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Voters Ratio">V %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">NV %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LR %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "NonVoters Ratio">LBS %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "BirthDate Ratio">BirDt %</th>
                                                <th class="text-center" style="background-color:#36abb9;color: white;" Title = "Mobile Ratio">Mob %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(sizeof($SupervisorNamedata) > 0 ){
                                                $srNo = 1;
                                                foreach ($SupervisorNamedata as $key => $value) {
                                                ?> 
                                                    <tr style="padding-top:0px;">
                                                        <td><?php echo $srNo++; ?></td>
                                                        <td class="text-center"><?php echo $value["SiteName"]; ?></td>
                                                        <td class="text-center"><?php echo $value["Listing"]; ?></td>
                                                        <td class="text-center"><?php echo $value["SocietyCount"]; ?></td>
                                                        <td class="text-center" ><?php echo ($value["Listing"]-$value["SocietyCount"]); ?></td>
                                                        <td class="text-center"><?php echo $value["RoomSurveyDone"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalVoters"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalNonVoters"]; ?></td>
                                                        <td class="text-center"><?php echo $value["LockRoom"]; ?></td>
                                                        <td class="text-center"><?php echo $value["LBS"]; ?></td>
                                                        <td class="text-center"><?php echo $value["TotalMobileCount"]; ?></td>
                                                        <td class="text-center"><?php echo $value["BirthdaysCount"]; ?></td>
                                                        <td class="text-center"><?php if($value["SocietyCount"] != ''){echo CEIL(($value["SocietyCount"]/$value["Listing"])*100)."%"; }?></td>
                                                        <td class="text-center"><?php if($value["TotalVoters"] != ''){echo CEIL(($value["TotalVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                        <td class="text-center"><?php if($value["TotalNonVoters"] != ''){echo CEIL(($value["TotalNonVoters"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                        <td class="text-center"><?php if($value["LockRoom"] != ''){echo CEIL(($value["LockRoom"]/$value["RoomSurveyDone"])*100)."%"; }?></td>
                                                        <td class="text-center"><?php if($value["LBS"] != ''){echo CEIL(($value["LBS"]/$value["RoomSurveyDone"])*100)."%";} ?></td>
                                                        <td class="text-center"><?php if($value["BirthdaysCount"] != ''){echo CEIL(($value["BirthdaysCount"]/($value["TotalVoters"]+$value["TotalNonVoters"]))*100)."%";} ?></td>
                                                        <td class="text-center"><?php if($value["TotalMobileCount"] != ''){echo CEIL(($value["TotalMobileCount"]/$value["RoomSurveyDone"])*100)."%"; }  ?></td>
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

<script>
  
  $(document).ready(function () {
    $('#SupervisorWiseSite').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});

</script>