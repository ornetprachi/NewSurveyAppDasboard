<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if(isset($_GET['FirstName'])  && isset($_GET['MiddleName'])  && isset($_GET['LastName'])){
             
            try  
            {  
             
                $FirstName = $_GET['FirstName'];
                $MiddleName = $_GET['MiddleName'];
                $LastName = $_GET['LastName'];
                $FullName = $_GET['FullName'];

                $_SESSION['SurveyUA_FirstName_SurveyQC_Details'] = $FirstName;
                $_SESSION['SurveyUA_MiddleName_SurveyQC_Details'] = $MiddleName;
                $_SESSION['SurveyUA_LastName_SurveyQC_Details'] = $LastName;
                $_SESSION['SurveyUA_FullName_SurveyQC_Details'] = $FullName;

                include 'pages/Survey-QC-NonVoter-Edit.php';

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

