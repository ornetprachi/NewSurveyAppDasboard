<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB = $_SESSION['SurveyUtility_ULB'];

        
        $Site_Cd = "";
        $executive_Cd = "";
        $pocket_Cd = "";

        if(isset($_SESSION['SurveyUA_Pocket_Cd_Society_Assign'])){
            $pocket_Cd = $_SESSION['SurveyUA_Pocket_Cd_Society_Assign'];
        }else{
            $pocket_Cd = "All";
            $_SESSION['SurveyUA_Pocket_Cd_Society_Assign'] = $pocket_Cd;
        }

    if(isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']) &&
    isset($_SESSION['SurveyUA_SiteCd_Society_Assign']) && !empty($_SESSION['SurveyUA_SiteCd_Society_Assign'])){

        $electionName = $_SESSION['SurveyUA_ElectionName'];
        $Site_Cd = $_SESSION['SurveyUA_SiteCd_Society_Assign'];

        if($Site_Cd == 'All'){
            $siteCondition = "";
        }else{
            $siteCondition = " AND Site_Cd = '$Site_Cd' ";
        }

 $pocketDropdownQuery = "SELECT 
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
                $siteCondition 
                ;";
				//ElectionName = '$electionName'
        
        $dataPocketSocietyAssign = $db->ExecutveQueryMultipleRowSALData($ULB, $pocketDropdownQuery, $userName, $appName, $developmentMode);

    }else{
        $dataPocketSocietyAssign = array();
    }


    if(isset($_SESSION['SurveyUA_Election_Cd'])){
        $executive_Cd = $_SESSION['SurveyUA_Election_Cd'];
    }else{
        $executive_Cd = "All";
    }

        $executiveCondition = "";
        $siteCondition = "";        
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
            <select class="select2 form-control"  name="pocketName" onChange="setPocketSocietyAssignInSession(this.value)">
                <option <?php echo $pocket_Cd == "All" ? 'selected=true' : '';?> value="All">All</option>
                 <?php
                if (sizeof($dataPocketSocietyAssign)>0) 
                {
                    //  if(!isset($_SESSION['SurveyUA_PocketCd'])){
                    //     $_SESSION['SurveyUA_PocketCd'] = $dataPocketSocietyAssign[0]["Pocket_Cd"];
                    //     $pocket_Cd = $_SESSION['SurveyUA_PocketCd'];
                    //  }
                    foreach ($dataPocketSocietyAssign as $key => $value) 
                      {
                          if($pocket_Cd == $value["Pocket_Cd"])
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