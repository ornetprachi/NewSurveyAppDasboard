<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
   
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];
    $ULB=$_SESSION['SurveyUtility_ULB'];


    if(
        (isset($_POST['Society_Cd']) && !empty($_POST['Society_Cd'])) &&
        (isset($_POST['DBName']) && !empty($_POST['DBName'])) &&
        (isset($_POST['SubLocation_Cd']) && !empty($_POST['SubLocation_Cd'])) 
    ) 
    {
        $Society_Cd = $_POST['Society_Cd'];
        $DBName = $_POST['DBName'];
        $SubLocation_Cd = $_POST['SubLocation_Cd'];

        $sql = "UPDATE [Survey_Entry_Data].[dbo].[Society_Master]
                SET QC_Done_Flag = 1,
                QC_Done_By = '$Executive_Cd',
                QC_Done_Date = GETDATE()
                WHERE Society_Cd = $Society_Cd;";

        $SocietyMasterSurveyQCDoneFlag = $db->RunQueryData($ULB,$sql, $userName, $appName, $developmentMode);
        
        if($SocietyMasterSurveyQCDoneFlag == true){
            
            $query1 = "UPDATE $DBName.[dbo].[Dw_VotersInfo]
                    SET 
                        QC_UpdatedDate = GETDATE(),
                        QC_UpdateByUser = '$userName',
                        QC_Done = 1
                    WHERE SubLocation_Cd = '$SubLocation_Cd';";

            $VoterSurveyQCDoneFlag = $db->RunQueryData($ULB,$query1, $userName, $appName, $developmentMode);
            
            $query2 = "UPDATE $DBName.[dbo].[NewVoterRegistration]
                    SET 
                        QC_UpdatedDate = GETDATE(),
                        QC_UpdateByUser = '$userName',
                        QC_Done = 1
                    WHERE Subloc_cd = '$SubLocation_Cd';";

            $NonVoterSurveyQCDoneFlag = $db->RunQueryData($ULB,$query2, $userName, $appName, $developmentMode);

            if($VoterSurveyQCDoneFlag == true && $NonVoterSurveyQCDoneFlag == true){
                echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!"));
            }elseif($VoterSurveyQCDoneFlag == true && $NonVoterSurveyQCDoneFlag == false){
                echo json_encode(array('statusCode' => 204, 'msg' => 'Society and Voter updated successfully, error occured while updating Non-Voter!'));
            }elseif($VoterSurveyQCDoneFlag == false && $NonVoterSurveyQCDoneFlag == true){
                echo json_encode(array('statusCode' => 204, 'msg' => 'Society and Non-Voter updated successfully, error occured while updating Voter!'));
            }else{
                echo json_encode(array('statusCode' => 204, 'msg' => 'Society updated successfully, error occured while updating Voter and Non-Voter!'));
            }
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured please try again'));
        }

    }
}

?>