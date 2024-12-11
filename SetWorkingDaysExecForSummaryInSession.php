<?php
    session_start();
// include 'api/includes/Constants.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['WorkingDaysExec']))
  {

    try  
        {  

            $WorkingDays = $_GET['WorkingDaysExec'];
            $_SESSION['SurveyUA__WorkingDaysExec_For_SummaryReport'] = $WorkingDays;

            $ToWorkingDaysExec = $_GET['ToWorkingDaysExec'];
            $_SESSION['SurveyUA__ToWorkingDaysExec_For_SummaryReport'] = $ToWorkingDaysExec;

            $_SESSION['SurveyUA_Div'] = 'profile';
            $Div = $_SESSION['SurveyUA_Div'];
           $_SESSION['SurveyUA_Div_Inner'] = "";
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

