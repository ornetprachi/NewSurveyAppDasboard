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
            $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'] = $siteCd;
            $siteCd = $_SESSION['SurveyUA_SiteName_For_ClientDashboaard'] ;
            if($siteCd == 'All'){
                $ElectionName = "";
                $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] = $ElectionName;
                $ElectionName = $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] ;
            }else{
                $EleQuery = "SELECT TOP(1) ElectionName,SiteName FROM Site_Master WHERE SiteName = '$siteCd'";

                $ElectionNameData = $db->ExecutveQuerySingleRowSALData($EleQuery , $userName, $appName, $developmentMode);
                // print_r($ElectionNameData);
                 
                $ElectionName = $ElectionNameData['ElectionName'];
                $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] = $ElectionName;
                $ElectionName = $_SESSION['SurveyUA_ElectionName_For_ClientDashboaard'] ;
            }
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

