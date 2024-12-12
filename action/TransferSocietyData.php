<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $updatedByUser = $userName;
    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $_SESSION['assign-executive-to-site'] = "AssignedReportTab";
    $UpdateVoterData = '';
    $UpdateNonVoterData = '';
    $UpdateLockroomData = '';
 
        if(
            (isset($_POST['Site']) && !empty($_POST['Site'])) && 
            (isset($_POST['FromSociety']) && !empty($_POST['FromSociety'])) &&
            (isset($_POST['ToSociety']) && !empty($_POST['ToSociety']))
        ) {
 
            $Site = $_POST['Site'];
            $FromSociety = $_POST['FromSociety'];
            $ToSociety = $_POST['ToSociety'];
            $UpdatedDate = $_POST['UpdatedDate'];
            $UpdatedBy = $_POST['UpdatedBy'];
            $VoterCds = $_POST['VoterCds'];
            $DBName = $_POST['DBName'];
            $SelectedVoterCds = explode(',',$VoterCds);
            
           
            $TSocietyQry = "SELECT Society_Cd,SocietyName,SocietyNameM FROM Society_Master WHERE Society_Cd = '$ToSociety'";
            $TSocietyData = $db->ExecutveQuerySingleRowSALData($ULB,$TSocietyQry, $userName, $appName, $developmentMode);

            $SocietyN = $TSocietyData['SocietyName'];
            $SocietyMar = $TSocietyData['SocietyNameM'];
            $SocietyCd = $TSocietyData['Society_Cd'];
            
            foreach($SelectedVoterCds AS $SelectedVoterCdsLoop){
                $VoterString = explode("~",$SelectedVoterCdsLoop);
                $Voter_Cd = $VoterString[0];
                $DataType = $VoterString[1];
//  print_r($DataType);
                if($DataType == 'Voter')
                {
                     $UpdateVoter = "UPDATE Dw_VotersInfo 
                                    SET
                                    SocietyName =  '$SocietyN',
                                    SocietyNameM = '$SocietyMar',
                                    SubLocation_Cd = $SocietyCd,
                                    Survey_Society_Cd = $ToSociety
                                    WHERE Voter_Cd = $Voter_Cd;";
                                    // print_r($UpdateVoter);
                                    // die();
                    $UpdateVoterData = $db->RunQueryData($ULB,$UpdateVoter, $userName, $appName, $developmentMode);

                }
                if($DataType == 'NonVoter'){

                    $UpdateNonVoter = "UPDATE NewVoterRegistration 
                                    SET
                                    Societyname =  '$SocietyN',
                                    SocietyNameM = '$SocietyMar',
                                    Subloc_cd = $SocietyCd,
                                    Survey_Society_Cd = $ToSociety
                                    WHERE Voter_Cd = $Voter_Cd;";
                    $UpdateNonVoterData = $db->RunQueryData($ULB,$UpdateNonVoter, $userName, $appName, $developmentMode);
                    // print_r($UpdateNonVoter);
                }
                if($DataType == 'LockRoom'){

                    $UpdateLockroom = "UPDATE LockRoom 
                                    SET
                                    SocietyName =  '$SocietyN',
                                    SocietyNameM = '$SocietyMar',
                                    Sublocation_Cd = $SocietyCd
                                    WHERE LR_Cd = $Voter_Cd;";
                    $UpdateLockroomData = $db->RunQueryData($ULB,$UpdateLockroom, $userName, $appName, $developmentMode);
                }

                
                }
                if($UpdateVoterData == true || $UpdateNonVoterData == true || $UpdateLockroomData == true){
                    $flag = "U";
                }else{
                    $flag = "F";
                }
        }else{
            $flag = "M";
        }
    
        if(!empty($flag)) {
            if($flag == "M"){
                echo json_encode(array('statusCode' => 204, 'msg' => "Required parameters are missing!"));
            }else if($flag == "F"){
                echo json_encode(array('statusCode' => 204, 'msg' => "Failed to Data Transfer!"));
            }else if($flag == "U"){
                echo json_encode(array('statusCode' => 200, 'msg' => "Society Data Transfered Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
