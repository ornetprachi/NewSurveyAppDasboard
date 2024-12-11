<?php



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
    
    $updatePocketMaster = array();

    if  (
            (isset($_POST['AcNo']) && !empty($_POST['AcNo'])) &&
            (isset($_POST['PocketName']) && !empty($_POST['PocketName'])) && 
            (isset($_POST['PocketNameMar']) && !empty($_POST['PocketNameMar'])) && 
            (isset($_POST['siteName']) && !empty($_POST['siteName'])) && 
            (isset($_POST['action']) && !empty($_POST['action'])) 
        ) {
            
        // (isset($_POST['CorporatorCd']) && !empty($_POST['CorporatorCd'])) && 

        $action = $_POST['action'];
        $Pocket_Cd = $_POST['Pocket_Cd'];
        $PocketName = trim($_POST['PocketName']);
        $PocketNameMar = trim($_POST['PocketNameMar']);
        $Site_Cd = trim($_POST['siteName']);
            
        $Area = trim($_POST['Area']);
        $Ac_No = $_POST['AcNo'];
        $AreaNameMarathi = trim($_POST['AreaNameMarathi']);
                     
        $deActiveDate = $_POST['deActiveDate'];
       
        $isActive = $_POST['isActive'];
        $PocketNo = $_POST['PocketNo'];
        $CorporatorCd = $_POST['CorporatorCd'];
        
        $KMLFile_Url_PreviousUploaded = $_POST['KMLFile_Url_OLD'];
        
        if(isset($_SESSION['SurveyUtility_ServerIP']) && !empty( $_SESSION['SurveyUtility_ServerIP'])){
            $ServerIP = $_SESSION['SurveyUtility_ServerIP'];;
        }else{
            $ServerIP = "";
        }

        if(isset($_FILES['KMLFile_Url']['name']) && !empty($_FILES['KMLFile_Url']['name']))
        {
            // $target_path1 = "../uploads/newkml/";
            if (!file_exists('../../SurveyUtilityAppApi/upload/'.$electionName.'_KML/')) {
                mkdir('../../SurveyUtilityAppApi/upload/'.$electionName.'_KML/', 0777, true);
            }
            $target_path1 = "../../SurveyUtilityAppApi/upload/".$electionName."_KML/";
            
            $temp = explode(".", $_FILES["KMLFile_Url"]["name"]);

            $getRandomDig = rand(1111, 9999);
            // $target_filename = round(microtime(true)) .'_'. $electionName . '.' . end($temp);
            $target_filename = $temp[0].'_'.$getRandomDig.'.' . end($temp);
            // $target_filename = $ServerIP."/SurveyUtilityAppApi/upload/".$electionName.'/'.end($temp);
            $target_path1 = $target_path1 . $target_filename ;
            $NewLikePath = "/SurveyUtilityAppApi/upload/".$electionName."_KML/".$target_filename;
            if(move_uploaded_file($_FILES['KMLFile_Url']['tmp_name'], $target_path1)) {
                
                $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                    "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                    $_SERVER['REQUEST_URI'];

                $file_Name_php = basename($link);
                $new_link = str_replace($file_Name_php, '', $link);
                // $kmlFileUrl = $new_link.$target_filename;
                // $kmlFileUrl = "http://".$ServerIP.$NewLikePath;
                $kmlFileUrl = "http://103.14.99.154".$NewLikePath;
				
				// $kmlFileUrl = urlencode($kmlFileUrl);
                $kmlFileUrl = preg_replace('/\s+/', '%20', $kmlFileUrl);
                                    
            }
        }else{
            $kmlFileUrl = $KMLFile_Url_PreviousUploaded;
			// $kmlFileUrl = urlencode($kmlFileUrl);
            $kmlFileUrl = preg_replace('/\s+/', '%20', $kmlFileUrl);
        }
		
		
        $querySiteName = "SELECT Site_Cd, SiteName, Area, Ward_No FROM Site_Master WHERE Site_Cd = $Site_Cd";
        $SiteData = $db->ExecutveQuerySingleRowSALData($ULB,$querySiteName, $userName, $appName,  $developmentMode);
        if(sizeof($SiteData)){
            $SiteName = $SiteData['SiteName'];
            $Ward_No = $SiteData['Ward_No'];
        }


        $queryPocketCd = "";
        $queryPocketCd = "SELECT MAX(Pocket_Cd) AS Pocket_Cd FROM Pocket_Master;";
        $Pocket_Cd_Get = $db->ExecutveQuerySingleRowSALData($ULB,$queryPocketCd, $userName, $appName,  $developmentMode);
        if(sizeof($Pocket_Cd_Get)){
            $Pocket_Cd_for = $Pocket_Cd_Get['Pocket_Cd'];
            $Pocket_Cd_for_Insert = $Pocket_Cd_for+1;
        }
        
        $db1=new DbOperation();
        
        $updatePocketMaster = $db1->uploadPocketMasterData($ULB,$userName, $appName, $electionCd, $electionName, $developmentMode, $action, $Pocket_Cd, $Pocket_Cd_for_Insert, $PocketName, $PocketNameMar, $Area,  $AreaNameMarathi, $SiteName, $Site_Cd, $Ward_No, $kmlFileUrl, $deActiveDate, $isActive, $updatedByUser, $PocketNo, $CorporatorCd,$Ac_No);
    }

    // print_r($updatePocketMaster);
    // die();

    if (sizeof($updatePocketMaster) > 0) {

        $flag = $updatePocketMaster['Flag'];

        if($flag == 'U') {
            echo json_encode(array('statusCode' => 204, 'msg' => 'Updated successfully!'));
        } elseif($flag == 'I'){
            echo json_encode(array('statusCode' => 200, 'msg' => 'Insert successfully!'));            
        } elseif($flag == 'E'){
            echo json_encode(array('statusCode' => 206, 'msg' => 'Pocket Already Exists!'));
        } elseif($flag == 'D'){
            echo json_encode(array('statusCode' => 203, 'msg' => 'Pocket Deleted!'));
        } elseif($flag == 'F'){
            echo json_encode(array('statusCode' => 203, 'msg' => 'Failed To Insert!'));
        } elseif($flag == 'ED'){
            echo json_encode(array('statusCode' => 203, 'msg' => 'Pocket Already Exists but Deactive!'));
        }
    }else{
        echo json_encode(array('statusCode' => 404, 'msg' => 'Error.. Please try again!'));
    }

    header('Location:../index.php?p=pocket-master&flag='.$flag);
}
?>
