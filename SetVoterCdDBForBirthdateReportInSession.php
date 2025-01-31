<?php
//Changes by Prachi For Report
session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['VoterCd']) && !empty($_GET['VoterCd']) && isset($_GET['DBName']) && !empty($_GET['DBName'])){
    
    try  
        {  
            $VoterCd = $_GET['VoterCd'];
            $DBName = $_GET['DBName'];
            $_SESSION['SurveyUtility_VoterCd'] = $VoterCd;
            $_SESSION['SurveyUtility_DBName'] = $DBName;
   
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
$VoterCd = $_SESSION['SurveyUtility_VoterCd'];
$DBName = $_SESSION['SurveyUtility_DBName'];
 $AddreessQuery = "SELECT
                COALESCE(dw.Voter_Cd,0) AS Voter_Cd,
                COALESCE(socm.Latitude, '') AS Latitude,
                COALESCE(socm.Longitude, '') AS Longitude,
                COALESCE(socm.SocietyName, '') AS SocietyName,
                COALESCE(socm.Building_Plate_Image, '') AS Building_Plate_Image,
                COALESCE(socm.Building_Image, '') AS Building_Image,
                COALESCE(socm.Sector, '') AS Sector,
                COALESCE(socm.Plot_No, '') AS PlotNo,
                COALESCE(socm.Area, '') AS Area
                FROM Dw_VotersInfo dw
                INNER JOIN Society_Master socm ON (dw.Society_Cd = socm.Society_Cd)
                WHERE dw.SF = 1 
                -- AND CONVERT(varchar,CONVERT(date,dw.BirthDate,101),23) BETWEEN '1999-01-01' AND '2023-06-20'
                AND dw.Voter_Cd = '$VoterCd'";
                // print_r($AddreessQuery);

$SiteWise = $db->ExecutveQuerySingleRowSALData($ULB,$AddreessQuery, $userName, $appName, $developmentMode);
$SocietyAdd = '';
$Building_Image_Path = '';
if(sizeof($SiteWise)>0){    
    $Building_Plate_Image = $SiteWise['Building_Plate_Image'];
    $SocietyName = $SiteWise['SocietyName'];
    $Sector = $SiteWise['Sector'];
    if($Sector != "" && $Sector != "NULL"){
        $SectorVar = ", Sector : $Sector";
    }else{
        $SectorVar = "";
    }

    $PlotNo = $SiteWise['PlotNo'];
    if($PlotNo != "" && $PlotNo != "NULL"){
        $PlotVar = ", Plot No : $PlotNo";
    }else{
        $PlotVar = "";
    }
    $Area = $SiteWise['Area'];
    $Building_Image_Path = $SiteWise['Building_Image'];
    $SocietyAdd = $SocietyName." ".$SectorVar." ".$PlotVar.", ".$Area;
}


?>
<style>
  .modal-content {
      width: auto;
      border-radius: 0.5rem;
      overflow: hidden;
      border: none;
      box-shadow: 0 0 20px 0 rgb(0 0 0 / 10%);
      position: absolute;
      left: 50%;
      top: 1%; 
      margin-left: -320px;
      /* margin-top: -320px; */
  }
</style>
<!-- <center> -->
<div id="MODAL_VIEW" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="">
    <div class="modal-dialog modal-dialog-centered modal-xl chatapp-call-window" role="document" id="PropertyQCFilterFormId">
        <div class="modal-content" style="width:50%;">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <!-- <div class="col-xl-6 col-md-6 col-sm-12"> -->
                            <!-- <div class="card"> -->
                                <div class="card-content">
                                    <div class="card-body">
                                        <img class="card-img img-fluid mb-1" style="height:450px;" src="<?php echo $Building_Image_Path; ?>">
                                        <h5 class="mt-1"><b>Address : </b></h5>
                                        <b><p class="card-text"><b><?php echo $SocietyAdd; ?></b></p>
                                    </div>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- </center> -->