<?php

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
    $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

    $ServerName = '';
    
    $query = "SELECT DISTINCT(ServerName) FROM Survey_Entry_Data..Election_Master WHERE ServerName IS NOT NULL AND ServerName <> 'NULL';";
    $FromServerNameData = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

    // echo "<pre>";
    // print_r($FromServerNameData);
      
?>

<!-- <div class="col-sm-12"> -->
<div class="form-group">
    <label>Server</label>
    <div class="controls">
        <select class="select2 form-control" name="FromServerName" onChange="setFromServerNameInSession(this.value)">
        <option value="">--SELECT--</option>
            <?php
            if (sizeof($FromServerNameData)>0) 
            {
                foreach ($FromServerNameData as $key => $value) 
                    {
                        if($FromServerName == $value["ServerName"])
                        {
            ?>
                        <option selected="true" value="<?php echo $value['ServerName']; ?>"><?php echo $value["ServerName"]; ?></option>
            <?php
                        }
                        else
                        {
            ?>
                        <option value="<?php echo $value["ServerName"];?>"><?php echo $value["ServerName"];?></option>
            <?php
                        }
                    }
                }
            ?> 
        </select>
    </div>
</div>
<!-- </div> -->