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

    if($ServerIP == "103.14.99.154"){
        $ServerIP =".";
    }else{
        $ServerIP ="103.14.99.154";
    }
    // $SiteNameArr = array();
    $ExecutiveCdsArr = '';
    $flag = "";
    $runQuery = false;
        if( 
            (isset($_POST['SurveyDate']) && !empty($_POST['SurveyDate'])) &&
            (isset($_POST['Attendance']) && !empty($_POST['Attendance'])) &&
            (isset($_POST['Namecd']) && !empty($_POST['Namecd'])) 
        ) {
 
            $SiteN  = $_POST['Site'];
            $Attendance  = $_POST['Attendance'];
            $SurveyDate  = $_POST['SurveyDate'];
            $Namecd  = $_POST['Namecd'];
            $Name  = $_POST['Name'];
            $InTime  = $_POST['InTime'];
            // $Election  = $_POST['Election'];
                   
            $queryAttData = "SELECT * FROM [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] WHERE Executive_Cd = $Namecd AND convert(varchar, SurveyDate, 23) = '$SurveyDate';";
            $SingleAttData = $db->ExecutveQuerySingleRowSALData($ULB,$queryAttData, $userName, $appName, $developmentMode);
            $SingleAttDataCOUNT = sizeof($SingleAttData);
            if($SingleAttDataCOUNT > 0){
                $flag = "E";
            }else{

                $SpQuery = "  SELECT Site_Cd,SiteName,ElectionName FROM [$ServerIP].[Survey_Entry_Data].[dbo].Site_Master WHERE SiteName = '$SiteN';";
                $SPData = $db->ExecutveQueryMultipleRowSALData($ULB,$SpQuery, $userName, $appName, $developmentMode);
                // print_r($SPData);
                // die();

                foreach ($SPData as $getAllSpData) {

                    $Site_CD = $getAllSpData['Site_Cd'];
                    $ElectionName = $getAllSpData['ElectionName']; 


                $insertinto="INSERT INTO [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Details] (Executive_Cd,ExecutiveName,Site_CD,SiteName,SurveyDate,UpdateByUser,Attendance,UpdatedDate,ElectionName) 
                VALUES ('$Namecd','$Name','$Site_CD','$SiteN','$SurveyDate','$userName',$Attendance,GETDATE(),'$ElectionName');";
                $runQuery = $db->RunQueryData($ULB,$insertinto, $userName, $appName,  $developmentMode);
                // print_r($insertinto);
                // die();
                $updateinto="UPDATE [$ServerIP].[Survey_Entry_Data].[dbo].[Executive_Master]
                            SET 
                                Attendance = 0,
                                SurveyDate = '$SurveyDate',
                                ElectionName ='$ElectionName', 
                                Site_CD = '$Site_CD',
                                SiteName = '$SiteN' 
                            WHERE Executive_Cd = $Namecd;";
                $runQuery = $db->RunQueryData($ULB,$updateinto, $userName, $appName,  $developmentMode);
                // print_r($updateinto);


                if($runQuery){
                    $flag = "U";
                }else{
                    $flag = "F";
                }

                }
            // print_r($flag);
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
                echo json_encode(array('statusCode' => 200, 'msg' => "Attendance Updated Successfully!"));
            }else if($flag == "E"){
                echo json_encode(array('statusCode' => 204, 'msg' => " Already Assigned!"));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing!'));
        }
    
}
?>
