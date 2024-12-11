<?php
    session_start();
// include 'api/includes/Constants.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if((isset($_GET['fromdate']) && !empty($_GET['fromdate']))&&
    (isset($_GET['todate']) && !empty($_GET['todate'])))
  {

    try  
        {  
            $fromdate = $_GET['fromdate'];
            $_SESSION['SurveyUA__FromDate_For_SummaryReport'] = $fromdate;
            
            $todate = $_GET['todate'];
            $_SESSION['SurveyUA__ToDate_For_SummaryReport'] = $todate;
            
            $Site = $_GET['SiteName'];
            $_SESSION['SurveyUA__SiteName_For_SummaryReport'] = $Site;
            
            $WorkingDays = $_GET['WorkingDays'];
            $_SESSION['SurveyUA__WorkingDays_For_SummaryReport'] = $WorkingDays;
            
            $ToWorkingdays = $_GET['ToWorkingdays'];
            $_SESSION['SurveyUA__ToWorkingdays_For_SummaryReport'] = $ToWorkingdays;

            $_SESSION['SurveyUA_Div'] = 'DateWise';
            $Div = $_SESSION['SurveyUA_Div'];
           
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

