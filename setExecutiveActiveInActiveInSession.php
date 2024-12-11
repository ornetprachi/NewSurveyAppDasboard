<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Status']) && !empty($_GET['Status']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Status'] = $_GET['Status'];
            $Status = $_SESSION['SurveyUA_Status'];
            
            $_SESSION['SurveyUA_Div'] = 'profile';
            $Div = $_SESSION['SurveyUA_Div'];
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

