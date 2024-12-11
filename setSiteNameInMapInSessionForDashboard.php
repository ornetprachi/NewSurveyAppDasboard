<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SiteName']) && !empty($_GET['SiteName']) ){

    try  
        { 
            $db=new DbOperation();
            $userName=$_SESSION['SurveyUA_UserName'];
            $appName=$_SESSION['SurveyUA_AppName'];
            // $electionCd=$_SESSION['SurveyUA_Election_Cd'];
            // $electionName=$_SESSION['SurveyUA_ElectionName'];
            $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

            $siteCd = $_GET['SiteName'];
            $_SESSION['SurveyUA_SiteName_For_Dashboaard'] = $siteCd;
            $siteCd = $_SESSION['SurveyUA_SiteName_For_Dashboaard'] ;
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

