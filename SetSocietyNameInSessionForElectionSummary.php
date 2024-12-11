<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['SocietyName']) && !empty($_GET['SocietyName']) ){

    try  
        {  
            
            $_SESSION['SurveyUA_SocietyName_for_Summary'] = $_GET['SocietyName'];
            $Society_Name = $_SESSION['SurveyUA_SocietyName_for_Summary'] ;
            
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