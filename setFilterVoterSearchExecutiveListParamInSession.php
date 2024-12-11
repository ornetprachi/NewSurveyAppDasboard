<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if(
        isset($_GET['FromDate']) && !empty($_GET['FromDate']) &&
        isset($_GET['ToDate']) && !empty($_GET['ToDate'])
    ){
            try
            {
                $UserName = $_GET['UserName'];
                $FromDate = $_GET['FromDate'];
                $ToDate = $_GET['ToDate'];
                $SearchedNotVal = $_GET['SearchedNotVal'];

                $_SESSION['VS_SurveyUA_UserName'] = $UserName;
                $_SESSION['VS_SurveyUA_SearchNotSearchVal'] = $SearchedNotVal;
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