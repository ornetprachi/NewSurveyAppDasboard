<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

        $dataSiteforDashboard = array();

        if(isset($_SESSION['SurveyUA_ElectionName_For_Dashboard']) && !empty($_SESSION['SurveyUA_ElectionName_For_Dashboard'])){

            $electionName_of_Dashboard = $_SESSION['SurveyUA_ElectionName_For_Dashboard'];


            $sitedropdownquerydashboard = "SELECT 
                                            COALESCE(Site_Cd,0) AS Site_Cd, 
                                            COALESCE(ClientName,'') AS ClientName,
                                            COALESCE(SiteName,'') AS SiteName,
                                            COALESCE(Area, '') AS Area,
                                            COALESCE(Ward_No,0) AS Ward_No,
                                            COALESCE(Address,'') AS Address,
                                            COALESCE(ElectionName,'') AS ElectionName
                                            FROM Site_Master 
                                            WHERE ElectionName = '$electionName_of_Dashboard'
                                            ";
            
          $dataSiteforDashboard = $db->ExecutveQueryMultipleRowSALData($sitedropdownquerydashboard, $userName, $appName, $developmentMode);


        }
        

        $Site_Cd = "";
        if(isset($_SESSION['SurveyUA_SiteCd_For_Dashboard'])){
            $Site_Cd = $_SESSION['SurveyUA_SiteCd_For_Dashboard'];
        }else{
            $_SESSION['SurveyUA_SiteCd_For_Dashboard'] = 1;
            $Site_Cd = $_SESSION['SurveyUA_SiteCd_For_Dashboard'] ;
        }

        

        
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site</label>
        <div class="controls">
            <select class="select2 form-control"  name="siteName" >
            <!-- onChange="setSiteForDashboardInSession(this.value)" -->
            <option value="">--Select--</option>
         
                 <?php
                if (sizeof($dataSiteforDashboard)>0) 
                {
                    foreach ($dataSiteforDashboard  as $key => $value) 
                      {
                          if($Site_Cd == $value["Site_Cd"])
                          {
                ?>
                            <option selected="true" value="<?php echo $value['Site_Cd']; ?>"><?php echo $value["SiteName"]; ?></option>
                <?php
                          }
                          else
                          {
                ?>
                            <option value="<?php echo $value["Site_Cd"];?>"><?php echo $value["SiteName"];?></option>
                <?php
                          }
                      }
                  }
                ?> 
            </select>
        </div>

    </div>
<!-- </div> -->