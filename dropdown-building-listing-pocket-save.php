<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        // if(isset($_SESSION['SurveyUA_Election_Cd']) && isset($_SESSION['SurveyUA_ElectionName']))
        // {
        //     $electionCd = $_SESSION['SurveyUA_Election_Cd'];
        //     $electionName = $_SESSION['SurveyUA_ElectionName'];
        // }
        // $Site_CdPocket = '';
        // if(isset($_SESSION['SurveyUA_SiteString_Building_Listing']) && !empty($_SESSION['SurveyUA_SiteString_Building_Listing'])){
        //     $Site_CdPocket = $_SESSION['SurveyUA_SiteString_Building_Listing'] ;

        //     unset($_SESSION['SurveyUA_SiteString_Building_Listing']);
        // }else{
        //     $Site_CdPocket = "ALL";
        // }


        // if(isset($_SESSION['SurveyUA_SiteString_Building_Listing']) && !empty($_SESSION['SurveyUA_SiteString_Building_Listing'])){
        //     $SiteString = $_SESSION['SurveyUA_SiteString_Building_Listing'];
        //     $SiteString = explode("~",$SiteString);
        //     $Site_CdPocket = $SiteString[0];
        //     // $SiteName = $SiteString[1];
        // }
        // else{
        //     $Site_CdPocket = 'ALL';
        // }

        // if($Site_CdPocket == "ALL" || $Site_CdPocket == ""){
        //     $siteCondition = " AND Site_Cd <> '' ";
        // }else{
        //     $siteCondition = " AND Site_Cd = '$Site_CdPocket' ";
        // }


        


        $query = "SELECT 
                    COALESCE(Pocket_Cd,0) AS Pocket_Cd,
                    COALESCE(PocketName,'') AS PocketName,
                    COALESCE(PocketNo, 0) AS PocketNo
                FROM Pocket_Master 
                WHERE ElectionName = '$electionName'
                AND SiteName = '$SiteName'
                AND IsActive = 1 ;";
        // $query = "SELECT 
        //             COALESCE(Pocket_Cd,0) AS Pocket_Cd,
        //             COALESCE(PocketName,'') AS PocketName,
        //             COALESCE(PocketNameM, '') AS PocketNameM,
        //             COALESCE(Area,'') AS Area,
        //             COALESCE(AreaM,'') AS AreaM,
        //             COALESCE(ElectionName,'') AS ElectionName,
        //             COALESCE(PocketNo, 0) AS PocketNo,
        //             COALESCE(SiteName,'') AS SiteName,
        //             COALESCE(Site_Cd,0) AS Site_Cd,
        //             COALESCE(Ward_No,0) AS Ward_No,
        //             COALESCE(IsActive,0) AS IsActive,
        //             COALESCE(KMLFile_Url,'') AS KMLFile_Url,
        //             COALESCE(CONVERT(VARCHAR,DeActiveDate,100),'') AS DeActiveDate
        //         FROM Pocket_Master 
        //         WHERE ElectionName = '$electionName'
        //         $siteCondition
        //         AND IsActive = 1 ;";
        
    $dataPocket = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);
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
                                                
            <span area-hidden="true" style="color:red;">*</span></label>
        <div class="controls">
            <select class="select2 form-control"  name="pocketNameBLSave" disabled>
                <!-- <option value="ALL">ALL</option> -->
                 <?php
                if (sizeof($dataPocket)>0) 
                {
                    foreach ($dataPocket as $key => $value) 
                      {
                          if($Pocket_Cd == $value["Pocket_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Pocket_Cd']; ?>~<?php echo $value['PocketName'] ?>"><?php echo $value['PocketName']." (".$value['PocketNo'].")"; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Pocket_Cd"];?>~<?php echo $value['PocketName'] ?>"><?php echo $value["PocketName"]." (".$value['PocketNo'].")";?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->