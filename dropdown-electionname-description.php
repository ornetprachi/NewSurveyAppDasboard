<?php

      $db=new DbOperation();
      $userName=$_SESSION['TREE_UserName'];
      $appName=$_SESSION['TREE_AppName'];
      $electionCd=$_SESSION['TREE_Election_Cd'];
      $electionName=$_SESSION['TREE_ElectionName'];
      $developmentMode=$_SESSION['TREE_DevelopmentMode'];

      $dataElectionName = $db->getTreeCensusCorporationElectionData($userName, $appName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Corporation</label>
        <div class="controls">
            <select class="select2 form-control" name="electionName" onChange="setElectionNameInSession(this.value)" >
                 <?php
                if (sizeof($dataElectionName)>0) 
                {
                    foreach ($dataElectionName as $key => $value) 
                      {
                          if($_SESSION['TREE_Election_Cd'] == $value["Election_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Election_Cd']; ?>"><?php echo $value["ElectionName"]." ".$value["CorporationName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Election_Cd"];?>"><?php echo $value["ElectionName"]." ".$value["CorporationName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->