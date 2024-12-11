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
            $dataElectionForSocietyAssign = $db->getSurveyUtilityCorporationElectionByCdData($ULB,$userName, $appName, $election_Cd,  $developmentMode);
            
            $election_Name = $dataElectionForSocietyAssign["ElectionName"];
            
            $_SESSION['SurveyUA_ElectionName'] = $election_Name;
            $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
            $electionCdSocietyAssign = $_SESSION['SurveyUA_Election_Cd'];
            $electionNameSocietyAssign = $_SESSION['SurveyUA_ElectionName'];
            
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

