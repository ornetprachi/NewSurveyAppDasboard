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
            (isset($_POST['PayStatus']) && !empty($_POST['PayStatus'])) &&
            (isset($_POST['SelectedExecutives']) && !empty($_POST['SelectedExecutives'])) &&
            (isset($_POST['TableName']) && !empty($_POST['TableName'])) 
        ) {
 
            $PayStatus  = $_POST['PayStatus'];
            $SalaryP_ID  = $_POST['SelectedExecutives'];
            $Remark  = $_POST['Remark'];
            $TableName  = $_POST['TableName'];
            $TableNameArr = array();
            $TableNameArr = explode('_',$TableName);
            if(sizeof($TableNameArr)>0){
                $Month = $TableNameArr[1];
                $Year = $TableNameArr[2];
            }else{  
                $Month = 0;
                $Year = 0;
            }
            
            $UpdateQuery ="UPDATE [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName]
                        SET 
                            PaymentStatus = '$PayStatus',
                            PayStatusRemark = N'$Remark',
                            UpdatedBy = '$Executive_Cd',
                            UpdatedDate = GETDATE()
                        WHERE SalaryP_ID IN ($SalaryP_ID);";
            $runQuery = $db->RunQueryData($ULB,$UpdateQuery, $userName, $appName,  $developmentMode);

            if($runQuery){
                 // Insert into Master Table -------------------------------------
                $SalaryP_IDArr = array();
                $SalaryP_IDArr = explode(',',$SalaryP_ID);
                foreach($SalaryP_IDArr AS $SalaryP_ID){
                    $SelectQuery = "SELECT SalaryP_ID FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[SalaryProcessed_MasterTable] 
                                    WHERE SalaryP_ID = $SalaryP_ID
                                    AND Month = $Month
                                    AND Year = $Year";
                    $selectedData = $db->ExecutveQuerySingleRowSALData($ULB,$SelectQuery, $userName, $appName, $developmentMode);
                    $selectedDataCount = sizeof($selectedData);
                    if($selectedDataCount != 0){
                        
                        $SelectQuery1 = "SELECT SalaryP_ID,Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,Present,[Absent],HalfDay,Training,CONVERT(varchar,JoiningDate,23) AS JoiningDate,
                                        CONVERT(varchar,FirstEntryDate,23) AS FirstEntryDate,RoomSurveyDone,Average,[Month],[Year],MonthDays,PerDaySalary,Salary,SalaryType,DeductionType,
                                        AdvanceAmt,DeductionAmt,IncentivesAmt,PayableSalary,
                                        PaymentStatus,Remark,PayStatusRemark,AddedBy,CONVERT(varchar,AddedDate,23) AS AddedDate,UpdatedBy,CONVERT(varchar,UpdatedDate,23) AS UpdatedDate
                                        , TotalMobileCount, ReceivedMobileNo, WrongMobileNo, NotConnectedMobileNo
                                        FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName]
                                        WHERE SalaryP_ID = $SalaryP_ID";
                        $selectedData1 = $db->ExecutveQuerySingleRowSALData($ULB,$SelectQuery1, $userName, $appName, $developmentMode);
                        
                        if(sizeof($selectedData1) > 0){
                            $Executive_Cd = $selectedData1['Executive_Cd'];
                            $ExecutiveName = $selectedData1['ExecutiveName'];
                            $UserName = $selectedData1['UserName'];
                            $Designation = $selectedData1['Designation'];
                            $ReferenceName = $selectedData1['ReferenceName'];
                            $Present = $selectedData1['Present'];
                            $Absent = $selectedData1['Absent'];
                            $HalfDay = $selectedData1['HalfDay'];
                            $Training = $selectedData1['Training'];
                            $JoiningDate = $selectedData1['JoiningDate'];
                            $FirstEntryDate = $selectedData1['FirstEntryDate'];
                            $RoomSurveyDone = $selectedData1['RoomSurveyDone'];
                            $Average = $selectedData1['Average'];
                            $Month = $selectedData1['Month'];
                            $Year = $selectedData1['Year'];
                            $MonthDays = $selectedData1['MonthDays'];
                            $PerDaySalary = $selectedData1['PerDaySalary'];
                            $Salary = $selectedData1['Salary'];
                            $SalaryType = $selectedData1['SalaryType'];
                            $DeductionType = $selectedData1['DeductionType'];
                            $AdvanceAmt = $selectedData1['AdvanceAmt'];
                            $DeductionAmt = $selectedData1['DeductionAmt'];
                            $IncentivesAmt = $selectedData1['IncentivesAmt'];
                            $PayableSalary = $selectedData1['PayableSalary'];
                            $PaymentStatus = $selectedData1['PaymentStatus'];
                            $Remark = $selectedData1['Remark'];
                            $PayStatusRemark = $selectedData1['PayStatusRemark'];
                            $AddedBy = $selectedData1['AddedBy'];
                            $AddedDate = $selectedData1['AddedDate'];
                            $UpdatedBy = $selectedData1['UpdatedBy'];
                            $UpdatedDate = $selectedData1['UpdatedDate'];
                            $TotalMobileCount = $selectedData1['TotalMobileCount'];
                            $ReceivedMobileNo = $selectedData1['ReceivedMobileNo'];
                            $WrongMobileNo = $selectedData1['WrongMobileNo'];
                            $NotConnectedMobileNo = $selectedData1['NotConnectedMobileNo'];
                        }
                        
                        $InsertIntoMasterTableQuery = "UPDATE [$ServerIP].[Survey_SalaryProcess].[dbo].[SalaryProcessed_MasterTable]
                                                        SET
                                                            Executive_Cd = '$Executive_Cd',
                                                            ExecutiveName = '$ExecutiveName',
                                                            UserName = '$UserName',
                                                            Designation = '$Designation',
                                                            ReferenceName = '$ReferenceName',
                                                            Present = '$Present',
                                                            [Absent] = '$Absent',
                                                            HalfDay = '$HalfDay',
                                                            Training = '$Training',
                                                            JoiningDate = '$JoiningDate',
                                                            FirstEntryDate = '$FirstEntryDate',
                                                            RoomSurveyDone = '$RoomSurveyDone',
                                                            Average = '$Average',
                                                            [Month] = '$Month',
                                                            [Year] = '$Year',
                                                            MonthDays = '$MonthDays',
                                                            PerDaySalary = '$PerDaySalary',
                                                            Salary = '$Salary',
                                                            SalaryType = '$SalaryType',
                                                            DeductionType = '$DeductionType',
                                                            AdvanceAmt = '$AdvanceAmt',
                                                            DeductionAmt = '$DeductionAmt',
                                                            IncentivesAmt = '$IncentivesAmt',
                                                            PayableSalary = '$PayableSalary',
                                                            PaymentStatus = '$PaymentStatus',
                                                            Remark = N'$Remark',
                                                            PayStatusRemark = '$PayStatusRemark',
                                                            AddedBy = '$AddedBy',
                                                            AddedDate = '$AddedDate',
                                                            UpdatedBy = '$UpdatedBy',
                                                            UpdatedDate = '$UpdatedDate',
                                                            TotalMobileCount = '$TotalMobileCount',
                                                            ReceivedMobileNo = '$ReceivedMobileNo',
                                                            WrongMobileNo = '$WrongMobileNo',
                                                            NotConnectedMobileNo = '$NotConnectedMobileNo'
                                                        WHERE SalaryP_ID = $SalaryP_ID AND [Month] = $Month AND [Year] = $Year";
                        $runQuery = $db->RunQueryData($ULB,$InsertIntoMasterTableQuery, $userName, $appName,  $developmentMode);
                        
                    }else{
                        $InsertIntoMasterTableQuery = "INSERT INTO [$ServerIP].[Survey_SalaryProcess].[dbo].[SalaryProcessed_MasterTable]
                                                (
                                                    SalaryP_ID,Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,Present,Absent,HalfDay,Training,JoiningDate,
                                                    FirstEntryDate,RoomSurveyDone,Average,Month,Year,MonthDays,PerDaySalary,Salary,SalaryType,DeductionType,
                                                    AdvanceAmt,DeductionAmt,IncentivesAmt,PayableSalary,
                                                    PaymentStatus,Remark,PayStatusRemark,AddedBy,AddedDate,UpdatedBy,UpdatedDate, TotalMobileCount, ReceivedMobileNo, WrongMobileNo, NotConnectedMobileNo
                                                )
                                                (
                                                    SELECT SalaryP_ID,Executive_Cd,ExecutiveName,UserName,Designation,ReferenceName,Present,Absent,HalfDay,Training,JoiningDate,
                                                    FirstEntryDate,RoomSurveyDone,Average,Month,Year,MonthDays,PerDaySalary,Salary,SalaryType,DeductionType,
                                                    AdvanceAmt,DeductionAmt,IncentivesAmt,PayableSalary,
                                                    PaymentStatus,Remark,PayStatusRemark,AddedBy,AddedDate,UpdatedBy,UpdatedDate ,TotalMobileCount, ReceivedMobileNo, WrongMobileNo, NotConnectedMobileNo
                                                    FROM [$ServerIP].[Survey_SalaryProcess].[dbo].[$TableName] WHERE SalaryP_ID = $SalaryP_ID
                                                )";
                        $runQuery = $db->RunQueryData($ULB,$InsertIntoMasterTableQuery, $userName, $appName,  $developmentMode);
                    }
                }
                
                if($runQuery){
                    $flag = "U";
                }else{
                    $flag = "I";
                } 
                // Insert into Master Table -------------------------------------
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
            }else if($flag == "I"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Failed to Insert into Master Table!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
}
?>
