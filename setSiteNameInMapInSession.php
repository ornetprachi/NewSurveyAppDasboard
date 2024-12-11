<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if((isset($_GET['SiteName']) && !empty($_GET['SiteName']) ) &&
  (isset($_GET['ElectionName']) && !empty($_GET['ElectionName']) )
  ){

    try  
        {  
          // unset($_SESSION['SurveyUA_SiteCd_For_ClientDashboard']);
            $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'] = $_GET['SiteName'];
            $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] = $_GET['ElectionName'];
            
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