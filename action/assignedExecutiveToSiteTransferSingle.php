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
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $ServerIP = $_SESSION['SurveyUtility_ServerIP'];

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
        if((isset($_POST['Date']) && !empty($_POST['Date']))){
 
            $Date = $_POST['Date'];
            $TransferDate = date('Y-m-d', strtotime('+1 day', strtotime($Date)));

            $getAllPreviousDataQuery = "SELECT ed.Executive_Cd, ed.ExecutiveName, ed.Site_CD, ed.SiteName, ed.ElectionName, ed.Attendance, ed.InTime, ed.latitude, ed.longitude
            FROM [Survey_Entry_Data].[dbo].[Executive_Details] AS ed
            INNER JOIN [Site_Master] AS sm ON (ed.SiteName = sm.SiteName AND sm.Closed = 0)
            INNER JOIN [Survey_Entry_Data].[dbo].[Executive_Master] AS em ON (ed.Executive_Cd = em.Executive_Cd AND em.EmpStatus <> 'NA')
            WHERE CONVERT(VARCHAR,ed.SurveyDate,23) = '$Date'";
            $getAllPreviousData = $db->ExecutveQueryMultipleRowSALData($ULB,$getAllPreviousDataQuery, $userName, $appName, $developmentMode);
            
            if(sizeof($getAllPreviousData)>0){
                foreach($getAllPreviousData AS $key=>$value){
                    $Executive_Cd = $value['Executive_Cd'];
                    $ExecutiveName = $value['ExecutiveName'];
                    $Site_CD = $value['Site_CD'];
                    $SiteName = $value['SiteName'];
                    $ElectionName = $value['ElectionName'];

                    $Attendance = $value['Attendance'];
                    $InTime = $value['InTime'];
                    $latitude = $value['latitude'];
                    $longitude = $value['longitude'];
                    
                    $queryData = "SELECT * FROM [Survey_Entry_Data].[dbo].[Executive_Details] WHERE Executive_Cd = $Executive_Cd AND convert(varchar, SurveyDate, 23) = '$TransferDate';";
                    $SingleData = $db->ExecutveQuerySingleRowSALData($ULB,$queryData, $userName, $appName, $developmentMode);
                    $SingleDataCOUNT = sizeof($SingleData);

                    if($SingleDataCOUNT > 0){
                        $flag = "E";
                    }else{

                        $insertinto="INSERT INTO [Survey_Entry_Data].[dbo].[Executive_Details] (Executive_Cd,ExecutiveName,Site_CD,SiteName,SurveyDate,UpdateByUser,Attendance,UpdatedDate,ElectionName, Attendance, InTime, latitude, longitude) 
                                                            VALUES ('$Executive_Cd','$ExecutiveName','$Site_CD','$SiteName','$TransferDate','$updatedByUser',0,GETDATE(),'$ElectionName', $Attendance, '$InTime', '$latitude', '$longitude');";
                        $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);

                        $insertinto="UPDATE [Survey_Entry_Data].[dbo].[Executive_Master]
                                    SET 
                                        Attendance = $Attendance,
                                        SurveyDate = '$TransferDate',
                                        ElectionName ='$ElectionName', 
                                        Site_CD = '$Site_CD',
                                        SiteName = '$SiteName' 
                                    WHERE Executive_Cd = $Executive_Cd;";
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
                echo json_encode(array('statusCode' => 204, 'msg' => "Failed to Transfer Data!"));
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
