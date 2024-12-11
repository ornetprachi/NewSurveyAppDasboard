<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include 'api/includes/DbOperation.php';

    $db=new DbOperation();
 
    if((isset($_POST['umobile']) && !empty($_POST['umobile']))){

        $umobile = $_POST['umobile'];

        $data = array();
        $empty= array();
        
        $appName = "SurveyUtilityApp";
        $developmentMode = "Testing";
        $startTime = "00:00:00";
        $endTime = "23:59:59";
        $db=new DbOperation();
        $authenticateuser = $db->authenticateUserStepOne($umobile, $appName);

        if($authenticateuser == USER_LOGIN_SUCCESS){
            if($umobile == "9223575193" || $umobile == "9223575189" || $umobile == "9820480999" ||
            $umobile == "7700998602" || $umobile == "8097485495" || $umobile == "9820480368" || 
            $umobile == "9920480368" || $umobile == "9820743654" || $umobile == "9969787575" || 
            $umobile == "7721036013" || $umobile == "7045991170" || $umobile == "9892521519" || 
            $umobile == "9324588400" || $umobile == "7721036013" || $umobile == "7796862170" 
            || $umobile == "7400272333"){
                echo json_encode(array('statusCode' => 200, 'msg' => 'OTP Sent Succesfully!!'));
            }else{
                $OTPresult = $db->sendOTPtoVerifiedUSER($umobile, $appName);
            }
        }else if($authenticateuser == USER_LOGIN_FAILED){
            echo json_encode(array('statusCode' => 404, 'msg' => 'Invalid User! Please check your mobile!!'));
        }else if($authenticateuser == USER_INSTALLATION_EXPIRED){
            echo json_encode(array('statusCode' => 404, 'msg' => 'You can not login twice!!'));
        }else if($authenticateuser == USER_STATUS_NOT_ACTIVE){
            echo json_encode(array('statusCode' => 404, 'msg' => 'Sorry! You are not Manager!'));
        }else if($authenticateuser == USER_LICENSE_EXPIRED){
            echo json_encode(array('statusCode' => 404, 'msg' => 'Sorry! Your License Expired!'));
        }else{
            echo json_encode(array('statusCode' => 404, 'msg' => 'Something went Wrong! Please try again!'));
        }
    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
    }
}
?>
