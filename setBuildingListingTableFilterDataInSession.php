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



            // $_SESSION['Building_Listing_tbl_SiteCd'] = $_GET['SiteCd'];
            $SiteString = $_GET['SiteCd'];
            $SiteString = explode("~",$SiteString);
              $Site_Cd = $SiteString[0];
              $SiteName = $SiteString[1];
              $election_Name = $SiteString[2];
              $election_Cd = $SiteString[3];

              
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;

            $_SESSION['SurveyUA_SiteCd_Building_Listing'] = $Site_Cd;
            $_SESSION['SurveyUA_SiteName_Building_Listing'] = $SiteName;

            $_SESSION['Building_Listing_tbl_pocketCd'] = $_GET['pocketCd'];
            $_SESSION['Building_Listing_tbl_fromDate'] = $_GET['fromDate'];
            $_SESSION['Building_Listing_tbl_toDate'] = $_GET['toDate'];
            $_SESSION['Building_Listing_tbl_executiveCd'] = $_GET['executiveCd'];
            $_SESSION['Building_Listing_tbl_QCStatus'] = $_GET['QCStatus']; 

            include 'setBuildingListingTblData.php';

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

