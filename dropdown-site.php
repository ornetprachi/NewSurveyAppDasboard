<?php

        $db=new DbOperation();
        $userName=$_SESSION['SurveyUA_UserName'];
        $appName=$_SESSION['SurveyUA_AppName'];
        $electionCd=$_SESSION['SurveyUA_Election_Cd'];
        $electionName=$_SESSION['SurveyUA_ElectionName'];
        $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
        $ULB=$_SESSION['SurveyUtility_ULB'];
        $Site_Cd = "";
        if(isset($_SESSION['SurveyUA_SiteCd'])){
            $Site_Cd = $_SESSION['SurveyUA_SiteCd'];
        }else{
            $Site_Cd = "All";
        }
        if(isset($_SESSION['SurveyUA_AcNo_Cd'])){
            $Ac_No = $_SESSION['SurveyUA_AcNo_Cd'];
      
        
        
                
        $query = "SELECT 
        COALESCE(Site_Cd,0) AS Site_Cd, 
        COALESCE(ClientName,'') AS ClientName,
        COALESCE(SiteName,'') AS SiteName,
        COALESCE(Area, '') AS Area,
        COALESCE(Ward_No,0) AS Ward_No,
        COALESCE(Address,'') AS Address,
        COALESCE(ElectionName,'') AS ElectionName
        FROM Site_Master 
        WHERE Ac_No = $Ac_No";
		
		//WHERE ElectionName = '$electionName' 
        
        $dataSite = $db->ExecutveQueryMultipleRowSALData($ULB, $query, $userName, $appName, $developmentMode);
    }else{
        $dataSite = array();
    }
?>

<!-- <div class="col-sm-12"> -->
    <div class="form-group">
         <label>Site</label>
        <div class="controls">
            <select class="select2 form-control"  name="siteName"

                <?php 
                    if( isset($_GET['p']) &&
                        (   $_GET['p'] == 'survey-utility-pocket-assign'
                        )
                    ){  
                ?>
                    onChange="setSiteInSession(this.value)"
                <?php 
                    } 
                ?>
            
            >
                <?php 
                    if( isset($_GET['p']) &&
                        (  
                              $_GET['p'] == 'survey-utility-pocket-assign'
                        )
                    ){  
                ?>
                    <option <?php echo $Site_Cd == 'All' ? 'selected=true' : '';
                                if($Site_Cd == 'All'){
                                $_SESSION['SurveyUA_SiteCd'] = $Site_Cd;     
                            }
                ?> value="All">All</option>
                <?php 
                    }else{
                ?>
                    <option value="">--Select--</option>
                <?php
                    } 
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