<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['AssignedTo']) && !empty($_GET['AssignedTo']) ){

    try  
        {  

            $AssignedTo = $_GET['AssignedTo'];
            $_SESSION['SurveyUA_AssignedTo_SurveyQC'] = $AssignedTo;

            include 'setSurveyQCData.php';
            
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

