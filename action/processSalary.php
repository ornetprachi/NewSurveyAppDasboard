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
    $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];
    
    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    $Executive_Cd = "";
    $Month = "";
    $Year = "";
    $totalDays = "";

    // $runQuery = false;

        if(
            (isset($_POST['Executive_Cd']) && !empty($_POST['Executive_Cd'])) &&
            (isset($_POST['Month']) && !empty($_POST['Month'])) &&
            (isset($_POST['Year']) && !empty($_POST['Year'])) &&
            (isset($_POST['totalDays']) && !empty($_POST['totalDays']))
        ){
 
            $Executive_Cd = $_POST['Executive_Cd'];
            $Month = $_POST['Month'];
            $Year = $_POST['Year'];
            $totalDays = $_POST['totalDays'];

            if(isset($_POST['process']) && !empty($_POST['process'])){
                $process = $_POST['process'];
            }else{
                $process = "";
            }
            
            $SalaryProcessData = $db->salaryProcess($userName, $appName,  $developmentMode, $Executive_Cd, $Month, $Year, $totalDays, $process);
      
            if($SalaryProcessData['Flag'] == "YES"){
                $flag = "E";
            }else if($SalaryProcessData['Flag'] == "SUCCESS"){
                $flag = "U";
            }else{

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
                echo json_encode(array('statusCode' => 200, 'msg' => "Salary Processed Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 203, 'msg' => "Salary Already Processed of $Month-$Year! \nDo you want to process again? \nNote: All changes will reset"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
} 
?>
