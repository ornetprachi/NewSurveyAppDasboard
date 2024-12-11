<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SiteName']) && !empty($_GET['SiteName']) ){

    try  
        {  
            unset($_SESSION['SurveyUA_ExecutiveName_For_Summary']);
            unset($_SESSION['SurveyUA_SocietyName_for_Summary']);
            $Site_Name = $_GET["SiteName"];
            
            $_SESSION['SurveyUA_SiteName_For_Summary'] = $Site_Name;
            // $_SESSION['SurveyUA_Election_Cd_For_Summary'] = $election_Cd;
            // $electionCd = $_SESSION['SurveyUA_Election_Cd_For_Summary'];
            $SiteName = $_SESSION['SurveyUA_SiteName_For_Summary'];
            
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

