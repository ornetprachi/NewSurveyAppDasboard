<?php

      $db=new DbOperation();
      $userName=$_SESSION['SurveyUA_UserName'];
      $appName=$_SESSION['SurveyUA_AppName'];
      $electionCd=$_SESSION['SurveyUA_Election_Cd'];
      $electionName=$_SESSION['SurveyUA_ElectionName'];
      $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
      
      $dataElectionNameSocietyAssign = $db->getSurveyUtilityCorporationElectionData($userName, $appName, $developmentMode);
    
      
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Corporation</label>
        <div class="controls">
            <select class="select2 form-control" name="electionName" onChange="setElectionNameSocietyAssignInSession(this.value)" >
            <option value="">--Select--</option>
                 <?php
                if (sizeof($dataElectionNameSocietyAssign)>0) 
                {
                    foreach ($dataElectionNameSocietyAssign as $key => $value) 
                      {
                          if($_SESSION['SurveyUA_Election_Cd'] == $value["Election_Cd"])
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