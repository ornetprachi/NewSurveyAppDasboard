<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if( 
                isset($_GET['fromdate']) && !empty($_GET['fromdate'])  &&
                isset($_GET['todate']) && !empty($_GET['todate'])
          ){
                // isset($_GET['siteName']) && !empty($_GET['siteName']) && 

            try  
            {  
             
                // $Site_Cd_Get = $_GET['siteName'];
                $fromdate = $_GET['fromdate'];
                $todate = $_GET['todate'];

                $_SESSION['FromDate_Society_Summary_Dashboard'] = $fromdate;
                $_SESSION['ToDate_Society_Summary_Dashboard'] = $todate;
                // $_SESSION['SurveyUA_SiteCd_For_Dashboard'] = $Site_Cd_Get;

                include 'datatbl/tblGetAssignedSocietyDatabySite.php';
                
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

