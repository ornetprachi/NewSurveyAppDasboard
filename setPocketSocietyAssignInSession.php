<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['PocketName']) && !empty($_GET['PocketName']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Pocket_Cd_Society_Assign'] = $_GET['PocketName'];
            
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

