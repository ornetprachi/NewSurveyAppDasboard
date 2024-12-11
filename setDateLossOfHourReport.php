<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
    if
    (
        (isset($_GET['Date']) && !empty($_GET['Date']))
    )
    {
        // $SiteName = $_GET['SiteName'];
        try{  
                $Date = $_GET['Date'];
                        
                $_SESSION['SurveyUA_LossOfHour_Date'] = $Date;

                include 'LossOfHrRpt.php';   
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

