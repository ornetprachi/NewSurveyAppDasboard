<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
    
    $SocietyNameMar = '';
    $AreaMar = '';
    $Sector = '';
    $PlotNo = '';
    $buildingImg = '';
    $buildingPlateImg = '';
    $buildingPlateImg_OLD_URL = '';



    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    // $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    // $electionName = $_SESSION['SurveyUA_ElectionName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];

    if (
        (isset($_POST['Society_Cd']) && !empty($_POST['Society_Cd'])) &&
        (isset($_POST['election_Cd']) && !empty($_POST['election_Cd'])) &&
        (isset($_POST['electionName']) && !empty($_POST['electionName'])) &&
        (isset($_POST['RejectedFlag']) && !empty($_POST['RejectedFlag'])) 
    ) 
    {
        $election_Cd = $_POST['election_Cd'];
        $electionName = $_POST['electionName'];
        $Society_Cd = $_POST['Society_Cd'];


        $sql1 = "UPDATE Society_Master SET
                    BList_QC_UpdatedFlag = 2,
                    BList_QC_UpdatedByUser = '$userName',
                    BList_QC_UpdatedDate = GETDATE(),
                    UpdatedByUser = '$userName', 
                    UpdatedDate = GETDATE()
                WHERE Society_Cd = $Society_Cd ;";

        $SocietyMasterBListQCReject = $db->RunQueryData($ULB,$sql1, $userName, $appName, $developmentMode);

        if($SocietyMasterBListQCReject == true){
            echo json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error occured, please try again' , 'error' => $SocietyMasterBListQCReject));
        }
    }




    if (
        (isset($_POST['Society_Cd']) && !empty($_POST['Society_Cd'])) &&
        (isset($_POST['election_Cd']) && !empty($_POST['election_Cd'])) &&
        (isset($_POST['electionName']) && !empty($_POST['electionName'])) &&
        (isset($_POST['Site_Cd']) && !empty($_POST['Site_Cd'])) &&
        (isset($_POST['society']) && !empty($_POST['society']))
    ) 
    {
     
        $SiteString = $_POST['Site_Cd'];
        $SiteString = explode("~",$SiteString);
            $Site_Cd = $SiteString[0];
            $SiteName = $SiteString[1];


        $PocketString = $_POST['pocketString'];
        if($PocketString != ""){
            $PocketString = explode("~",$PocketString);
            $pocketCd = $PocketString[0];
            $PocketName = $PocketString[1];
        }else{
            $pocketCd = 0;
            $PocketName = "";
        }
        


        $election_Cd = $_POST['election_Cd'];
        $electionName = $_POST['electionName'];
        $Society_Cd = $_POST['Society_Cd'];
        $society = $_POST['society'];
        $Area = $_POST['Area'];
        $Floor = $_POST['Floor'];
        $Rooms = $_POST['Rooms'];
        $newLat = $_POST['newLat'];
        $newLng = $_POST['newLng'];


        if((isset($_POST['societyMar']) && !empty($_POST['societyMar']))){
            $societyMar = "N'".$_POST['societyMar']."'";
        }else{
            $societyMar = "NULL";
        }

        if((isset($_POST['AreaMar']) && !empty($_POST['AreaMar']))){
            $AreaMar = "N'".$_POST['AreaMar']."'";
        }else{
            $AreaMar = "NULL";
        }

        if((isset($_POST['Sector']) && !empty($_POST['Sector']))){
            $Sector = $_POST['Sector'];
        }else{
            $Sector = "NULL";
        }

        if((isset($_POST['PlotNo']) && !empty($_POST['PlotNo']))){
            $PlotNo = $_POST['PlotNo'];
        }else{
            $PlotNo = "NULL";
        }

        // if((isset($_POST['buildingImg']) && !empty($_POST['buildingImg']))){
        //     $buildingImg = "N'".$_POST['buildingImg']."'";
        // }else{
        //     $buildingImg = "NULL";
        // }

        // if((isset($_POST['buildingPlateImg']) && !empty($_POST['buildingPlateImg']))){
        //     $buildingPlateImg = "N'".$_POST['buildingPlateImg']."'";
        // }else{
        //     $buildingPlateImg = "NULL";
        // }

        if((isset($_POST['buildingImg_OLD_URL']) && !empty($_POST['buildingImg_OLD_URL']))){
            $buildingImg_OLD_URL = "N'".$_POST['buildingImg_OLD_URL']."'";
        }else{
            $buildingImg_OLD_URL = "NULL";
        }

        if((isset($_POST['buildingPlateImg_OLD_URL']) && !empty($_POST['buildingPlateImg_OLD_URL']))){
            $buildingPlateImg_OLD_URL = "N'".$_POST['buildingPlateImg_OLD_URL']."'";
        }else{
            $buildingPlateImg_OLD_URL = "NULL";
        }


        $target_path = "../../UploadImagePhp/SurveyBuildingImage/";
        
        if(isset($_FILES['buildingImg']['name'])){

            $temp = explode(".", $_FILES["buildingImg"]["name"]);
            // $target_filename = round(microtime(true)) .'_'. $electionName .'_'. $Pocket_Cd . '.' . end($temp);
            $target_path = $target_path . "BP_" . $Society_Cd ;
            if (move_uploaded_file($_FILES['buildingImg']['tmp_name'], $target_path)) {
                
                $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                        $_SERVER['REQUEST_URI'];

                $file_Name_php = basename($link);
                $new_link = str_replace($file_Name_php, '', $link);
                        
                $buildingImgURL = $new_link.$target_path;
                $buildingImgURL = "N'".$buildingImgURL."'";
            }

        }else{
            $buildingImgURL = $buildingImg_OLD_URL;
        }


        if(isset($_FILES['buildingPlateImg']['name'])){

            $temp = explode(".", $_FILES["buildingPlateImg"]["name"]);
            // $target_filename = round(microtime(true)) .'_'. $electionName .'_'. $pocket_Cd . '.' . end($temp);
            $target_path = $target_path . "BPI_" . $Society_Cd ;
            if (move_uploaded_file($_FILES['buildingPlateImg']['tmp_name'], $target_path)) {
                
                $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                        $_SERVER['REQUEST_URI'];

                $file_Name_php = basename($link);
                $new_link = str_replace($file_Name_php, '', $link);
                  
                $buildingPlateImgURL = $new_link.$target_path;
                $buildingPlateImgURL = "N'".$buildingPlateImgURL."'";
            }

        }else{
            $buildingPlateImgURL = $buildingPlateImg_OLD_URL;
        }
        
        $DBName = $db->GetDBName($ULB,$electionName, $election_Cd, $userName, $appName, $developmentMode);

        if(!empty($DBName)){
       

            $sql1 = "UPDATE Society_Master SET
                Site_Cd = '$Site_Cd',
                SiteName = '$SiteName',
                SocietyName = '$society',
                SocietyNameMar = $societyMar, 
                Area = '$Area', 
                AreaMar = $AreaMar,
                Floor = '$Floor', 
                Rooms = '$Rooms', 
                Sector = '$Sector', 
                PlotNo = '$PlotNo', 
                Pocket_Cd = $pocketCd,
                PocketName = '$PocketName', 
                Building_Image = $buildingImgURL, 
                Building_Plate_Image = $buildingPlateImgURL,
                Latitude = '$newLat', 
                Longitude = '$newLng',
                BList_QC_UpdatedFlag = 1,
                BList_QC_UpdatedByUser = '$userName',
                BList_QC_UpdatedDate = GETDATE(),
                UpdatedByUser = '$userName', 
                UpdatedDate = GETDATE()
            WHERE Society_Cd = $Society_Cd ;";


            $SocietyMasterBListQCUpdate = $db->RunQueryData($ULB,$sql1, $userName, $appName, $developmentMode);

            


            if($SocietyMasterBListQCUpdate == true){

                $db2=new DbOperation();

                $sql2 = "UPDATE $DBName..SubLocationMaster SET
                    Site_Cd = '$Site_Cd',
                    SiteName = '$SiteName',
                    SocietyName = '$society',
                    SocietyNameM = $societyMar,
                    AreaM = $AreaMar,
                    Floor = '$Floor',
                    Rooms = '$Rooms',
                    Sector = '$Sector',
                    PlotNo = '$PlotNo',
                    Pocket_Cd = $pocketCd,
                    PocketName = '$PocketName',
                    Latitude = '$newLat',
                    Longitude = '$newLng'
                WHERE Survey_Society_Cd = $Society_Cd ; ";
            
                $IndividualMemberlistBListQCUpdate = $db2->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);

                if($IndividualMemberlistBListQCUpdate == true){
                    echo json_encode(array('statusCode' => 200, 'msg' => "Updated!"));
                }
                else{
                    echo json_encode(array('statusCode' => 204, 'msg' => 'SocietyMaster Updated, Error in Updating Individual Member List!' , 'error' => $IndividualMemberlistBListQCUpdate , 'query' => $sql2));
                }
            }
            else
            {
                echo json_encode(array('statusCode' => 204, 'msg' => 'Error in Updating! Please Try Again'));
            }
        }
        else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Error in Fetching DB Name!'));
        }

       
    }
}


?>