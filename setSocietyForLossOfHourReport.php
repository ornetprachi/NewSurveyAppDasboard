<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {

    if
    (
        (isset($_GET['SocietyName']) && !empty($_GET['SocietyName']))
    )
    {
        // $SiteName = $_GET['SiteName'];
        try{  
                $SocietyName = $_GET['SocietyName'];
                        
                $_SESSION['SurveyUA_LossOfHour_SocietyName'] = $SocietyName;

        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
        unset($_SESSION['SurveyUA_LossOfHour_User']);

    }else{
        //echo "ddd";
    }

}
?>

