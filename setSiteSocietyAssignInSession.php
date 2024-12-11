<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['siteName']) && !empty($_GET['siteName']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_SiteCd_Society_Assign'] = $_GET['siteName'];
            unset($_SESSION['SurveyUA_Pocket_Cd_Society_Assign']);
            
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

