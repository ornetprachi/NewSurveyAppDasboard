<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        // $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];

        // $ExecutiveCd = "";

        // if(isset($_SESSION['SurveyUA_Executive_Society_Assign'])){
        //    $ExecutiveCd = $_SESSION['SurveyUA_Executive_Society_Assign']; 
        // }

        $ExecutiveListQuery = "SELECT 
                ISNULL(em.Executive_Cd, 0) AS Executive_Cd,
                ISNULL(em.ExecutiveName, '') AS ExecutiveName,
                ISNULL(um.User_Id, 0) AS User_Id,
                ISNULL(um.Mobile, '') AS Mobile,
                ISNULL(um.UserType , '') AS UserType,
                um.DeactiveFlag
                FROM Survey_Entry_Data..User_Master um
                INNER JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = um.Executive_Cd
                WHERE um.AppName = '$appName' 
                AND (um.DeactiveFlag IS NULL OR um.DeactiveFlag = '')
                AND um.Upload_SyncFlag = 1
                AND um.Download_SyncFlag = 1 
                ORDER BY em.ExecutiveName
                ";
        // $ExecutiveListQuery = "SELECT 
        //     DISTINCT(sm.QC_Assign_To) AS Executive_Cd,
        //     ISNULL(em.ExecutiveName, '') AS ExecutiveName
        //     FROM Society_Master sm
        //     INNER JOIN Executive_Master em on sm.QC_Assign_To = em.Executive_Cd
        //     WHERE sm.QC_Done_Flag = 3 ";
        
        $ExecutiveData = $db1->ExecutveQueryMultipleRowSALData($ULB, $ExecutiveListQuery, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Survey Executive Name</label>
        <div class="controls">
            <select class="select2 form-control" name="executiveName">
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($ExecutiveData)>0) 
                {
                  
                    foreach ($ExecutiveData as $key => $value) 
                      {
                          if( $ExecutiveCd == $value["Executive_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Executive_Cd']; ?>"><?php echo $value["ExecutiveName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Executive_Cd"];?>"><?php echo $value["ExecutiveName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->