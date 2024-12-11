<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if
  (
    (isset($_GET['electionName']) && !empty($_GET['electionName']))
  )
  {
    // echo "here";
    // die();
    try  
        {  
            $db=new DbOperation();
            $userName=$_SESSION['SurveyUA_UserName'];
            $appName=$_SESSION['SurveyUA_AppName'];
            $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
            
            // $election_Cd=$_GET['electionName'];
            // $dataElection = $db->getSurveyUtilityCorporationElectionByCdData($userName, $appName, $election_Cd,  $developmentMode);
            
            // $election_Name = $dataElection["ElectionName"];

            
            
            // $_SESSION['Building_Listing_tbl_SiteCd'] = $_GET['SiteCd'];
            $SiteString = $_GET['SiteCd'];
            $SiteString = explode("~",$SiteString);
            $Site_Cd = $SiteString[0];
            $SiteName = $SiteString[1];
            $election_Name = $SiteString[2];
            $election_Cd = $SiteString[3];
            
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            
            $_SESSION['SurveyUA_SiteCd_SurveyQC'] = $Site_Cd;
            $_SESSION['SurveyUA_SiteName_SurveyQC'] = $SiteName;

            $_SESSION['SurveyQC_tbl_pocketCd'] = $_GET['pocketCd'];
            $_SESSION['SurveyQC_tbl_fromDate'] = $_GET['fromDate'];
            $_SESSION['SurveyQC_tbl_toDate'] = $_GET['toDate'];
            $_SESSION['SurveyQC_tbl_executiveCd'] = $_GET['executiveCd'];
            $_SESSION['SurveyQC_tbl_QCAssignedTo'] = $_GET['QCAssignedTo']; 
            $_SESSION['SurveyQC_tbl_QCStatus'] = $_GET['QCStatus']; 
            $_SESSION['SurveyQC_tbl_SurveyStatus'] = $_GET['SurveyStatus']; 

            include 'setSurveyQCData.php';

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

