<?php
    session_start();
include 'api/includes/DbOperation.php'; 

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  
  if(isset($_GET['Date']) && !empty($_GET['Date']) ){

    try  
        {  
            $Date = $_GET['Date'];
            $_SESSION['SurveyUA_Date_Attendance'] = $Date;


            //include 'Attendance.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
                                                          

  }
  else if(isset($_GET['WorkIn']) && !empty($_GET['WorkIn']) ){

    try  
        {  
            $WorkIn = $_GET['WorkIn'];
            $_SESSION['SurveyUA_WorkIn_Attendance'] = $WorkIn;

            //include 'Attendance.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
 
  }
  else if(isset($_GET['Site']) && !empty($_GET['Site']) ){

    try  
        {  
            $Site = $_GET['Site'];
            $_SESSION['SurveyUA_Site_Attendance'] = $Site;

            //include 'Attendance.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
 
  }
  else if(isset($_GET['Filter']) && !empty($_GET['Filter']) ){

    try  
        {  
            $Filter = $_GET['Filter'];
            $_SESSION['SurveyUA_Filter_Attendance'] = $Filter;
            // print_r($Filter);
            // die();

            //include 'Attendance.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
 
  }
  else if(isset($_GET['designation']) && !empty($_GET['designation']) ){

    try  
        {  
            $designation = $_GET['designation'];
            $_SESSION['SurveyUA_designation_Attendanmce'] = $designation;
            // print_r($Filter);
            // die();

            //include 'Attendance.php';
            
        } 
        catch(Exception $e)  
        {  
            echo("Error!");  
        }
 
  }
  else{
    //echo "ddd";
  }

  $_SESSION['assign-executive-to-site'] = "AttendanceTab";

}
?>

