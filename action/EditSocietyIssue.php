<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];
    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    $ULB=$_SESSION['SurveyUtility_ULB'];


    if(
        (isset($_POST["SocietyName"]) && !empty($_POST["SocietyName"])) &&
        (isset($_POST["SocietyCd"]) && !empty($_POST["SocietyCd"]))
    )
    {


        $SocietyCd = $_POST['SocietyCd'];
        $SocietyName = $_POST['SocietyName'];
        $Rooms = $_POST['Rooms'];
        $Chairman_Name = $_POST['Chairman_Name'];
        $Chairman_No = $_POST['Chairman_No'];
        $Secretory_Name = $_POST['Secretory_Name'];
        $Secretory_No = $_POST['Secretory_No'];
        $Issue = $_POST['Issue'];
        $EleNme = $_POST['EleNme'];
        $SiteName = $_POST['SiteName'];
        $IssueSolve = $_POST['Solve'];
        $IssueSolvedRemark = $_POST['IssueSolvedRemark'];
        $action = $_POST['action'];



       if($action == 'Update'){

            $sql3 = "UPDATE Survey_Entry_Data..Society_Master
                    SET
                    Remark = N'$Issue',
                    Rooms  = $Rooms,
                    ChairmanName = N'$Chairman_Name',
                    ChairmanMobileNo = N'$Chairman_No',
                    SecretaryName = N'$Secretory_Name',
                    SecretaryMobileNo = N'$Secretory_No',
                    IssueSolvedRemark = N'$IssueSolvedRemark',
                    IssueSolved = $IssueSolve
                    WHERE Society_Cd = '$SocietyCd'";
                    // print_r($sql3);
                    // die();
            $UpdateRoomNo = $db->RunQueryData($ULB,$sql3, $userName, $appName, $developmentMode);
            // print_r($UpdateRoomNo);
            // die();
        }
// print_r($UpdateRoomNo);
        if($UpdateRoomNo){
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!",'Election' =>  $EleNme,'SiteName' => $SiteName));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured! please try again later','Election' =>  $EleNme,'SiteName' => $SiteName));
        }

    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured! Required parameters are missing please try again later','Election' =>  $EleNme,'SiteName' => $SiteName));
    }
}

?>
