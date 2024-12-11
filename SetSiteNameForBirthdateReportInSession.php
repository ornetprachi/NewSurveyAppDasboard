<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SiteName']) && !empty($_GET['SiteName']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_Site_For_BirtyhdayReport'] = $_GET['SiteName'];
            $SiteName =  $_SESSION['SurveyUA_Site_For_BirtyhdayReport'];
            
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