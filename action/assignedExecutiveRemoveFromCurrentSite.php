<?php
/**changes Added by prachi */
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
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $updatedByUser = $userName;
    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];
    $_SESSION['assign-executive-to-site'] = "AssignedReportTab";
    
    // if($ServerIP == "103.14.99.154"){
    //     $ServerIP =".";
    // }else{
    //     $ServerIP ="103.14.99.154";
    // }
    $PassedArr = array();
    $ExecutiveCdsArr = array();
    $getAllPreviousData = array();
    $flag = "";
    $runQuery = false;
        if(
            (isset($_POST['Date']) && !empty($_POST['Date'])) && 
            (isset($_POST['SelectedExecutiveCds']) && !empty($_POST['SelectedExecutiveCds'])) &&
            (isset($_POST['OldSite']) && !empty($_POST['OldSite']))
        ) {
 
            $Date = $_POST['Date'];
            $SelectedExecutiveCds = $_POST['SelectedExecutiveCds'];
            $SiteName = $_POST['OldSite'];
            $Supervisor = $_POST['Supervisor'];
            $SelectedExecutiveCds = substr($SelectedExecutiveCds,0,-1);

            $removeQuery = "DELETE FROM [Survey_Entry_Data].[dbo].[Executive_Details] 
            WHERE Executive_Cd IN ($SelectedExecutiveCds)
            AND convert(varchar, SurveyDate, 23) = '$Date'
            AND SiteName = '$SiteName';";

            $runQuery = $db->RunQueryData($ULB,$removeQuery, $userName, $appName,  $developmentMode);
            
            if($runQuery){
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
                echo json_encode(array('statusCode' => 204, 'msg' => "Failed to Data Remove!"));
            }else if($flag == "U"){
                echo json_encode(array('statusCode' => 200, 'msg' => "Data Removed Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
