<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB = $_SESSION['SurveyUtility_ULB'];

        $ExecutiveCd = "";

        if(isset($_SESSION['SurveyUA_Executive_Society_Assign'])){
           $ExecutiveCd = $_SESSION['SurveyUA_Executive_Society_Assign']; 
        }

        $ExecutiveListQuery = "SELECT ISNULL(um.Executive_Cd, 0) AS Executive_Cd, ISNULL(um.ExecutiveName, '') AS ExecutiveName, ISNULL(User_Id, 0) AS User_Id, 
        ISNULL(Mobile, '') AS Mobile, ISNULL(UserType , '') AS UserType, DeactiveFlag 
        FROM Survey_Entry_Data..User_Master as um 
        LEFT JOIN Survey_Entry_Data..Executive_Master as em on (um.Executive_Cd = em.Executive_Cd)
        WHERE AppName = 'SurveyUtilityApp' 
        --AND em.Designation = 'SE-Belapur'
        AND CONVERT(varchar, um.ExpDate, 32) >= CONVERT(varchar, FORMAT(SYSUTCDATETIME() AT TIME ZONE 'UTC' AT TIME ZONE 'India Standard Time', 'yyyy-MM-dd HH:mm:ss.fff'), 32)
        AND (DeactiveFlag IS NULL OR DeactiveFlag = '') AND Upload_SyncFlag = 1 AND Download_SyncFlag = 1 
        ORDER BY ExecutiveName
                ";
        
        $ExecutiveData = $db1->ExecutveQueryMultipleRowSALData($ULB, $ExecutiveListQuery, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Executive Name</label>
        <div class="controls">
            <select class="select2 form-control" name="executiveName">
                <?php 
                    // if( isset($_GET['p']) &&
                    //     ( $_GET['p'] == 'survey-utility-society-assign' )
                    // ){  
                ?>
                    <!-- <option <?php //echo $ExecutiveCd == 'All' ? 'selected=true' : '';
                            // if($ExecutiveCd == 'All'){
                            //     $_SESSION['SurveyUA_Executive_Society_Assign'] = $ExecutiveCd;     
                            // }
                ?> value="All">All</option> -->
                <?php 
                   // }else{
                ?>
                    <option value="">--Select--</option>
                <?php
                    //} 
                ?>

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