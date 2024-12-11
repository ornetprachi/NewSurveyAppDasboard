<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['ULB']) && !empty($_GET['ULB']) && isset($_GET['ServerName']) && !empty($_GET['ServerName'])){
    
    try  
        {  
          
            unset($_SESSION['SurveyUA_Election_Cd']);
            unset($_SESSION['SurveyUA_ElectionName']);
          $ULB = $_GET['ULB'];
          $ServerName = $_GET['ServerName'];
          $_SESSION['SurveyUtility_ULB'] = $ULB;
          $_SESSION['SurveyUtility_ServerIP'] = $ServerName;
          $_SESSION['SurveyUA_Election_Cd'] = $_GET['ElectionCd'];
          $_SESSION['SurveyUA_ElectionName'] = $_GET['Election'];
            
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

