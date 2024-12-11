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
              $ClientName = $SiteString[4];

            $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'] = $Site_Cd;
            $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'] = $SiteName;
            
            $_SESSION['SurveyUA_ClientName_SurveyQC_DateWise'] = $ClientName;
       
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;

            include 'setDataSurveyQCDateWise.php';
            
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

