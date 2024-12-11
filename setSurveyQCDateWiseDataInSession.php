<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if
  (
    (isset($_GET['SiteCd']) && !empty($_GET['SiteCd']))
  )
  {
    // echo "here";
    // die();
    try  
        {  
            $SiteString = $_GET['SiteCd'];
            $SiteString = explode("~",$SiteString);
            $Site_Cd = $SiteString[0];
            $SiteName = $SiteString[1];
            $election_Name = $SiteString[2];
            $election_Cd = $SiteString[3];
            $ClientName = $SiteString[4];
            
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            
            $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'] = $Site_Cd;
            $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'] = $SiteName;
            
            $_SESSION['SurveyUA_ClientName_SurveyQC_DateWise'] = $ClientName;

            $_SESSION['SurveyQCDateWise_tbl_fromDate'] = $_GET['fromDate'];
            $_SESSION['SurveyQCDateWise_tbl_toDate'] = $_GET['toDate'];

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

