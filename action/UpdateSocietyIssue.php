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
        (isset($_POST["Ac_No"]) && !empty($_POST["Ac_No"])) &&
        (isset($_POST["Issue"]) && !empty($_POST["Issue"])) &&
        (isset($_POST["SocietyName"]) && !empty($_POST["SocietyName"]))  
    )
    {
        $Ac_No = $_POST["Ac_No"];
        $Ward_No = $_POST["Ward_No"];
        $Corporator = $_POST["Corporator"];
        $SocietyName = trim($_POST["SocietyName"]);
        $Rooms = $_POST["Rooms"];
        $Pocket = $_POST["Pocket"];
        $Chairman_Name = $_POST["Chairman_Name"];
        $Chairman_No = $_POST["Chairman_No"];
        $Secretory_Name = $_POST["Secretory_Name"];
        $Secretory_No = $_POST["Secretory_No"];
        $Issue = $_POST["Issue"];
        $Issue_Solve = $_POST["IssueSolve"];
        $SocietyCd = $_POST["SocietyCd"];
        $SctJsonCds = $_POST["SctJsonCds"];
        $action = $_POST["action"];
// print_r($Issue_Solve);
        if(!empty($SocietyCd)){
                $PassedArrMain = explode(',',$SocietyCd);
                $PassedArr = explode('~',$PassedArrMain[0]);
                $Image = $PassedArr[0];
                $Long = $PassedArr[1];
                $Lat = $PassedArr[2];
        }else{
            $Image = '';
            $Long = '';
            $Lat = '';
        }
        
        if($action == 'Insert'){
        $sql3 = "INSERT INTO Survey_Entry_Data..Society_Issues(Ac_No, Ward, Corporator_Name, Society, Rooms, Pocket_Name, Chairman_Name, Chairman_MobNo, Secretory_Name, Secretory_MobNo, Issues, Issue_Solve, Bulding_Img,Longitude,Lattitude,SocietyDetail)
        Values($Ac_No, $Ward_No, N'$Corporator', N'$SocietyName', $Rooms, N'$Pocket', N'$Chairman_Name', N'$Chairman_No', N'$Secretory_Name', N'$Secretory_No', N'$Issue', N'$Issue_Solve', N'$Image',N'$Long',N'$Lat',N'$SctJsonCds')";
        // print_r($sql3);
        // die();
        $UpdateRoomNo = $db->RunQueryData($ULB,$sql3, $userName, $appName, $developmentMode);
        }else if($action == 'Update'){  

            $sql3 = "UPDATE Survey_Entry_Data..Society_Issues 
                    SET
                    Ac_No = $Ac_No,
                    Ward  = $Ward_No,
                    Corporator_Name =  N'$Corporator',
                    Rooms = $Rooms,
                    Pocket_Name = N'$Pocket',
                    Chairman_Name = N'$Chairman_Name',
                    Chairman_MobNo = N'$Chairman_No',
                    Secretory_Name = N'$Secretory_Name',
                    Secretory_MobNo = N'$Secretory_No',
                    Issues = N'$Issue',
                    Issue_Solve = N'$Issue_Solve'
                    WHERE Society = '$SocietyName'";
                    // print_r($sql3);
                    // die();
            $UpdateRoomNo = $db->RunQueryData($ULB,$sql3, $userName, $appName, $developmentMode);
            // print_r($UpdateRoomNo);
            // die();
        }else if($action == 'Remove'){
            $sqlD = "UPDATE Survey_Entry_Data..Society_Issues 
                    SET
                    isActive = 0
                    WHERE Society = '$SocietyName'";
                    // print_r($sql3);
                    // die();
            $UpdateRoomNo = $db->RunQueryData($ULB,$sqlD, $userName, $appName, $developmentMode);
        }
// print_r($UpdateRoomNo);
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