<?php
session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['servername']) && !empty($_GET['servername']) ){

    try  
    {  

        $servername=$_GET['servername'];

        $_SESSION['To_Servername_MoveDBData'] = $servername;

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

