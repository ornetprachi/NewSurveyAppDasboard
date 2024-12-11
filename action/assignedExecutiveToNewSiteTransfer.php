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
    $_SESSION['assign-executive-to-site'] = "AssignedReportTab";

    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    $PassedArr = array();
    $ExecutiveCdsArr = array();
    $getAllPreviousData = array();
    $flag = "";
    $runQuery = false;
        if(
            (isset($_POST['Date']) && !empty($_POST['Date'])) && 
            (isset($_POST['SelectedExecutiveCds']) && !empty($_POST['SelectedExecutiveCds'])) &&
            (isset($_POST['SiteName']) && !empty($_POST['SiteName']))
        ) {
 
            $Date = $_POST['Date'];
            $SelectedExecutiveCds = $_POST['SelectedExecutiveCds'];
            $OldSite = $_POST['OldSite'];
            $Supervisor = $_POST['Supervisor'];
            $SiteName = $_POST['SiteName'];
            $SiteNameArr = explode('~',$SiteName);
            $SiteCd = $SiteNameArr[0];
            $NewSiteName = $SiteNameArr[1];

            $SelectedExecutiveCds = substr($SelectedExecutiveCds,0,-1);
            $SelectedExecutiveCds = explode(',',$SelectedExecutiveCds);

            $getElectionNameQuery = "SELECT ElectionName FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Site_Master] WHERE Site_Cd = $SiteCd AND SiteName = '$NewSiteName'";
            $ElectionNameData = $db->ExecutveQuerySingleRowSALData($ULB,$getElectionNameQuery, $userName, $appName, $developmentMode);
            $ElectionName = $ElectionNameData['ElectionName'];

            foreach($SelectedExecutiveCds AS $SelectedExecutiveCdsLoop){
                $Executive_Cd = $SelectedExecutiveCdsLoop;
            
                $insertinto="UPDATE [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] 
                            SET 
                                Site_CD = '$SiteCd',
                                SiteName = '$NewSiteName',
                                UpdateByUser = '$updatedByUser',
                                Attendance = 0,
                                UpdatedDate = GETDATE(),
                                ElectionName = '$ElectionName'
                            WHERE Executive_Cd = $Executive_Cd AND SiteName = '$OldSite' AND SurveyDate = '$Date';";
                $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                $insertinto="UPDATE [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master]
                            SET 
                                Attendance = 0,
                                ElectionName = '$ElectionName', 
                                Site_CD = '$SiteCd',
                                SiteName = '$NewSiteName' 
                            WHERE Executive_Cd = $Executive_Cd;";
                $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);
                
                if($runQuery){
                    $flag = "U";
                }else{
                    $flag = "F";
                }
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
                echo json_encode(array('statusCode' => 200, 'msg' => "Assigned Data Transfered Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
