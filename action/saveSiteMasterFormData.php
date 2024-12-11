<?php

$Site_Cd = "";
$ClientName = "";
$SiteName = "";
$Area = "";
$Ward_No = "";
$Ac_No = "";
$Site_Start_Date = "";
$Site_End_Date = "";
$SupervisorName = "";
$ManagerName = "";
$Manager2 = "";
$ElectionName = "";
$ClientNameM = "";
$MobileNo = "";
$Remark = "";
$action  = "";


if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';

    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $updatedByUser = $userName;
    
    $updateSiteMaster = array();


        if(
            (isset($_POST['ClientName']) && !empty($_POST['ClientName'])) && 
            (isset($_POST['SiteName']) && !empty($_POST['SiteName'])) && 
            (isset($_POST['SupervisorName']) && !empty($_POST['SupervisorName'])) &&
            (isset($_POST['action']) && !empty($_POST['action'])) 
        ) {
 
            $Site_Cd = $_POST['Site_Cd'];
            $ClientName = trim($_POST['ClientName']);
            $SiteName = trim($_POST['SiteName']);
            $Area = trim($_POST['Area']);
            $Area1=substr($Area,0,5);
            $Ward_No = trim($_POST['Ward_No']);
            $Ac_No = trim($_POST['Ac_No']);
            $Site_Start_Date = $_POST['Site_Start_Date'];
            $Site_End_Date = $_POST['Site_End_Date'];

            $SupervisorName = $_POST['SupervisorName'];
            $SupervisorNameArr = explode('~',$SupervisorName);

            $SupervisorName = $SupervisorNameArr[0];
            $Supervisor_Cd = $SupervisorNameArr[1];


            $ManagerName = $_POST['ManagerName'];
            $Manager2 = $_POST['Manager2'];
            $ElectionName = $_POST['ElectionName']; 
            $ClientNameM = trim($_POST['ClientNameM']);
            $MobileNo = trim($_POST['MobileNo']);
            $Remark = trim($_POST['Remark']);
            $KMLFile_Url_PreviousUploaded = $_POST['KMLFile_Url_OLD'];
            $action = $_POST['action'];
            
            $SiteStatus = $_POST['SiteStatus'];
            $BldListingStatus = $_POST['BldListingStatus'];
            $AreaVisit = $_POST['AreaVisit'];
            $ApkStatus = $_POST['ApkStatus'];
            $LetterStatus = $_POST['LetterStatus'];
            
            $SiteNameForEdit = $_POST['SiteNameForEdit'];

            if(isset($_FILES["kmlFileUrl"]["name"]) && !empty($_FILES["kmlFileUrl"]["name"])){
                $target_path1 = "../../SurveyUtilityAppApi/upload/";
                $temp = explode(".", $_FILES["kmlFileUrl"]["name"]);
                $target_filename = round(microtime(true)) .''. $ElectionName .''. $Area1 . '.' . end($temp);
                $target_path1 = $target_path1 . $target_filename ;
                if (move_uploaded_file($_FILES['kmlFileUrl']['tmp_name'], $target_path1)) {
                    
                    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                            "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                            $_SERVER['REQUEST_URI'];
    
                            $file_Name_php = basename($link);
                            $new_link = str_replace($file_Name_php, '', $link);
                            
                    $kmlFileUrl = $new_link.$target_path1;
                } 
            }else {
                $kmlFileUrl = "$KMLFile_Url_PreviousUploaded";
            }
          
            
    
            if($action == 'Insert'){
                $Rowscount = 0;
                $ArraCount = array();
                $GetDataquery = "SELECT SiteName FROM Site_Master WHERE SiteName = '$SiteName';";
                
                $ArraCount = $db->ExecutveQuerySingleRowSALData($ULB,$GetDataquery, $userName, $appName, $developmentMode);
            //  print_r($ArraCount);
            //  die();
                if (sizeof($ArraCount) > 0 && $action == 'Insert'){
                    $flag = "E";
                }else{
                    $insertinto = "INSERT INTO Site_Master(Site_Cd, ClientName, SiteName, Area, Ward_No, Ac_No, Site_Start_Date, Site_End_Date, SupervisorName, ManagerName, Manager2, ElectionName, ClientNameM, MobileNo, Remark, KMLFile_Url, UpdateByUser, UpdatedDate,Closed,SiteStatus,Supervisor_Cd, LetterStatus, ApkStatus, AreaVisit, Bld_Listing_Status)
                                    Values((select CASE WHEN max(Site_Cd) is NULL THEN 1 ELSE (max(Site_Cd)+1) END AS Site_Cd 
                                    from Site_Master),'$ClientName', '$SiteName', '$Area', '$Ward_No', '$Ac_No', '$Site_Start_Date', '$Site_End_Date', '$SupervisorName', '$ManagerName', '$Manager2', '$ElectionName', N'$ClientNameM', '$MobileNo', N'$Remark', N'$kmlFileUrl', '$updatedByUser', GETDATE(),0,'$SiteStatus','$Supervisor_Cd','$LetterStatus', '$ApkStatus','$AreaVisit','$BldListingStatus');";
                    $Insert58 = $db->ExecutveQuerySingleRowSALData($ULB,$insertinto, $userName, $appName, $developmentMode);
                   
                    if($Insert58){
                        $flag = "I";
                    }else{
                        $flag = "IF";    
                    }
                }
            }else if($action == 'edit'){

                $updateinto = "UPDATE Site_Master
                    SET 
                    ClientName = '$ClientName', 
                    SiteName = '$SiteName', 
                    Area = '$Area',
                    Ward_No = '$Ward_No',
                    Ac_No = '$Ac_No',
                    Site_Start_Date = '$Site_Start_Date',
                    Site_End_Date = '$Site_End_Date',
                    SupervisorName = '$SupervisorName',
                    Supervisor_Cd = '$Supervisor_Cd',
                    ManagerName = '$ManagerName',
                    Manager2 = '$Manager2',
                    ElectionName = '$ElectionName',
                    ClientNameM = N'$ClientNameM',
                    MobileNo = '$MobileNo', 
                    Remark = N'$Remark', 
                    KMLFile_Url = N'$kmlFileUrl', 
                    UpdateByUser = '$updatedByUser',
                    UpdatedDate = GETDATE(),
                    LetterStatus = '$LetterStatus',
                    ApkStatus = '$ApkStatus',
                    AreaVisit = '$AreaVisit',
                    Bld_Listing_Status = '$BldListingStatus',
                    SiteStatus = '$SiteStatus'
                    where SiteName = '$SiteNameForEdit';";
                $Update58 = $db->RunSEDQueryData($ULB,$userName, $appName, $updateinto,$developmentMode);
               
                if($Update58){
                    $flag = "U";
                }else{
                    $flag = "UF";    
                }
            }else{
                $flag = "ANS";
            }
    }else{
        $flag = "RDM";
    }
    header('Location:../index.php?p=site-master&flag='.$flag);
}
?>
