<?php 
session_start();
include '../api/includes/DbOperation.php'; 
  
  $db=new DbOperation();
  $userName=$_SESSION['SurveyUA_UserName'];
  $appName=$_SESSION['SurveyUA_AppName'];
  $electionCd=$_SESSION['SurveyUA_Election_Cd'];
  $electionName=$_SESSION['SurveyUA_ElectionName'];
  $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if(
        (isset($_POST['pocketName']) && !empty($_POST['pocketName'])) &&
         (isset($_POST['assignDate']) && !empty($_POST['assignDate'])) &&
         (isset($_POST['userId']) && !empty($_POST['userId'])) &&
         (isset($_POST['executiveCd']) && !empty($_POST['executiveCd']))
      )
    {
      
        $electionName = $_SESSION['SurveyUA_ElectionName'];
   
        $ULB=$_SESSION['SurveyUtility_ULB'];

        $pocketCd = $_POST['pocketName'];
        $Ac_No = $_POST['Ac_No'];
        $assignDate = $_POST['assignDate'];
        $userId = $_POST['userId'];
        $executiveCd = $_POST['executiveCd'];
        
        $updateAssignPocket = false;

        $dbTime = new DbOperation();
        $dbTimeData = $dbTime->ExecutveQuerySingleRowSALData($ULB,"SELECT convert(varchar(10), GETDATE(), 108) as TimeStr",$userName, $appName, $developmentMode);

        $timeFormatData = $dbTimeData["TimeStr"].".000";
        $assignDate = $assignDate." ".$timeFormatData;
        
        $query1 = "UPDATE Pocket_Master SET 
                    Executive_Cd = $executiveCd, 
                    AssignedDate = '$assignDate' , 
                    UpdatedByUser = '$userName' ,
                    UpdatedDate = GETDATE()
                  WHERE Pocket_Cd = $pocketCd;";
        // echo $query1;
        // die();

        $db2=new DbOperation();
        $updateAssignPocket = $db2->RunQueryData($ULB,$query1, $userName, $appName, $developmentMode);

        $query = "UPDATE Survey_Entry_Data..User_Master SET 
                    Survey_Ac_No = $Ac_No
                  WHERE  AppName = 'SurveyUtilityApp' AND ElectionName = '$electionName';";


        $UpdateUserMaster = $db2->RunQueryData($ULB,$query, $userName, $appName, $developmentMode);

        $query2 = "INSERT INTO PocketAssign (PocketCd, SRExecutiveCd, SRAssignedDate, AddedBy, AddedDate, UpdatedBy, UpdatedDate) 
                                VALUES ( $pocketCd, $executiveCd, '$assignDate', '$userName', GETDATE(), '$userName', GETDATE());";
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