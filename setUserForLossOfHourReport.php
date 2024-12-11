<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
    if
    (
        (isset($_GET['User']) && !empty($_GET['User']))
    )
    {
        // $SiteName = $_GET['SiteName'];
        try{  
                $User = $_GET['User'];
                        
                $_SESSION['SurveyUA_LossOfHour_User'] = $User;

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

