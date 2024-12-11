<?php

$electionName = "";
$SiteName = "";
$FilterType = "";
$Date = "";
$Supervisor = "";
$ExecutiveCds = "";
$SingleDataCOUNT = 0;
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

    $_SESSION['assign-executive-to-site'] = "AttendanceTab";
    
    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    $SiteNameArr = array();
    $ExecutiveCdsArr = array();
    $flag = "";
    $runQuery = false;
        if(
            (isset($_POST['FilterType']) && !empty($_POST['FilterType'])) &&
            (isset($_POST['ExecutiveCds']) && !empty($_POST['ExecutiveCds'])) 
        ) {
 
            //$Election_Cd  = $_POST['Election_Cd'];
            //(isset($_POST['Election_Cd']) && !empty($_POST['Election_Cd'])) && 
            $Attendance  = $_POST['FilterType'];
            $date  = $_POST['Date'];
        

            $ExecutiveCds  = $_POST['ExecutiveCds'];
            $lastCharacter = substr($ExecutiveCds, -1);

            if($lastCharacter == ","){
                $ExecutiveCds = substr($ExecutiveCds,0,-1);
            }

            $ExecutiveCdsArr = explode(',',$ExecutiveCds);
            // print_r($ExecutiveCdsArr);

            foreach ($ExecutiveCdsArr as $ExecutiveCd) {
                if($Attendance == "1"){
                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Details 
                     SET 
                     Attendance = 1,
                     UpdatedDate = GETDATE()
                     where Executive_Cd=$ExecutiveCd 
                    and convert(varchar, SurveyDate, 23)='$date';";
                    //echo $insertinto;
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);

                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Master SET Attendance = 1 
                    where Executive_Cd=$ExecutiveCd;";

                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                }else if($Attendance == "2"){
                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Details SET Attendance = 2,InTime=NULL,latitude = NULL,longitude = NULL,
                    UpdatedDate=GETDATE() 
                    where Executive_Cd=$ExecutiveCd and convert(varchar, SurveyDate, 23)='$date';";
                    //echo $insertinto;
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);

                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Master SET Attendance = 2 
                    where Executive_Cd=$ExecutiveCd;";
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                }else if($Attendance == "3"){

                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Details SET Attendance = 3,InTime=NULL,latitude = NULL,longitude = NULL,UpdatedDate=GETDATE()  
                    where Executive_Cd=$ExecutiveCd and convert(varchar, SurveyDate, 23)='$date';";
                    //echo $insertinto;
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Master SET Attendance = 3 
                    where Executive_Cd=$ExecutiveCd;";
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                }
                else if($Attendance == "4"){

                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Details SET Attendance = 4,InTime=NULL,latitude = NULL,longitude = NULL,UpdatedDate=GETDATE()  
                    where Executive_Cd=$ExecutiveCd and convert(varchar, SurveyDate, 23)='$date';";
                    //echo $insertinto;
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                    $insertinto="UPDATE [".$ServerIP."].Survey_Entry_Data.dbo.Executive_Master SET Attendance = 4 
                    where Executive_Cd=$ExecutiveCd;";
                    $updateData = $db->RunQueryData($ULB,$insertinto, $userName, $appName, $developmentMode);
                }
            } 
            if($updateData == 1)
            {
                $flag = "U";
            }      
        }else{
            $flag = "M";
        }
    
        if(!empty($flag)) {
            if($flag == "M"){
                echo json_encode(array('statusCode' => 204, 'msg' => "Required parameters are missing!"));
            }else if($flag == "F"){
                echo json_encode(array('statusCode' => 204, 'msg' => "Failed to Assign!"));
            }else if($flag == "U"){
                echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
