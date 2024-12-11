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
    $ULB=$_SESSION['SurveyUtility_ULB'];
    
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
            (isset($_POST['TableName']) && !empty($_POST['TableName'])) &&
            (isset($_POST['SalaryP_ID']) && !empty($_POST['SalaryP_ID'])) &&
            (isset($_POST['PayableAmtChange']) && !empty($_POST['PayableAmtChange'])) 
        ) {
 
            $TableName  = $_POST['TableName'];
            $SalaryP_ID  = $_POST['SalaryP_ID'];
            $Advance  = $_POST['Advance'];
            $Deduction  = $_POST['Deduction'];
            $Incentives  = $_POST['Incentives'];
            $PayableSalary  = $_POST['PayableAmtChange'];
            $Remark  = $_POST['Remark'];
        
            $UpdateQuery ="UPDATE [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName]
                        SET 
                            AdvanceAmt = '$Advance',
                            IncentivesAmt = '$Incentives',
                            DeductionAmt = '$Deduction',
                            Remark = N'$Remark', 
                            PayableSalary = '$PayableSalary',
                            UpdatedBy = '$Executive_Cd',
                            UpdatedDate = GETDATE()
                        WHERE SalaryP_ID = $SalaryP_ID;";
            $runQuery = $db->RunQueryData($ULB,$UpdateQuery, $userName, $appName,  $developmentMode);

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
                echo json_encode(array('statusCode' => 204, 'msg' => "Failed to Update!"));
            }else if($flag == "U"){
                echo json_encode(array('statusCode' => 200, 'msg' => "Update Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
