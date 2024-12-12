<?php
/*Changes Done By prachi*/
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

    $_SESSION['assign-executive-to-site'] = "AssignTab";
    
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
            (isset($_POST['SiteName']) && !empty($_POST['SiteName'])) && 
            (isset($_POST['Date']) && !empty($_POST['Date'])) &&
            (isset($_POST['Supervisor']) && !empty($_POST['Supervisor'])) &&
            (isset($_POST['ExecutiveCds']) && !empty($_POST['ExecutiveCds'])) &&
            (isset($_POST['AttendanceFilter']) && !empty($_POST['AttendanceFilter'])) 
        ) {
 
            //$Election_Cd  = $_POST['Election_Cd'];
            //(isset($_POST['Election_Cd']) && !empty($_POST['Election_Cd'])) && 
            $SiteName  = $_POST['SiteName'];
            
            $SiteNameArr = explode('~',$SiteName);
            $Site_Cd = $SiteNameArr[0];
            $SiteName = $SiteNameArr[1];
            
            $FilterType  = $_POST['FilterType'];
            $Date  = $_POST['Date'];
            $Supervisor  = $_POST['Supervisor'];
            

            // Attendance -------------------------
            $Attendance = $_POST['AttendanceFilter'];
            $AttendanceCondiColumn = "";
            $AttendanceCondiValues = "";
            if($Attendance == "1"){
                $AttendanceCondiColumn = ",Attendance";
                $AttendanceCondiValues = ",$Attendance";
            }else if($Attendance == "2" || $Attendance == "3" || $Attendance == "4"){
                $AttendanceCondiColumn = ",Attendance,InTime,latitude,longitude";
                $AttendanceCondiValues = ",$Attendance,NULL,NULL,NULL";
            }else{
                $AttendanceCondiColumn = "";
                $AttendanceCondiValues = "";
            }
            // Attendance -------------------------

            $SupervisorArr = explode('~',$Supervisor);
            $SupervisorCd = $SupervisorArr[0];
            $SupervisorName = $SupervisorArr[1];

            $ExecutiveCds  = $_POST['ExecutiveCds'];
            if (strpos($ExecutiveCds, ',') !== false) {
                // $ExecutiveCds = substr($ExecutiveCds,0,-1);
                $ExecutiveCdsArr = explode(',',$ExecutiveCds);
            }else{
                $ExecutiveCdsArr = array($ExecutiveCds);
            }

            $getElectionNameQuery = "SELECT ElectionName FROM [Site_Master] WHERE Site_Cd = $Site_Cd AND SiteName = '$SiteName'";
            $ElectionNameData = $db->ExecutveQuerySingleRowSALData($ULB,$getElectionNameQuery, $userName, $appName, $developmentMode);
            $ElectionName = $ElectionNameData['ElectionName'];


            foreach ($ExecutiveCdsArr as $ExecutiveCd) {
                $queryData = "SELECT * FROM [Survey_Entry_Data].[dbo].[Executive_Details] WHERE Executive_Cd = $ExecutiveCd AND convert(varchar, SurveyDate, 23) = '$Date';";
                $SingleData = $db->ExecutveQuerySingleRowSALData($ULB,$queryData, $userName, $appName, $developmentMode);
                $SingleDataCOUNT = sizeof($SingleData);
                if($SingleDataCOUNT > 0){
                    $flag = "E";
                }else{

                    $ExecQuery = "SELECT * FROM [Survey_Entry_Data].[dbo].[Executive_Master] WHERE Executive_Cd = $ExecutiveCd;";
                    $ExecData = $db->ExecutveQuerySingleRowSALData($ULB,$ExecQuery, $userName, $appName, $developmentMode);
                    if(sizeof($ExecData)>0){
                        $Executive_Cd = $ExecData['Executive_Cd'];
                        $ExecutiveName = $ExecData['ExecutiveName'];
                    }

                    $insertinto="INSERT INTO [Survey_Entry_Data].[dbo].[Executive_Details] (Executive_Cd,ExecutiveName,Site_CD,SiteName,SurveyDate,UpdateByUser,UpdatedDate,ElectionName $AttendanceCondiColumn) 
                                                        VALUES ('$Executive_Cd','$ExecutiveName','$Site_Cd','$SiteName','$Date','$updatedByUser',GETDATE(),'$ElectionName' $AttendanceCondiValues);";
                    $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                    $insertinto="UPDATE [Survey_Entry_Data].[dbo].[Executive_Master]
                                SET 
                                    Attendance = $Attendance,
                                    SurveyDate = '$Date',
                                    ElectionName ='$ElectionName', 
                                    Site_CD = '$Site_Cd',
                                    SiteName = '$SiteName' 
                                WHERE Executive_Cd = $Executive_Cd;";
                    $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                    $insertinto="UPDATE [Site_Master]
                                SET
                                    SupervisorName = '$SupervisorName',
                                    Supervisor_Cd = '$SupervisorCd'
                                WHERE Site_Cd = $Site_Cd;";
                    $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);
                    
                    if($runQuery){
                        $flag = "U";
                    }else{
                        $flag = "F";
                    }
                }
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
                echo json_encode(array('statusCode' => 200, 'msg' => "Assigned Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
