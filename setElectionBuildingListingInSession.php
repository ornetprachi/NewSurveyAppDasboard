<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['electionName']) && !empty($_GET['electionName']) ){

    try  
        {  
            $db=new DbOperation();
            $userName=$_SESSION['SurveyUA_UserName'];
            $appName=$_SESSION['SurveyUA_AppName'];
            $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
            $ULB=$_SESSION['SurveyUtility_ULB'];
            
            $election_Cd=$_GET['electionName'];
            $dataElectionForAverageCount = $db->getSurveyUtilityCorporationElectionByCdData($ULB,$userName, $appName, $election_Cd,  $developmentMode);
            
            $election_Name = $dataElectionForAverageCount["ElectionName"];
            
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            
            unset($_SESSION['SurveyUA_SiteCd_Building_Listing']);
            unset($_SESSION['SurveyUA_SiteName_Building_Listing']);

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

