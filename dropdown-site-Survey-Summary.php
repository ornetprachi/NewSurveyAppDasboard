<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];


$query = "SELECT 
COALESCE(Site_Cd,0) AS Site_Cd, 
COALESCE(ClientName,'') AS ClientName,
COALESCE(SiteName,'') AS SiteName,
COALESCE(Area, '') AS Area,
COALESCE(Ward_No,0) AS Ward_No,
COALESCE(Address,'') AS Address,
COALESCE(ElectionName,'') AS ElectionName
FROM Site_Master WHERE ElectionName = '$electionName'  ";

$dataSite = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site</label>
         <div class="controls">
            <select class="select2 form-control"  name="siteName" onchange="setSiteSummaryReportInSession(this.value)" >
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($dataSite)>0) 
                {
                    foreach ($dataSite as $key => $value) 
                      {
                          if($siteName == $value["SiteName"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['SiteName']; ?>"><?php echo $value["SiteName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["SiteName"];?>"><?php echo $value["SiteName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->