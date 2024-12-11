<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];

        $nodeName = "";
        $Site_Cd = "";
        $executive_Cd = "";
        $pocket_Cd = "";
        
        // if(isset($_SESSION['TREE_NodeName'])){
        //     $nodeName = $_SESSION['TREE_NodeName'];
        // }else{
        //     $nodeName = "All";
        // }
        if(isset($_SESSION['SurveyUA_SiteCd'])){
            $Site_Cd = $_SESSION['SurveyUA_SiteCd'] ;
        }else{
            $Site_Cd = "All";
        }
        if(isset($_SESSION['SurveyUA_PocketCd'])){
            $pocket_Cd = $_SESSION['SurveyUA_PocketCd'];
        }else{
            $pocket_Cd = "All";
        }

        if(isset($_SESSION['SurveyUA_Executive_Cd'])){
            $executive_Cd = $_SESSION['SurveyUA_Executive_Cd'];
        }else{
            $executive_Cd = "All";
        }

        $executiveCondition = "";
        $siteCondition = "";

        if($executive_Cd == "All"){
            $executiveCondition = " AND tc.AddedBy <> '' ";
        }else{
            $addedBy = "";
            $query1 = "SELECT top (1) 
            ISNULL(um.UserName,'') as UserName
            FROM Survey_Entry_Data..User_Master um
            INNER JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = um.Executive_Cd
            WHERE um.AppName = '$appName'
            AND ISNULL(um.Executive_Cd,0) = '$executive_Cd' 
            ";
            
            $db1=new DbOperation();
            $dataExecutiveName = $db1->getSurveyUtilityExecutiveData($query1, $userName, $appName, $developmentMode);
            

            if(sizeof($dataExecutiveName)>0){
                $addedBy = $dataExecutiveName[0]["UserName"];
            }
            $executiveCondition = " AND tc.AddedBy = '$addedBy' ";
        }

        if($Site_Cd == "All"){
            $siteCondition = " WHERE Site_Cd <> '' ";
        }else{
            $siteCondition = " WHERE Site_Cd = '$Site_Cd' ";
        }

        if(isset($_GET['filter_date']) && $_GET['filter_date'] == "All" ){
            $dateCondition = "";
        }else{
            $dateCondition = " AND CONVERT(VARCHAR,tc.AddedDate,120) BETWEEN '$fromDate' AND '$toDate' ";
        }
        
        $query = "SELECT 
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
                $siteCondition
                AND IsActive = 1 ;";
				//ElectionName = '$electionName'
        
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
                <option value="">--Select--</option>
                 <?php
                if (sizeof($dataPocket)>0) 
                {
                     if(!isset($_SESSION['SurveyUA_PocketCd'])){
                        $_SESSION['SurveyUA_PocketCd'] = $dataPocket[0]["Pocket_Cd"];
                        $pocket_Cd = $_SESSION['SurveyUA_PocketCd'];
                     }


                    foreach ($dataPocket as $key => $value) 
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