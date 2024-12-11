<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Election']) && !empty($_GET['Election']) ){

    try  
        {  
          unset($_SESSION['SurveyUA_SiteName_For_Summary']);
          unset($_SESSION['SurveyUA_ExecutiveName_For_Summary']);
            $election_Name = $_GET["Election"];
            
            $_SESSION['SurveyUA_ElectionName_For_Summary'] = $election_Name;
            $electionName = $_SESSION['SurveyUA_ElectionName_For_Summary'];
            
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

