<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];
$electionName=$_SESSION['SurveyUA_ElectionName'];

if(isset($_SESSION['SurveyUA_SiteCd_QC_Assign']) && isset($_SESSION['SurveyUA_SiteName_QC_Assign'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_QC_Assign'];
    $SiteName = $_SESSION['SurveyUA_SiteName_QC_Assign'];
}

if($ULB == 'PANVEL'){
    $ElectionCond = " AND sm.ElectionName = 'PT188' ";
}else{
    $ElectionCond = " ";
}

$DBName = $db->GetDBNameULB($ULB,$userName, $appName, $developmentMode);
$query = "SELECT 
            COALESCE(sm.Site_Cd,0) AS Site_Cd, 
            COALESCE(sm.ClientName,'') AS ClientName,
            COALESCE(sm.SiteName,'') AS SiteName,
            COALESCE(sm.Area, '') AS Area,
            COALESCE(sm.Ward_No,0) AS Ward_No,
            COALESCE(sm.Address,'') AS Address,
            COALESCE(sm.ElectionName,'') AS ElectionName,
            COALESCE(em.Election_Cd,0) AS Election_Cd,
            COALESCE(sm.SiteStatus,'') AS SiteStatus
        FROM $DBName..Site_Master sm
        INNER JOIN Election_Master em ON (sm.ElectionName = em.ElectionName)
        WHERE em.ULB = '$ULB' 
        $ElectionCond
        ";

$dataSite = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site<span area-hidden="true" style="color:red;">*</span></label>
         <div class="controls">
            <select class="select2 form-control"  name="SiteName" onchange="setSiteQCAssignInSession(this.value)" >
                <option value="">--SELECT--</option>
                 <?php
                if (sizeof($dataSite)>0) 
                {
                    foreach ($dataSite as $key => $value) 
                      {
                          if($Site_Cd == $value["Site_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Site_Cd']; ?>~<?php echo $value["SiteName"];?>~<?php echo $value["ElectionName"];?>~<?php echo $value["Election_Cd"];?>"><?php echo "<b>" . $value["SiteName"] . "</b> - " . $value["ClientName"] . " (" . $value["SiteStatus"] . ")"  ; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value['Site_Cd']; ?>~<?php echo $value["SiteName"];?>~<?php echo $value["ElectionName"];?>~<?php echo $value["Election_Cd"];?>"><?php echo "<b>" . $value["SiteName"] . "</b> - " . $value["ClientName"] . " (" . $value["SiteStatus"] . ")"  ; ?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->