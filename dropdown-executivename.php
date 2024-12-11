<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        
        $executive_Cd = "All";
        if(isset($_SESSION['SurveyUA_Executive_Cd'])){
            $executive_Cd = $_SESSION['SurveyUA_Executive_Cd'];
        }
        
        $query = "SELECT
        ISNULL(um.Executive_Cd,0) as Executive_Cd,
        ISNULL(em.ExecutiveName,'') as ExecutiveName,
        ISNULL(um.UserName,'') as UserName,
        ISNULL(em.MobileNo,'') as MobileNo
        FROM Survey_Entry_Data..User_Master um
        INNER JOIN Survey_Entry_Data..Executive_Master em on em.Executive_Cd = um.Executive_Cd
        INNER JOIN TreeCensus tc on tc.AddedBy = um.UserName
        WHERE um.AppName = '$appName'
        AND CONVERT(VARCHAR,tc.AddedDate,120) BETWEEN '$fromDate' AND '$toDate' 
        GROUP BY ISNULL(um.Executive_Cd,0), ISNULL(em.ExecutiveName,''),
        ISNULL(um.UserName,''), ISNULL(em.MobileNo,'')
        ";
        // echo $query;
        $dataExecutiveName = $db->getSurveyUtilityExecutiveData($query, $userName, $appName, $developmentMode);
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Executive Name</label>
        <div class="controls">
            <select class="select2 form-control" name="executive_Name" onChange="setExecutiveNameInSession(this.value)" >
                <?php 
                    if( isset($_GET['p']) &&
                        (   $_GET['p'] == 'tree-census-list'
                            ||  $_GET['p'] == 'tree-census-qc'
                            ||  $_GET['p'] == 'tree-census-grid'
                            ||  $_GET['p'] == 'tree-census-map'
                            ||  $_GET['p'] == 'tree-health-survey-reports'
                        )
                    ){  
                ?>
                    <option <?php echo $executive_Cd == 'All' ? 'selected=true' : '';?> value="All">All</option>
                <?php 
                    }else{
                ?>
                    <option value="">--Select--</option>
                <?php
                    } 
                ?>
                
                 <?php
                if (sizeof($dataExecutiveName)>0) 
                {
                    foreach ($dataExecutiveName as $key => $value) 
                      {
                          if($executive_Cd == $value["Executive_Cd"])
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