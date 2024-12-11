<?php
// echo "here innnnn";
// die();
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        // include 'api/includes/DbOperation.php'; 


        if(
            isset($_GET['FirstName']) &&
            isset($_GET['MiddleName']) && 
            isset($_GET['LastName']) && 
            (isset($_GET['Voter_Cd']) && !empty($_GET['Voter_Cd'])) &&
            (isset($_GET['SubLocation_Cd']) && !empty($_GET['SubLocation_Cd'])) &&
            (isset($_GET['DBName']) && !empty($_GET['DBName'])) &&
            (isset($_GET['RoomNo'])) 
        ){

            try  
            {  
            //  echo 'here';
                $Voter_Cd = $_GET['Voter_Cd'];
                $SubLocation_Cd = $_GET['SubLocation_Cd'];
                $DBName = $_GET['DBName'];
                $RoomNo = $_GET['RoomNo'];

                $FirstName = $_GET['FirstName'];
                $MiddleName = $_GET['MiddleName'];
                $LastName = $_GET['LastName'];


                $_SESSION['SurveyUA_VoterCd_SurveyQC_Edit'] = $Voter_Cd;
                $_SESSION['SurveyUA_SubLocationCd_SurveyQC_Edit'] = $SubLocation_Cd;
                $_SESSION['SurveyUA_DBName_SurveyQC_Edit'] = $DBName;
                $_SESSION['SurveyUA_RoomNo_SurveyQC_Edit'] = $RoomNo;

                $_SESSION['SurveyUA_FirstName_SurveyQC_Edit'] = $FirstName;
                $_SESSION['SurveyUA_MiddleName_SurveyQC_Edit'] = $MiddleName;
                $_SESSION['SurveyUA_LastName_SurveyQC_Edit'] = $LastName;
                


                // include 'pages/Survey-QC-NonVoter-Edit.php';

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

