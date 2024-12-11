<?php 
session_start();
include '../api/includes/DbOperation.php'; 
  
  $db=new DbOperation();
  $userName=$_SESSION['SurveyUA_UserName'];
  $appName=$_SESSION['SurveyUA_AppName'];
  $electionCd = $_SESSION['SurveyUA_Election_Cd'];
  $electionName = $_SESSION['SurveyUA_ElectionName'];
  $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
  $ULB=$_SESSION['SurveyUtility_ULB'];


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if(
        // (isset($_POST['electionCd']) && !empty($_POST['electionCd'])) && 
        (isset($_POST['pcktCd']) && !empty($_POST['pcktCd'])) &&
        (isset($_POST['pcktAssgnCd']) && !empty($_POST['pcktAssgnCd'])) &&
         (isset($_POST['exeCd']) && !empty($_POST['exeCd'])) &&
         (isset($_POST['usrId']) && !empty($_POST['usrId'])) &&
         (isset($_POST['srPocketRemoveRemark']) && !empty($_POST['srPocketRemoveRemark']))
      )
    {
      
        // $election_Cd = $_POST['electionCd'];
        // $dataElection = $db->getSurveyUtilityCorporationElectionByCdData($userName, $appName, $election_Cd, $developmentMode);
        // $election_Name = $dataElection["ElectionName"];
        // $_SESSION['SurveyUA_ElectionName'] = $election_Name;
        // $_SESSION['SurveyUA_Election_Cd'] = $election_Cd;
        
        // $electionCd = $_SESSION['SurveyUA_Election_Cd'];
        $electionName = $_SESSION['SurveyUA_ElectionName'];

        $pocketCd = $_POST['pcktCd'];
        $pocketAssignCd = $_POST['pcktAssgnCd'];
        $sremoveRemark = $_POST['srPocketRemoveRemark'];
        $userId = $_POST['usrId'];
        $executiveCd = $_POST['exeCd'];
        
        $updateAssignPocket = false;
        
        
        $query1 = "UPDATE Pocket_Master 
                  SET 
                      Executive_Cd = 0, 
                      AssignedDate = null , 
                      UpdatedByUser = '$userName' ,
                      UpdatedDate = GETDATE()
                  WHERE Pocket_Cd = $pocketCd AND Executive_Cd = $executiveCd;";

        // echo $query1;
        $db2=new DbOperation();
        $updateAssignPocket = $db2->RunQueryData($ULB,$query1, $userName, $appName, $developmentMode);

        $query2 = "  UPDATE PocketAssign 
                    SET 
                        SRRemoveRemark = '$sremoveRemark', 
                        SRRemovedDate = GETDATE() , 
                        UpdatedDate = GETDATE(), 
                        UpdatedBy = '$userName' 
                    WHERE PocketCd = $pocketCd AND SRExecutiveCd=$executiveCd AND PocketAssignCd = $pocketAssignCd ;";
        // echo $query2;
        $db3=new DbOperation();
        $updateAssignPocket = $db3->RunQueryData($ULB,$query2, $userName, $appName, $developmentMode);
               

          if($updateAssignPocket == true) 
          {
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
          }
          else
          {
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error in Assigning Pocket!'));
          }
      
    }
}
?>