<?php
//Changes added by prachi
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
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];

    $_SESSION['assign-executive-to-site'] = "AssignTab";
    
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
            (isset($_POST['TransferDate']) && !empty($_POST['TransferDate'])) && 
            (isset($_POST['SupervisorCds']) && !empty($_POST['SupervisorCds']))
        ) {
 
            $TransferDate = $_POST['TransferDate'];
            $RecordDate = $_POST['RecordDate'];
            $PassedArr = $_POST['SupervisorCds'];
            
            // New
            $PassedArr = substr($PassedArr,0,-1);
            $PassedArrMain = explode(',',$PassedArr);
            
            foreach($PassedArrMain AS $PassedArrMainLoop){
                $PassedArr = explode('~',$PassedArrMainLoop);
                $SupervisorName = $PassedArr[0];
                $SupervisorCd = $PassedArr[1];
                $SiteName = $PassedArr[2];
                $electionName = $PassedArr[3];

                $getAllPreviousDataQuery = "SELECT Executive_Cd, ExecutiveName, Site_CD, SiteName, ElectionName 
                                        FROM [Survey_Entry_Data].[dbo].[Executive_Details]
                                        WHERE CONVERT(VARCHAR,SurveyDate,23) = '$RecordDate'
                                        AND SiteName = '$SiteName'";
                $getAllPreviousData = $db->ExecutveQueryMultipleRowSALData($ULB,$getAllPreviousDataQuery, $userName, $appName, $developmentMode);
                
                foreach ($getAllPreviousData as $getAllPreviousDataLoop) {
                    $Executive_Cd = $getAllPreviousDataLoop['Executive_Cd'];
                    $ExecutiveName = $getAllPreviousDataLoop['ExecutiveName'];
                    $Site_CD = $getAllPreviousDataLoop['Site_CD'];
                    $SiteName = $getAllPreviousDataLoop['SiteName'];
                    $ElectionName = $getAllPreviousDataLoop['ElectionName'];
                    
                    $queryData = "SELECT * FROM [Survey_Entry_Data].[dbo].[Executive_Details] WHERE Executive_Cd = $Executive_Cd AND convert(varchar, SurveyDate, 23) = '$TransferDate';";
                    $SingleData = $db->ExecutveQuerySingleRowSALData($ULB,$queryData, $userName, $appName, $developmentMode);
                    $SingleDataCOUNT = sizeof($SingleData);

                    if($SingleDataCOUNT > 0){
                        $flag = "E";
                    }else{

                        $insertinto="INSERT INTO [Survey_Entry_Data].[dbo].[Executive_Details] (Executive_Cd,ExecutiveName,Site_CD,SiteName,SurveyDate,UpdateByUser,Attendance,UpdatedDate,ElectionName) 
                                                            VALUES ('$Executive_Cd','$ExecutiveName','$Site_CD','$SiteName','$TransferDate','$updatedByUser',0,GETDATE(),'$ElectionName');";
                        $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                        $insertinto="UPDATE [Survey_Entry_Data].[dbo].[Executive_Master]
                                    SET 
                                        Attendance = 0,
                                        SurveyDate = '$TransferDate',
                                        ElectionName ='$ElectionName', 
                                        Site_CD = '$Site_CD',
                                        SiteName = '$SiteName' 
                                    WHERE Executive_Cd = $Executive_Cd;";
                                  
                        $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                        $insertinto="UPDATE [Site_Master]
                                    SET
                                        SupervisorName = '$SupervisorName',
                                        Supervisor_Cd = '$SupervisorCd'
                                    WHERE Site_Cd = $Site_CD;";
                        $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);
                        
                        if($runQuery){
                            $flag = "U";
                        }else{
                            $flag = "F";
                        }
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
