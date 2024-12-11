<?php
    session_start();
include 'api/includes/DbOperation.php'; 
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

 $Div = $_SESSION['SurveyUA_Div']; 

  if($ServerIP == "103.14.99.154"){
    $ServerIP =".";
  }else{
      $ServerIP ="103.14.99.154";
  }

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Site']) && !empty($_GET['Site']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Site_Name_Qc'] = $_GET['Site'];
            $SiteName = $_SESSION['SurveyUA_Site_Name_Qc'];
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
    /* padding: 5px; */
    margin-bottom: 0;
    background-color: rgba(34, 41, 47, 0.03);
    border-bottom: 1px solid rgba(34, 41, 47, 0.125);
}
</style>
<div class = "QcSiteData">
    <?php 
    $Site = $_SESSION['SurveyUA_Site_Name_Qc'];
    // echo "$Site";
    $SiteWiseQuery = "SELECT 
          COALESCE(CONVERT(varchar,ss.SDate,23),'') AS SurveyDate, 
          COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing, 
          (SELECT COUNT(DISTINCT(Society_Cd)) 
          FROM Survey_Entry_Data..Society_Master 
          WHERE BList_QC_UpdatedFlag = 1 AND SiteName = '$Site'
          AND CONVERT(varchar,BList_QC_UpdatedDate,23) = CONVERT(varchar,ss.SDate,23)) ListingQc, 
          COALESCE(sum(ss.TotalVoters),0) AS TotalVoters, 
          COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters, 
          COALESCE(sum(ss.VoterQCDone),0) AS VoterQCDone, 
          COALESCE(sum(ss.NonVoterQCDone),0) AS NonVoterQCDone, 
          COALESCE(sum(ss.NonVotersConverted),0) AS NonVotersConverted, 
          COALESCE(sum(ss.WrongMobileNo),0) AS WrongMobileNo, 
          COALESCE(sum(ss.TotalMobileCount),0) AS TotalMobileCount,
          COALESCE(um.ExecutiveName ,'') AS ExecutiveName, 
          COALESCE(um.UserName ,'') AS UserName 
          FROM DataAnalysis..SurveySummary as ssd 
          LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd) 
          LEFT JOIN Survey_Entry_Data..User_Master as um on (ssd.SurveyBy = um.UserName) 
          WHERE ssd.ULB = '$ULB' 
          AND ssd.SiteName = '$Site' 
          AND CONVERT(varchar,ss.SDate,23) is not null 
          GROUP BY CONVERT(varchar,ss.SDate,23),um.ExecutiveName,um.UserName
          ORDER BY CONVERT(varchar,ss.SDate,23) DESC, um.ExecutiveName ASC
          ";
        //   SELECT 
        //   COALESCE(CONVERT(varchar,ss.SDate,23),'') AS SurveyDate,
        //   COALESCE(count(DISTINCT(ssd.Society_Cd)),'') AS Listing,
        //   (SELECT 
        //   COUNT(DISTINCT(Society_Cd))
        //   FROM Survey_Entry_Data..Society_Master WHERE BList_QC_UpdatedFlag = 1 AND SiteName = 'AG178' AND CONVERT(varchar,BList_QC_UpdatedDate,23) = CONVERT(varchar,ss.SDate,23)) ListingQc,
        //   COALESCE(sum(ss.TotalVoters),0) AS TotalVoters,
        //   COALESCE(sum(ss.TotalNonVoters),0) AS TotalNonVoters,
        //   COALESCE(sum(ss.VoterQCDone),0) AS VoterQCDone,
        //   COALESCE(sum(ss.NonVoterQCDone),0) AS NonVoterQCDone,
        //   COALESCE(sum(ss.NonVotersConverted),0) AS NonVotersConverted,
        // COALESCE(sum(ss.WrongMobileNo),0) AS WrongMobileNo
        //   FROM DataAnalysis..SurveySummary as ssd
        //   LEFT JOIN DataAnalysis..SurveySummaryExecutiveDateWise as ss on (ssd.Society_Cd = ss.Society_Cd)
        //   WHERE ssd.ULB = '$ULB' AND ssd.SiteName = '$Site' AND CONVERT(varchar,ss.SDate,23) is not null
        //   GROUP BY CONVERT(varchar,ss.SDate,23)
        //   ORDER BY CONVERT(varchar,ss.SDate,23) DESC

    $SiteWise = $db->ExecutveQueryMultipleRowSALData($SiteWiseQuery, $userName, $appName, $developmentMode);
    ?>

    <!-- <center> -->
  <div id="SiteQcdata" class="SiteQcdata" style="display:none;">
    <div class="card-header">
          <h4 class="card-title"> <?php echo $Site ?> Detail :</h4>
          <?php if($ExcelExportButton == "show"){ ?>
            <span><button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SiteWiseSociety')">Excel</button></span>
          <?php } ?>
    </div>
    <section id="basic-datatable">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-content">
              <div class="card-body card-dashboard">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered complex-headers" id="SiteWiseSociety">
                    <thead>
                      <tr>
                          <th class="text-center" style="background-color:#36abb9;color: white;" rowspan="2">Sr No</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" rowspan="2">Survey Date</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" rowspan="2">Executive Name</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" colspan="2">Listing</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" colspan="2">Voters</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" colspan="3">NonVoters</th>
                          <th class="text-center" style="background-color:#36abb9;color: white;" colspan="3">Mobile No</th>
                      </tr>
                      <tr>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Total</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Qc</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Total</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Qc</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Total</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Qc</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Converted</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Total</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Wrong</th>
                        <th class="text-center" style="background-color:#36abb9;color: white;" >Percentage</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      if(sizeof($SiteWise) > 0 ){
                          $srNo = 1;
                          foreach ($SiteWise as $key => $value) {
                          ?> 
                              <tr style="padding-top:0px;">
                                  <td class="text-center"><?php echo $srNo++; ?></td>
                                  <td class="text-center" ><?php echo $value["SurveyDate"]; ?></td>
                                  <td class="" ><?php echo $value["ExecutiveName"]; ?></td>
                                  <td class="text-center"><?php echo $value["Listing"]; ?></td>
                                  <td class="text-center"><?php echo $value["ListingQc"]; ?></td>
                                  <td class="text-center"><?php echo $value["TotalVoters"]; ?></td>
                                  <td class="text-center"><?php echo $value["VoterQCDone"]; ?></td>
                                  <td class="text-center"><?php echo $value["TotalNonVoters"]; ?></td>
                                  <td class="text-center"><?php echo $value["NonVoterQCDone"]; ?></td>
                                  <td class="text-center"><?php echo $value["NonVotersConverted"]; ?></td>
                                  <td class="text-center"><?php echo $value["TotalMobileCount"]; ?></td>
                                  <td class="text-center"><?php echo $value["WrongMobileNo"]; ?></td>
                                  <td class="text-center">
                                    <?php 
                                          if($value["TotalMobileCount"] != 0){
                                              $Percentage = ($value["WrongMobileNo"]/$value["TotalMobileCount"])*100;
                                              echo $Percentage = number_format($Percentage, 2)." %";
                                          }else{
                                              echo "0.00 %";
                                          }
                                      ?>     
                                  </td>
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
  </div>
<!-- </center> -->
</div>
   
<!-- </div> -->
<script>
  $(document).ready(function () {
    $('#SiteWiseSociety').DataTable({
      "lengthMenu": [ [-1,20, 40, 50], ["All",20, 40, 50] ]
    });
});
</script>