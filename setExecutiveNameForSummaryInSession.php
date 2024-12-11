<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Executive']) && !empty($_GET['Executive']) ){

    try  
        {  
          unset($_SESSION['SurveyUA_SiteName_For_Summary']);
          unset($_SESSION['SurveyUA_SocietyName_for_Summary']);
            
            $_SESSION['SurveyUA_ExecutiveName_For_Summary'] = $_GET["Executive"];
            $ExecutiveName = $_SESSION['SurveyUA_ExecutiveName_For_Summary'];
            
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

