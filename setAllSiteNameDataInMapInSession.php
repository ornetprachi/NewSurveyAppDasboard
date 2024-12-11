<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SiteName']) && !empty($_GET['SiteName']) ){

    try  
        { 
            

            $siteCd = $_GET['SiteName'];
            $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'] = $siteCd;
            $siteCd = $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'] ;
                $ElectionName = "";
                $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] = $ElectionName;
                $ElectionName = $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] ;
           
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

