<?php
    session_start();
    // include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['pageNo']) && !empty($_GET['pageNo']) ){

    try  
        {  
            // $db=new DbOperation();
            $pageNo = $_GET['pageNo'];
            $_SESSION['SurveyUtility_Pagination_PageNo'] = $pageNo;
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

