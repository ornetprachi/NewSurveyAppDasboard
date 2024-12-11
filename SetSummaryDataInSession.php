<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if( 
                isset($_GET['fromDate']) && !empty($_GET['fromDate'])  &&
                isset($_GET['toDate']) && !empty($_GET['toDate'])
          ){
             
            try  
            {  
             
                $fromdate = $_GET['fromDate'];
                $todate = $_GET['toDate'];

                $_SESSION['Survey_Utility_FromDate'] = $fromdate;
                $_SESSION['Survey_Utility_ToDate'] = $todate;

                include 'pages/Survey_Summary_Report.php';

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

