<?php
    session_start();
// include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(
    (isset($_GET['selectYear']) && !empty($_GET['selectYear'])) )
  {

    try  
        {  
                $_SESSION['OR_ADMIN_SELECT_YEAR'] = $_GET['selectYear'];
                $_SESSION['OR_ADMIN_SELECT_MONTH'] = $_GET['selectMonth'];
                $_SESSION['OR_ADMIN_SELECT_DESIGNATON'] = $_GET['selectdesignation'];
                $_SESSION['OR_ADMIN_SELECT_SITE'] = $_GET['selectsite'];

                
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }else{
    //echo "ddd"; 
    $_SESSION['OR_ADMIN_SELECT_MONTH'] = '';
    $_SESSION['OR_ADMIN_SELECT_DESIGNATON'] = '';
    $_SESSION['OR_ADMIN_SELECT_SITE'] = '';
  }

}
?>

