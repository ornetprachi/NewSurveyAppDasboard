<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        // $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        // $ExecutiveCd = "";

        // if(isset($_SESSION['SurveyUA_Executive_Society_Assign'])){
        //    $ExecutiveCd = $_SESSION['SurveyUA_Executive_Society_Assign']; 
        // }

        $ExecutiveListQuery = "SELECT 
            COALESCE(sm.BList_UpdatedByUser,'') AS UserName,
            COALESCE(em.Executive_Cd,0) AS Executive_Cd,
            COALESCE(em.ExecutiveName,'') AS ExecutiveName,
            COALESCE(um.Mobile,'') AS Mobile,
            COALESCE(um.UserType , '') AS UserType
        FROM Society_Master sm
        INNER JOIN User_Master um ON (sm.BList_UpdatedByUser = um.UserName)
        INNER JOIN Executive_Master em ON (um.Executive_Cd = em.Executive_Cd)
        WHERE sm.SiteName = '$SiteName'
        GROUP BY sm.BList_UpdatedByUser,em.Executive_Cd,em.ExecutiveName,um.Mobile,um.UserType
                ";
        // $ExecutiveListQuery = "SELECT 
        //         ISNULL(em.Executive_Cd, 0) AS Executive_Cd,
        //         ISNULL(em.ExecutiveName, '') AS ExecutiveName,
        //         ISNULL(um.User_Id, 0) AS User_Id,
        //         ISNULL(um.Mobile, '') AS Mobile,
        //         ISNULL(um.UserType , '') AS UserType,
        //         um.DeactiveFlag
        //         FROM User_Master um
        //         INNER JOIN Executive_Master em on em.Executive_Cd = um.Executive_Cd
        //         WHERE um.AppName = '$appName' 
        //         --AND DbName = 'Survey_Entry_Data'
        //         AND (um.DeactiveFlag IS NULL OR um.DeactiveFlag = '')
        //         AND um.Upload_SyncFlag = 1
        //         AND um.Download_SyncFlag = 1 
        //         ORDER BY em.ExecutiveName
        //         ";
        
        $ExecutiveData = $db1->ExecutveQueryMultipleRowSALData($ExecutiveListQuery, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Executive Name</label>
        <div class="controls">
            <select class="select2 form-control" name="executiveName">
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($ExecutiveData)>0) 
                {
                  
                    foreach ($ExecutiveData as $key => $value) 
                      {
                          if( $UserNameDD == $value["UserName"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['UserName']; ?>"><?php echo $value["ExecutiveName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["UserName"];?>"><?php echo $value["ExecutiveName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->