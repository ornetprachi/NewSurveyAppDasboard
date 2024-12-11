<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if(isset($_GET['SearchNotSearchVal']) && !empty($_GET['SearchNotSearchVal'])){
  
            try
            {   
                $SearchNotSearchVal = $_GET['SearchNotSearchVal'];
                $_SESSION['VS_SurveyUA_SearchNotSearchVal'] = $SearchNotSearchVal;
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