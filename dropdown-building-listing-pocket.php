<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];

        $query = "SELECT 
                    COALESCE(Pocket_Cd,0) AS Pocket_Cd,
                    COALESCE(PocketName,'') AS PocketName,
                    COALESCE(PocketNo, 0) AS PocketNo
                FROM Pocket_Master 
                WHERE SiteName = '$SiteName'
                AND IsActive = 1 ;";
        
    $dataPocket = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>
         <?php if(isset($_SESSION['Form_Language']) && $_SESSION['Form_Language'] == 'English')
            {  
                echo "Pocket";
            }
            else if(isset($_SESSION['Form_Language']) && $_SESSION['Form_Language'] == 'Marathi')
            { 
                echo "पॉकेट";
            }  
            ?>
                                                
        </label>
        <div class="controls">
            <select class="select2 form-control"  name="pocketName">
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($dataPocket)>0) 
                {
                    foreach ($dataPocket as $key => $value) 
                      {
                          if($Pocket_Cd == $value["Pocket_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Pocket_Cd']; ?>"><?php echo $value["PocketName"]." (".$value['PocketNo'].")"; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Pocket_Cd"];?>"><?php echo $value["PocketName"]." (".$value['PocketNo'].")";?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->