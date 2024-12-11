<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if( 
                isset($_GET['MarkerType']) && !empty($_GET['MarkerType']) 
          ){
             
            try  
            {  
             
                $MarkerType = $_GET['MarkerType'];

                $_SESSION['Survey_Utility_MarkerType'] = $MarkerType;
                $Div = "Map";

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

