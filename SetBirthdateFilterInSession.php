<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Date']) && !empty($_GET['Date']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_BdayDate_BirthdateFilter'] = $_GET['Date'];

            $Date = $_SESSION['SurveyUA_BdayDate_BirthdateFilter'];
            
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

