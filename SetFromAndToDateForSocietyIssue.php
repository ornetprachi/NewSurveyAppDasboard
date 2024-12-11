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
            $_SESSION['SurveyUA__FromDate_For_SocietyIssue'] = $fromdate;
            
            $todate = $_GET['todate'];
            $_SESSION['SurveyUA__ToDate_For_SocietyIssue'] = $todate;
            

           
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

