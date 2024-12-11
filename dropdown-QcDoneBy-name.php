<?php

        $db1=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        // $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        if(isset($_SESSION['QC_Assign_tbl_QCAssigned'])){
            $QcAssigned = $_SESSION['QC_Assign_tbl_QCAssigned'];
        }
        $QCExecutiveListQuery = "SELECT 
                                DISTINCT(sm.QC_Assign_To) AS Executive_Cd,
                                ISNULL(em.ExecutiveName, '') AS ExecutiveName
                                FROM Society_Master sm
                                INNER JOIN Executive_Master em on sm.QC_Assign_To = em.Executive_Cd";
        
        $QCExecutiveData = $db1->ExecutveQueryMultipleRowSALData($QCExecutiveListQuery, $userName, $appName, $developmentMode);

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>QC Assigned To</label>
        <div class="controls">
        <select class="select2 form-control" name="QCAssigned" <?php echo $DesignationCond;if(($Designation == 'Data Entry Executive' || $Designation == 'DE') && $_SESSION['SurveyUA_Mobile'] != "9967972847" && $_SESSION['SurveyUA_Mobile'] != "8828259020" && $_SESSION['SurveyUA_Mobile'] != "9356338373" && $_SESSION['SurveyUA_Mobile'] != "8286894002" && $_SESSION['SurveyUA_Mobile'] != "7039797103" ){ ?>Disabled <?php } ?>  >
            <!-- <select class="select2 form-control" name="QCAssignedTo" <?php //echo $DesignationCond ?> onchange="setAssignedToSurveyQCInSession(this.value)"> -->
                <option value="ALL">ALL</option>
                 <?php
                if (sizeof($QCExecutiveData)>0) 
                {
                  
                    foreach ($QCExecutiveData as $key => $value) 
                      {
                          if( $QcAssigned == $value["Executive_Cd"])
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