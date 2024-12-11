<?php
    if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
        session_start();
        include 'api/includes/DbOperation.php'; 
        if(
            (isset($_GET['FamilyNo']) && !empty($_GET['FamilyNo'])) &&
            (isset($_GET['Ac_No']) && !empty($_GET['Ac_No'])) &&
            (isset($_GET['Voter_Cd']) && !empty($_GET['Voter_Cd'])) 
        ){
             
            try  
            {  
             
                $FamilyNo = $_GET['FamilyNo'];
                $Ac_No = $_GET['Ac_No'];
                $Voter_Cd = $_GET['Voter_Cd'];

                $FirstName = $_GET['FirstName'];
                $MiddleName = $_GET['MiddleName'];
                $LastName = $_GET['LastName'];
                $FullName = $_GET['FullName'];
                $AdvanceSearch = $_GET['AdvanceSearch'];
                $IdCard_No = $_GET['IdCard_No'];
                // $List_No = $_GET['List_No'];

                $_SESSION['SurveyUA_FamilyNo_SurveyQC_Details'] = $FamilyNo;
                $_SESSION['SurveyUA_AcNo_SurveyQC_Details'] = $Ac_No;
                $_SESSION['SurveyUA_VoterCd_SurveyQC_Details'] = $Voter_Cd;

                $_SESSION['SurveyUA_FirstName_SurveyQC_Details'] = $FirstName;
                $_SESSION['SurveyUA_MiddleName_SurveyQC_Details'] = $MiddleName;
                $_SESSION['SurveyUA_LastName_SurveyQC_Details'] = $LastName;
                $_SESSION['SurveyUA_FullName_SurveyQC_Details'] = $FullName;
                $_SESSION['SurveyUA_AdvanceSearch_SurveyQC_Details'] = $AdvanceSearch;
                $_SESSION['SurveyUA_IdCard_No_SurveyQC_Details'] = $IdCard_No;
                // $_SESSION['SurveyUA_List_No_SurveyQC_Details'] = $List_No;

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

