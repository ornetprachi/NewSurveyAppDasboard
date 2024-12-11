<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['BdayDate']) && !empty($_GET['BdayDate']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_BdayDate_Birthdate_Report'] = $_GET['BdayDate'];

            $Date = $_SESSION['SurveyUA_BdayDate_Birthdate_Report'];
            
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

