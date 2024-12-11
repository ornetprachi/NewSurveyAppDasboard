<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Month']) && !empty($_GET['Month']) ){

    try  
        {   
            $Month = $_GET['Month'];
            $_SESSION['SurveyUA_Salary_Process_Month'] = $Month;

            include 'setSurveySalaryProcess.php';
            
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

            include 'setSurveySalaryProcess.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['Designation']) && !empty($_GET['Designation']) ){

    try  
        {  
            $Designation = $_GET['Designation'];
            $_SESSION['SurveyUA_Salary_Process_Designation'] = $Designation;

            include 'setSurveySalaryProcess.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['PaymentStatus']) && !empty($_GET['PaymentStatus']) ){

    try  
        {  
            $PaymentStatus = $_GET['PaymentStatus'];
            $_SESSION['SurveyUA_Salary_Process_PaymentStatus'] = $PaymentStatus;

            include 'setSurveySalaryProcess.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['Reference']) && !empty($_GET['Reference']) ){

    try  
        {  
            $Reference = $_GET['Reference'];
            $_SESSION['SurveyUA_Salary_Process_Reference'] = $Reference;

            include 'setSurveySalaryProcess.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['ExecutiveCdOrNameOrMobile']) && !empty($_GET['ExecutiveCdOrNameOrMobile']) ){

    try  
        {  
            $ExecutiveCdOrNameOrMobile = $_GET['ExecutiveCdOrNameOrMobile'];
            $_SESSION['SurveyUA_Salary_Process_ExecutiveCdOrNameOrMobile'] = $ExecutiveCdOrNameOrMobile;

            include 'setSurveySalaryProcess.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
  }else if(isset($_GET['Electionname']) && !empty($_GET['Electionname']) ){

    try  
        {  
            $Electionname = $_GET['Electionname'];
            $_SESSION['SurveyUA_Salary_Process_Electionname'] = $Electionname;

            include 'setSurveySalaryProcess.php';
            
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

