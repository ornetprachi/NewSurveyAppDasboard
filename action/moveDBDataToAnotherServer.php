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
        (isset($_POST['FromServerName']) && !empty($_POST['FromServerName'])) &&
        (isset($_POST['FromElectionName']) && !empty($_POST['FromElectionName'])) &&
        (isset($_POST['ToServerName']) && !empty($_POST['ToServerName'])) &&
        (isset($_POST['ToElectionName']) && !empty($_POST['ToElectionName'])) 
    ) 
    {
        $SourceServerName = $_POST['FromServerName'];
        $SourceElectionName = $_POST['FromElectionName'];
        $DestinationServerName = $_POST['ToServerName'];
        $DestinationElectionName = $_POST['ToElectionName'];

        // $MoveSED = $db->getMoveDBDataAllInOneFunction_Testing($userName, $appName, $developmentMode,$Executive_Cd,$SourceServerName,$SourceElectionName,$DestinationServerName,$DestinationElectionName);
        $MoveSED = $db->getMoveDBDataAllInOneFunction($userName, $appName, $developmentMode,$Executive_Cd,$SourceServerName,$SourceElectionName,$DestinationServerName,$DestinationElectionName);

        if($MoveSED == true){
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!"));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured please try again'));
        }

        // print_r($MoveSED);
        // die();
    
    }
}