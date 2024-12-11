<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
    if
    (
        (isset($_GET['fromDate']) && !empty($_GET['fromDate'])) &&
        (isset($_GET['toDate']) && !empty($_GET['toDate'])) 
    )
    {

        try{  
                $fromDate = $_GET['fromDate'];
                $toDate = $_GET['toDate'];
                        
                $_SESSION['SurveyUA_BLReport_fromDate'] = $fromDate;
                $_SESSION['SurveyUA_BLReport_toDate'] = $toDate;

                include 'setBListSummaryReportData.php';   
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

