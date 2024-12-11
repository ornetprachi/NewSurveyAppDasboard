<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['assembly']) && !empty($_GET['assembly']) ){

  try  
    {  
      $db=new DbOperation();
      $userName=$_SESSION['SurveyUA_UserName'];
      $appName=$_SESSION['SurveyUA_AppName'];
      $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
      $ULB=$_SESSION['SurveyUtility_ULB'];
      
      $ac_no=$_GET['assembly'];
      $_SESSION['SurveyUA_AcNo_Cd'] = $ac_no;

        
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

