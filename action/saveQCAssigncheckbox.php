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


    if (
        (isset($_POST['society_cds']) && !empty($_POST['society_cds'])) &&
        (isset($_POST['ExecutiveCd']) && !empty($_POST['ExecutiveCd'])) 
        ) 
    {
        $society_cds = $_POST['society_cds'];
        $ExecutiveCd = $_POST['ExecutiveCd'];

        if (substr($society_cds, -1) === ',') { // Check if the last character is a comma
            $society_cds = substr($society_cds, 0, -1); // Remove the last character (i.e. the comma)
        }

        

        $sql = "UPDATE Society_Master 
                SET QC_Assign_To = '$ExecutiveCd',
                QC_Done_Flag = 3,
                QC_Assign_Date = GETDATE()
                WHERE Society_Cd IN ($society_cds);";

        // echo $sql;
        // die();

        $SocietyMasterBListQCCheckboxUpdate = $db->RunQueryData($ULB,$sql, $userName, $appName, $developmentMode);

        if($SocietyMasterBListQCCheckboxUpdate == true){
            echo json_encode(array('statusCode' => 200, 'msg' => "Assigned!"));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured please try again'));
        }

    }
}

?>