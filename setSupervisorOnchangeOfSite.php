<?php
session_start();
include 'api/includes/DbOperation.php';
 
$db = new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd = $_SESSION['SurveyUA_Election_Cd'];
$electionName = $_SESSION['SurveyUA_ElectionName'];
$developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
$updatedByUser = $userName;
$ServerIP = $_SESSION['SurveyUtility_ServerIP'];

if($ServerIP == "103.14.99.154"){
  $ServerIP =".";
}else{
  $ServerIP ="103.14.99.154";
}

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {

  if(isset($_POST['Site_Cd']) && !empty($_POST['Site_Cd']) ){
    
            $Site_CdGET = $_POST['Site_Cd'];
            $Site_CdData = explode('~',$Site_CdGET);
            $Site_Cd = $Site_CdData[0];

            $connectionString154 = array("Database"=> "Survey_Entry_Data", "CharacterSet" => "UTF-8", "Uid"=> "sa", "PWD"=>"154@2023SQL#ORNET01");
            $conn154 = sqlsrv_connect("103.14.99.154", $connectionString154);
            $tsql = "SELECT TOP 1
                    COALESCE(em.Executive_Cd, 0) as Executive_Cd,
                    COALESCE(em.ExecutiveName, '') as ExecutiveName
                    FROM [Survey_Entry_Data].[dbo].[Executive_Master] em
                    INNER JOIN Site_Master sm ON (em.ExecutiveName= sm.SupervisorName AND sm.Site_Cd = $Site_Cd)
                    WHERE (em.Designation = 'SP' OR em.Designation = 'Survey Supervisor') 
                    AND em.EmpStatus = 'A' AND em.ElectionName <> 'AMC' 
                    ORDER BY em.ExecutiveName;";
            $params = array($userName, $appName, $developmentMode);
            $data = $db->getDataInRowWithConnAndQueryAndParams($conn154, $tsql, $params);
          echo json_encode($data);
  }
}
?>

