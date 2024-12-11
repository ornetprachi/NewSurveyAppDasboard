<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

if(isset($_SESSION['SurveyUA_SiteCd_SurveyQC']) && isset($_SESSION['SurveyUA_SiteName_SurveyQC'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_SurveyQC'];
    $SiteName = $_SESSION['SurveyUA_SiteName_SurveyQC'];
}


if($ULB == 'PANVEL'){
    $ElectionCond = " AND sm.ElectionName = 'PT188' ";
}else{
    $ElectionCond = " ";
}

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
        FROM Site_Master sm
        INNER JOIN Survey_Entry_Data..Election_Master em ON (sm.ElectionName = em.ElectionName)
        WHERE em.ULB = '$ULB' 
        $ElectionCond
        ";

$dataSite = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site<span area-hidden="true" style="color:red;">*</span></label>
         <div class="controls">
            <select class="select2 form-control"  name="SiteName" onchange="setSiteSurveyQCInSession(this.value)" >
                <option value="">--SELECT--</option>
                <?php
                if (sizeof($dataSite)>0) 
                {
                    foreach ($dataSite as $key => $value) 
                      {
                          if($Site_Cd == $value["Site_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Site_Cd']; ?>~<?php echo $value['SiteName'];?>~<?php echo $value['ElectionName'];?>~<?php echo $value['Election_Cd'];?>"><?php echo "<b>" . $value["SiteName"] . "</b> - " . $value["ClientName"] . " (" . $value["SiteStatus"] . ")"  ; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value['Site_Cd']; ?>~<?php echo $value['SiteName'];?>~<?php echo $value['ElectionName'];?>~<?php echo $value['Election_Cd'];?>"><?php echo "<b>" . $value["SiteName"] . "</b> - " . $value["ClientName"] . " (" . $value["SiteStatus"] . ")"  ; ?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->