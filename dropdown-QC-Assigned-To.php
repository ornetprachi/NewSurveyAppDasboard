<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        // $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];


        $ExecutiveListQuery = "SELECT 
            DISTINCT(sm.QC_Assign_To) AS Executive_Cd,
            ISNULL(em.ExecutiveName, '') AS ExecutiveName
            FROM Society_Master sm
            INNER JOIN Survey_Entry_Data..Executive_Master em on sm.QC_Assign_To = em.Executive_Cd
            WHERE sm.QC_Done_Flag = 3 ";
        
        $ExecutiveData = $db1->ExecutveQueryMultipleRowSALData($ULB, $ExecutiveListQuery, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>QC Assigned To</label>
        <div class="controls">
        <select class="select2 form-control" name="QCAssignedTo" <?php echo $DesignationCond;if(($Designation == 'Data Entry Executive' || $Designation == 'DE') && $_SESSION['SurveyUA_Mobile'] != "9967972847" && $_SESSION['SurveyUA_Mobile'] != "8828259020" && $_SESSION['SurveyUA_Mobile'] != "9356338373" && $_SESSION['SurveyUA_Mobile'] != "8286894002" && $_SESSION['SurveyUA_Mobile'] != "7738779669" && $_SESSION['SurveyUA_Mobile'] != "7039797103" ){ ?>Disabled <?php } ?>  >
            <!-- <select class="select2 form-control" name="QCAssignedTo" <?php //echo $DesignationCond ?> onchange="setAssignedToSurveyQCInSession(this.value)"> -->
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($ExecutiveData)>0) 
                {
                  
                    foreach ($ExecutiveData as $key => $value) 
                      {
                          if( $QCAssignedTo == $value["Executive_Cd"])
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