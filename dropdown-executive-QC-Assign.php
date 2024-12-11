<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        // $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        

        // if(isset($_SESSION['SurveyUA_Executive_Society_Assign'])){
        //    $ExecutiveCd = $_SESSION['SurveyUA_Executive_Society_Assign']; 
        // }

        $ExecutiveListQuery = "SELECT * FROM
                                (
                                    SELECT
                                    DISTINCT(em.Executive_Cd) AS Executive_Cd,
                                    ISNULL(em.ExecutiveName, '') AS ExecutiveName,
                                    ISNULL(em.MobileNo, '') AS Mobile,
                                    ISNULL(em.Designation, '') AS Designation
                                    FROM Executive_Master em
                                    LEFT JOIN User_Master um ON (em.Executive_Cd = um.Executive_Cd)
                                    WHERE um.AppName = '$appName'
                                    AND um.Upload_SyncFlag = 1
                                    AND um.Download_SyncFlag = 1 
                                    AND (um.DeactiveFlag IS NULL OR um.DeactiveFlag = '')
                                    AND em.Designation in ('DE','Data Entry Executive','QC')
                                )  AS tb
                                ORDER BY tb.ExecutiveName";
        
        $ExecutiveData = $db1->ExecutveQueryMultipleRowSALData($ExecutiveListQuery, $userName, $appName, $developmentMode);
// print_r($ExecutiveData);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Assign Executive For QC<span area-hidden="true" style="color:red;">*</span></label>
         <!-- <label>Executive Name</label> -->
        <div class="controls">
            <select class="select2 form-control" name="executiveName">
                <option value="">--SELECT--</option>
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