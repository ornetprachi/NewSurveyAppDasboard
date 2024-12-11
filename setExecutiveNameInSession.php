<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['executiveCd']) && !empty($_GET['executiveCd']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Executive_Cd'] = $_GET['executiveCd'];
            
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

