<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
   
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    // $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    // $electionName = $_SESSION['SurveyUA_ElectionName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];


    if (isset($_POST['society_cds']) && !empty($_POST['society_cds'])) 
    {
        $society_cds = $_POST['society_cds'];

        if (substr($society_cds, -1) === ',') { // Check if the last character is a comma
            $society_cds = substr($society_cds, 0, -1); // Remove the last character (i.e. the comma)
        }

        // echo $society_cds;
        // die();

        $sql = "UPDATE Society_Master 
            SET 
                BList_QC_UpdatedFlag = 1,
                BList_QC_UpdatedByUser = '$userName',
                BList_QC_UpdatedDate = GETDATE(),
                UpdatedByUser = '$userName', 
                UpdatedDate = GETDATE()
            WHERE Society_Cd IN ($society_cds);";

        // echo $sql;
        // die();

        $SocietyMasterBListQCCheckboxUpdate = $db->RunQueryData($ULB,$sql, $userName, $appName, $developmentMode);

        if($SocietyMasterBListQCCheckboxUpdate == true){
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured please try again'));
        }

    }
}

?>