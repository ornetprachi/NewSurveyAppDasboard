<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
   
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];
    $ULB=$_SESSION['SurveyUtility_ULB'];

    if(
        (isset($_POST["roomNo"])) &&
        (isset($_POST["DBName"]) && !empty($_POST["DBName"])) &&
        (isset($_POST["Inputfield"]) && !empty($_POST["Inputfield"])) &&
        (isset($_POST["Type"]) && !empty($_POST["Type"])) &&
        (isset($_POST["Cd"]) && !empty($_POST["Cd"])) 
    )
    {
        $originalRoomNo = trim($_POST["originalRoomNo"]);
        $roomNo = trim($_POST["roomNo"]);
        $DBName = trim($_POST["DBName"]);
        $InputField = trim($_POST["Inputfield"]);
        $Type = trim($_POST["Type"]);
        $Cd = trim($_POST["Cd"]);

        if($InputField == 'Floor'){

            if($Type == 'Voter'){
                $TableName = 'Dw_VotersInfo';
                $RoomNo_Cond = " Col4 = '$roomNo'  ";
                $CD_Cond = " Voter_Cd = $Cd ; ";
            }
            elseif($Type == 'NonVoter'){
                $TableName = 'NewVoterRegistration';
                $RoomNo_Cond = " Col4 = '$roomNo'  ";
                $CD_Cond = " Voter_Cd = $Cd ; ";
            }
            elseif($Type == 'LockRoom'){
                $TableName = 'LockRoom';
                $RoomNo_Cond = " FloorNo = '$roomNo'  ";
                $CD_Cond = " LR_Cd = $Cd ; ";
            }
        }else if($InputField == 'Roomm'){

        if($Type == 'Voter'){
            $TableName = 'Dw_VotersInfo';
            $RoomNo_Cond = " RoomNo = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
        elseif($Type == 'NonVoter'){
            $TableName = 'NewVoterRegistration';
            $RoomNo_Cond = " Roomno = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
        elseif($Type == 'LockRoom'){
            $TableName = 'LockRoom';
            $RoomNo_Cond = " RoomNo = '$roomNo'  ";
            $CD_Cond = " LR_Cd = $Cd ; ";
        }
        }else if($InputField == 'HouseStatus'){

        if($Type == 'Voter'){
            $TableName = 'Dw_VotersInfo';
            $RoomNo_Cond = " Hstatus = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
        elseif($Type == 'NonVoter'){
            $TableName = 'NewVoterRegistration';
            $RoomNo_Cond = " Hstatus = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
        }else if($InputField == 'Mobile'){

        if($Type == 'Voter'){
            $TableName = 'Dw_VotersInfo';
            $RoomNo_Cond = " MobileNo = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
        elseif($Type == 'NonVoter'){
            $TableName = 'NewVoterRegistration';
            $RoomNo_Cond = " Mobileno = '$roomNo'  ";
            $CD_Cond = " Voter_Cd = $Cd ; ";
        }
    }


        $sql3 = " UPDATE $DBName..$TableName  SET $RoomNo_Cond WHERE $CD_Cond ";

        $UpdateRoomNo = $db->RunQueryData($ULB,$sql3, $userName, $appName, $developmentMode);
   
        if($UpdateRoomNo){
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!"));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured! please try again later', 'Query' => $sql3));
        }

    }else{
        echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured! Required parameters are missing please try again later'));
    }
}

?>