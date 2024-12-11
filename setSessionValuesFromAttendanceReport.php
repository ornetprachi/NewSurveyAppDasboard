<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Month']) && !empty($_GET['Month']) ){

    try  
        {   
            $Month = $_GET['Month'];
            $_SESSION['SurveyUA_Salary_Process_Month'] = $Month;

            // include 'Attendance.php';
            $_SESSION['SurveyUA_Attedance_Tab'] = "AttedanceReportScreen";
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }

  }else if(isset($_GET['Year']) && !empty($_GET['Year']) ){

    try  
        {  
            $Year = $_GET['Year'];
            $_SESSION['SurveyUA_Salary_Process_Year'] = $Year;

            // include 'Attendance.php';
            $_SESSION['SurveyUA_Attedance_Tab'] = "AttedanceReportScreen";
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['Tab']) && !empty($_GET['Tab']) ){

    try  
        {  
            $Tab = $_GET['Tab'];
            $_SESSION['SurveyUA_AttendanceReportTab'] = $Tab;
            $_SESSION['SurveyUA_Attedance_Tab'] = "AttedanceReportScreen";
            
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

