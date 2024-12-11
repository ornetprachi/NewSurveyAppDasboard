<?php

      $db=new DbOperation();
      $userName=$_SESSION['SurveyUA_UserName'];
      $appName=$_SESSION['SurveyUA_AppName'];
      $electionCd=$_SESSION['SurveyUA_Election_Cd'];
      $electionName=$_SESSION['SurveyUA_ElectionName'];
      $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
      
      $dataElectionNameAverageCount = $db->getSurveyUtilityCorporationElectionData($userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Corporation<span area-hidden="true" style="color:red;">*</span></label>
        <div class="controls">
            <select class="select2 form-control" name="electionName" onchange="setElectionNameBuildingListingInSession(this.value)" >
            <option value="">--Select--</option>
                 <?php
                if (sizeof($dataElectionNameAverageCount)>0) 
                {
                    foreach ($dataElectionNameAverageCount as $key => $value) 
                      {
                          if($electionCd == $value["Election_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Election_Cd']; ?>"><?php echo $value["ElectionName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Election_Cd"];?>"><?php echo $value["ElectionName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->