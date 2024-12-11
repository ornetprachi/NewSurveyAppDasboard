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
            
            $election_Cd = $_GET['electionName'];
            $dataElectionForAverageCount = $db->getSurveyUtilityCorporationElectionByCdData($ULB,$userName, $appName, $election_Cd,  $developmentMode);
            
            $election_Name = $dataElectionForAverageCount["ElectionName"];
            
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;

            include 'setAssignedExecutiveSiteTransfer.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }

  }else if(isset($_GET['Date']) && !empty($_GET['Date']) ){

    try  
        {  
            $Date = $_GET['Date'];
            $_SESSION['SurveyUA_Date_AssignExecutiveToSite'] = $Date;

            include 'setAssignedExecutiveSiteTransfer.php';
            
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

