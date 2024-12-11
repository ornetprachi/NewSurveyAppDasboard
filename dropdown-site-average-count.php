<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

      
        $Site_Cd = "";
        if(isset($_SESSION['SurveyUA_SiteCd_Average_Count'])){
            $Site_Cd = $_SESSION['SurveyUA_SiteCd_Average_Count'];
        }
        // else{
        //     $Site_Cd = "All";
        // }
        $electionCd_AverageCount = '';
        $electionName_AverageCount = '';

        if(isset($_SESSION['SurveyUA_Election_Cd']) && 
            !empty($_SESSION['SurveyUA_Election_Cd']) && 
            isset($_SESSION['SurveyUA_ElectionName_Average_Count']) && 
            !empty($_SESSION['SurveyUA_ElectionName'])){

                $electionCd_AverageCount = $_SESSION['SurveyUA_Election_Cd'];
                $electionName_AverageCount = $_SESSION['SurveyUA_ElectionName'];
                $ElectionNameCondition = " WHERE ElectionName = '$electionName_AverageCount'";


                $sitedropdownquery = "SELECT 
                    COALESCE(Site_Cd,0) AS Site_Cd, 
                    COALESCE(ClientName,'') AS ClientName,
                    COALESCE(SiteName,'') AS SiteName,
                    COALESCE(Area, '') AS Area,
                    COALESCE(Ward_No,0) AS Ward_No,
                    COALESCE(Address,'') AS Address,
                    COALESCE(ElectionName,'') AS ElectionName
                    FROM Site_Master $ElectionNameCondition 
                    ";
                    
                $dataSite = $db->ExecutveQueryMultipleRowSALData($sitedropdownquery, $userName, $appName, $developmentMode);

            }else{
                $dataSite = array();
            }
                
    //   print_r($Site_Cd);
    // if(sizeof($dataSite)>0){
    //     print_r($dataSite);
    // }

?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site</label>
        <div class="controls">
            <select class="select2 form-control"  name="SiteName"

                <?php 
                    if( isset($_GET['p']) &&
                        // (  $_GET['p'] == 'survey-utility-average-count' )
                        (  $_GET['p'] == 'survey-utility-average-count' )
                    ){  
                ?>
                    onchange="setSiteAverageCountInSession(this.value)"
                <?php 
                    } 
                ?>
            
            >
                <?php 
                    // if( isset($_GET['p']) &&
                    //     ( $_GET['p'] == 'survey-utility-society-assign' )
                    // ){  
                ?>
                    <!-- <option <?php //echo $Site_Cd == 'All' ? 'selected=true' : '';
                            //     if($Site_Cd == 'All'){
                            //     $_SESSION['SurveyUA_SiteCd_Society_Assign'] = $Site_Cd;     
                            // }
                ?> value="All">All</option> -->
                <?php 
                    //}else{
                ?>
                    <option value="ALL">ALL</option>
                <?php
                   // } 
                ?>
                 <?php
                if (sizeof($dataSite)>0) 
                {
                    foreach ($dataSite as $key => $value) 
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