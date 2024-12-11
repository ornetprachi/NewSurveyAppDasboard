<?php 
session_start();
include '../api/includes/DbOperation.php'; 
  
  $db=new DbOperation();
  $userName=$_SESSION['SurveyUA_UserName'];
  $appName=$_SESSION['SurveyUA_AppName'];
  $electionCd=$_SESSION['SurveyUA_Election_Cd'];
  $electionName=$_SESSION['SurveyUA_ElectionName'];
  $developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
  $ULB=$_SESSION['SurveyUtility_ULB'];
  $Exe_cd = $_SESSION['SurveyUA_Executive_Cd_Login'];

  $updatedByUser = $userName;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if(
         (isset($_POST['assignDate']) && !empty($_POST['assignDate'])) &&
         (isset($_POST['siteCd']) && !empty($_POST['siteCd'])) &&
         (isset($_POST['executiveCd']) && !empty($_POST['executiveCd'])) &&
         (isset($_POST['society_cds']) && !empty($_POST['society_cds']))
      )
    {
      
        // (isset($_POST['pocketCd']) && !empty($_POST['pocketCd'])) &&
        // $election_Cd = $_POST['electionCd'];

        // $dataElectionforSocietyAssign = $db->getSurveyUtilityCorporationElectionByCdData($userName, $appName, $election_Cd, $developmentMode);
        // $election_Name = $dataElectionforSocietyAssign["ElectionName"];
        
        $pocketCd = $_POST['pocketCd'];
        $assignDate = $_POST['assignDate'];
        $Site_Cd = $_POST['siteCd'];
        $executiveCd = $_POST['executiveCd'];

        if(isset($_POST['society_cds'])){
            $society_cds = $_POST['society_cds'];
        }else{
          $society_cds = '';
        }
        
        
        $societyCd_for_Update = substr($society_cds, 0, -1);
        // echo $societyCd_for_Update;

        $society_cds_Array = array();
        $society_cds_Array = explode(",", $societyCd_for_Update);

        // print_r($society_cds_Array);

        $exeSize = sizeOf($society_cds_Array);
        $exeNo = 0;
        
        $updateAssignSociety = false;

        
        $dbTime = new DbOperation();
        $dbTimeData = $dbTime->ExecutveQuerySingleRowSALData($ULB,"SELECT convert(varchar(10), GETDATE(), 108) as TimeStr",$userName, $appName, $developmentMode);

        $timeFormatData = $dbTimeData["TimeStr"].".000";
        $assignDate = $assignDate." ".$timeFormatData;

        foreach($society_cds_Array as $society_cd)
        {
            $sql = "";
            
            $sql = "SELECT Society_Cd FROM Society_Master WHERE Society_Cd = $society_cd ;";

            $exeNo = $exeNo + 1;
            $db1 = new DBOperation();
            $dataSocieties = $db1->ExecutveQueryMultipleRowSALData($ULB,$sql, $userName, $appName, $developmentMode);

            if(sizeOf($dataSocieties) > 0)
            {
                    $sql1 = "UPDATE Society_Master
                            SET
                            Executive_Cd = $executiveCd,
                            AssignedDate = '$assignDate',
                            AssignedBy = $Exe_cd,
                            -- AssignedBy
                            added_date = GETDATE()
                            WHERE Society_Cd IN ($society_cd)";

                    
                    $userName=$_SESSION['SurveyUA_UserName'];
                    $appName=$_SESSION['SurveyUA_AppName'];

                    $db3=new DbOperation();
                    $updateAssignSociety = $db3->RunQueryData($ULB,$sql1, $userName, $appName, $developmentMode);
            }
            else
            {
                    $sql2 = "UPDATE Society_Master
                            SET
                            Executive_Cd = '',
                            AssignedDate = '',
                            added_date = ''
                            WHERE Society_Cd = $society_cd
                            ";

                    $userName=$_SESSION['SurveyUA_UserName'];
                    $appName=$_SESSION['SurveyUA_AppName'];
                    $db3=new DbOperation();
                    $updateAssignSociety = $db3->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);
            }

            

        }
        // print_r($sql1);
        // die();
        if($updateAssignSociety == true) 
        {
          echo json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
        }
        else
        {
          echo json_encode(array('statusCode' => 204, 'msg' => 'Error in Assigning Society!'));
        }


    }
}
?>