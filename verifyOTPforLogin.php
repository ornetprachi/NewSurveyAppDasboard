<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include 'api/includes/DbOperation.php';

    $db=new DbOperation();
 
    if(
        (isset($_POST['umobile']) && !empty($_POST['umobile'])) &&
        (isset($_POST['OTP_Pass']) && !empty($_POST['OTP_Pass']))
    ){

        $umobile = $_POST['umobile'];
        $OTP_Pass = $_POST['OTP_Pass'];

        $data = array();
        $empty= array();
        
        $appName = "SurveyUtilityApp";
        $developmentMode = "Testing";
        $startTime = "00:00:00";
        $endTime = "23:59:59";
        $db = new DbOperation();
        $verifyOTP = $db->verifyOTPandLogin($umobile, $OTP_Pass, $appName);
        
        if(sizeof($verifyOTP['userData'])>0){
            foreach ($verifyOTP['userData'] as $key => $value){

                $userName = $value["UserName"];
                $appName = $value["AppName"];
                $fullName = $value["FullName"];
                $designation = $value["Designation"];
                $userType = $value["UserType"];
                $clientCd = $value["Client_Cd"];
                $electionCd = $value["Election_Cd"];
                $electionName = $value["ElectionName"];
                $ExecutiveName = $value["ExecutiveName"];
                $Executive_Cd = $value["Executive_Cd"];

                $_SESSION['SurveyUA_Mobile']=$umobile;
                    
                $_SESSION['SurveyUA_UserName']=$userName;
                $_SESSION['SurveyUA_LoggedIn_UserName']=$userName;
                $_SESSION['SurveyUA_AppName']=$appName;
                $_SESSION['SurveyUA_FullName']=$fullName;
                $_SESSION['SurveyUA_Designation']=$designation;
                $_SESSION['SurveyUA_UserType']=$userType;
                $_SESSION['SurveyUA_Client_Cd']=$clientCd;
                $_SESSION['SurveyUA_ElectionName']=$electionName;
                $_SESSION['SurveyUA_Election_Cd']=$electionCd;
                $_SESSION['SurveyUA_DevelopmentMode']=$developmentMode;
                $_SESSION['SurveyUA_ExecutiveName']=$ExecutiveName;
                
                $_SESSION['StartTime']=$startTime;
                $_SESSION['EndTime']=$endTime;
                $_SESSION['Filter_Column'] = "All";
                $_SESSION['SurveyUA_Assign_Type'] = "GroupMobile";
                $_SESSION['Action_Type'] = "Assign";
                $_SESSION['SurveyUA_Executive_Cd_Login']=$Executive_Cd;

            }

            echo json_encode(array('statusCode' => 200, 'msg' => 'Logged In Succesfully!!'));
        }else{
            echo json_encode(array('statusCode' => 404, 'msg' => 'OTP does not matched!'));
        }
    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
    }
}
?>
