<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['siteName']) && !empty($_GET['siteName']) ){

    try  
        {  
            // $SiteString = $_GET['siteName'];

            $_SESSION['SurveyUA_SiteString_Building_Listing'] = $_GET['siteName'];

            include 'BuildingSurvey.php';
            
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

