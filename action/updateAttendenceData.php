<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
   
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    // $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];

    if (isset($_POST['Executive_Cd']) && !empty($_POST['Executive_Cd'])) 
    {
        // echo "test";die();
        $action = $_POST['action'];
//  print_r($action);die;
        $Executive_Cd = $_POST['Executive_Cd'];
       
        $ExecutiveName = $_POST['ExecutiveName'];
        $SurveyDate = $_POST['SurveyDate'];
        $Attendance = $_POST['Attendance'];
        $Doc_No = $_POST['Doc_No'];
       
        $AbsentRemark = $_POST['AbsentRemark'];
        $SiteString = $_POST['SiteName'];

        $SiteString = explode("~",$SiteString);
        $Site_Cd = $SiteString[0];
        $SiteName = $SiteString[1];

    if($action = 'update'){
    //    echo"update";die;
         
        $sql = "UPDATE Executive_Details
                    SET 
                        ExecutiveName = '$ExecutiveName',
                        SurveyDate= '$SurveyDate',
                        Attendance = '$Attendance',
                        AbsentRemark = N'$AbsentRemark',
                        SiteName = '$SiteName',
                        Site_CD = $Site_Cd
                    WHERE Executive_Cd = $Executive_Cd
                    AND Doc_No = '$Doc_No'
                    ";
               

            $updateAttendanceData = $db->RunQueryData($ULB,$sql, $userName, $appName, $developmentMode);

            if($updateAttendanceData == true){
                 json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
            }
            else{
                 json_encode(array('statusCode' => 204, 'msg' => 'Error occured please try again'));
            }
        // }else if($action = 'insert'){
        //  $query2 = "INSERT INTO Executive_Details(Executive_Cd,ExecutiveName,SiteName,Site_Cd,Attendance,AbsentRemark,
        //         SurveyDate, UpdatedBy, UpdatedDate) 
        //         VALUES($Executive_Cd,$ExecutiveName, $SiteName, '$Site_Cd','$Attendance','$AbsentRemark','$SurveyDate',GETDATE(),GETDATE());";
        //         $db3=new DbOperation();
        //         $insertattandance = $db3->RunQueryData($query2, $userName, $appName, $developmentMode);


        // }
      
         
        }
        // else if($action = 'insert'){
        //     echo"insert data";die;
        //     //$insert = ""
        // }
    }
}

?>