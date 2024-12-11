<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        $nodeName = "";
        $Site_Cd = "";
        $executive_Cd = "";
        $pocket_Cd = "";
        
        
        $PocketListquery = "SELECT 
                    COALESCE(Pocket_Cd,0) AS Pocket_Cd,
                    COALESCE(PocketName,'') AS PocketName,
                    COALESCE(PocketNameM, '') AS PocketNameM,
                    COALESCE(Area,'') AS Area,
                    COALESCE(AreaM,'') AS AreaM,
                    COALESCE(ElectionName,'') AS ElectionName,
                    COALESCE(PocketNo, 0) AS PocketNo,
                    COALESCE(SiteName,'') AS SiteName,
                    COALESCE(Site_Cd,0) AS Site_Cd,
                    COALESCE(Ward_No,0) AS Ward_No,
                    COALESCE(IsActive,0) AS IsActive,
                    COALESCE(KMLFile_Url,'') AS KMLFile_Url,
                    COALESCE(CONVERT(VARCHAR,DeActiveDate,100),'') AS DeActiveDate
                FROM Pocket_Master 
                WHERE IsActive = 1
                ORDER BY PocketName ;";
        
        $PocketListData = $db->ExecutveQueryMultipleRowSALData($PocketListquery, $userName, $appName, $developmentMode);
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
                <option value="">--Select--</option>
                 <?php
                if (sizeof($PocketListData)>0) 
                {
                    foreach ($PocketListData as $key => $value) 
                      {
                          if($pocket_Cd == $value["Pocket_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Pocket_Cd']; ?>"><?php echo $value["PocketName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Pocket_Cd"];?>"><?php echo $value["PocketName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->