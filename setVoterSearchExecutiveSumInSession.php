<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if(isset($_GET['ExecutiveVal']) && !empty($_GET['ExecutiveVal'])
        && isset($_GET['FromDate']) && !empty($_GET['FromDate']) 
        && isset($_GET['ToDate']) && !empty($_GET['ToDate'])){
  
            try
            {   
                $ExecutiveVal = $_GET['ExecutiveVal'];
                $FromDate = $_GET['FromDate'];
                $ToDate = $_GET['ToDate'];
                $_SESSION['VS_SurveyUA_ExecutiveName'] = $ExecutiveVal;
                $_SESSION['VS_SurveyUA_FromDate'] = $FromDate;
                $_SESSION['VS_SurveyUA_ToDate'] = $ToDate;
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