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
        // $SiteName = $_GET['SiteName'];
        try{  
                $fromDate = $_GET['fromDate'];
                $toDate = $_GET['toDate'];
                        
                $_SESSION['SurveyUA_BirthdayReport_fromDate'] = $fromDate;
                $_SESSION['SurveyUA_BirthdayReport_toDate'] = $toDate;

                include 'SiteWiseBirthdateReportTable.php';   
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

