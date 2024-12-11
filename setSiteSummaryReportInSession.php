<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if( 
                isset($_GET['siteName']) && !empty($_GET['siteName']) 
          ){
             
            try  
            {  
             
                $siteName = $_GET['siteName'];

                $_SESSION['Survey_Utility_siteName'] = $siteName;

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

