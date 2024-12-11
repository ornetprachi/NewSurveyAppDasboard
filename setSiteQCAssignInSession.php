<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['siteName']) && !empty($_GET['siteName']) ){

    try  
        {  
      
            $SiteString = $_GET['siteName'];
            $SiteString = explode("~",$SiteString);
              $Site_Cd = $SiteString[0];
              $SiteName = $SiteString[1];
              $election_Name = $SiteString[2];
              $election_Cd = $SiteString[3];

            $_SESSION['SurveyUA_SiteCd_QC_Assign'] = $Site_Cd;
            $_SESSION['SurveyUA_SiteName_QC_Assign'] = $SiteName;

            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;

            include 'setQCAssignData.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }else{
    //echo "ddd";
  }

}
?>

