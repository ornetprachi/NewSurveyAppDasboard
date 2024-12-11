<?php

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    include '../api/includes/DbOperation.php';
   
    $db=new DbOperation();
    $userName=$_SESSION['SurveyUA_UserName'];
    $appName=$_SESSION['SurveyUA_AppName'];
    $developmentMode = $_SESSION['SurveyUA_DevelopmentMode'];
    $ULB=$_SESSION['SurveyUtility_ULB'];
    $Executive_Cd = $_SESSION['SurveyUA_Executive_Cd_Login'];



    //==================================================Delete Extra NonVoter Start==============================================================================================

    if(
        (isset($_POST['Voter_Cd']) && !empty($_POST['Voter_Cd'])) &&
        (isset($_POST['DeleteExtraVoter']) && $_POST['DeleteExtraVoter'] == 'DeleteExtraVoter')
    ) 
    {

        if(
            (isset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionName_SurveyQC_Details'])) &&
            (isset($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'])) &&
            (isset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'])) 
        ){
            $electionName = $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'];
            $electionCd = $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'];
            $Society_Cd = $_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'];
            $pagetype = $_SESSION['SurveyUA_pagetype_SurveyQC_Details'];

            if($pagetype == 'Card'){
                $redirectURL = "-Card&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
            }else{
                $redirectURL = "&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
            }

            $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
        }

        // $DBName = $_POST['DBName'];
        $NonvVoterVoter_Cd = $_POST['Voter_Cd'];



        $IfNotExistCreateQuery = "IF NOT EXISTS (SELECT 1 FROM $DBName.sys.tables WHERE name = 'NewVoterRegistrationDeleted') 	
                            CREATE TABLE [$DBName].[dbo].[NewVoterRegistrationDeleted](
                                [Voter_Cd] [int] NOT NULL,
                                [Name] [nvarchar](255) NULL,
                                [NameM] [nvarchar](255) NULL,
                                [Middlename] [nvarchar](255) NULL,
                                [MiddlenameM] [nvarchar](255) NULL,
                                [Surname] [nvarchar](255) NULL,
                                [SurnameM] [nvarchar](255) NULL,
                                [Age] [int] NULL,
                                [Education] [nvarchar](255) NULL,
                                [Birthdate] [datetime] NULL,
                                [Livingyear] [nvarchar](255) NULL,
                                [Mobileno] [nvarchar](255) NULL,
                                [Societyname] [nvarchar](255) NULL,
                                [Subloc_cd] [int] NULL,
                                [Remark] [nvarchar](255) NULL,
                                [Roomno] [nvarchar](255) NULL,
                                [Fullname] [nvarchar](255) NULL,
                                [FullnameM] [nvarchar](255) NULL,
                                [Occupation] [nvarchar](255) NULL,
                                [UpdatedStatus] [nvarchar](255) NULL,
                                [OwnerName] [nvarchar](255) NULL,
                                [OwnerAddress] [nvarchar](255) NULL,
                                [RefVoterID] [nvarchar](50) NULL,
                                [RefVoterListNo] [nvarchar](50) NULL,
                                [LPNO] [int] NULL,
                                [ServerUpdatedFlag] [nvarchar](255) NULL,
                                [Ward_No] [int] NULL,
                                [UpdatedDate] [datetime] NULL,
                                [UpdateByUser] [nvarchar](50) NULL,
                                [Sex] [nvarchar](10) NULL,
                                [OldWard_No] [nvarchar](50) NULL,
                                [MarNmar_det] [nvarchar](50) NULL,
                                [ControlChartWard] [int] NULL,
                                [LockedButSurvey] [nvarchar](50) NULL,
                                [HouseStatus_Cd] [int] NULL,
                                [Hstatus] [nvarchar](10) NULL,
                                [OwnerMobileNo] [nvarchar](50) NULL,
                                [District] [nvarchar](50) NULL,
                                [Ac_No] [int] NULL,
                                [Col1] [nvarchar](50) NULL,
                                [Col2] [nvarchar](50) NULL,
                                [Col3] [nvarchar](50) NULL,
                                [Col4] [nvarchar](50) NULL,
                                [Col5] [nvarchar](50) NULL,
                                [MarNmar] [nvarchar](50) NULL,
                                [Religion] [nvarchar](50) NULL,
                                [SubCaste] [nvarchar](50) NULL,
                                [AndroidFormNo] [bigint] NULL,
                                [FamilyNo] [bigint] NULL,
                                [AnniversaryDate] [date] NULL,
                                [StayOutside] [bit] NULL,
                                [FamilyCount] [int] NULL,
                                [VidhanSabha] [int] NULL,
                                [Site_Cd] [int] NULL,
                                [SiteName] [nvarchar](50) NULL,
                                [DSK_UpdatedByUser] [nvarchar](50) NULL,
                                [DSK_UpdatedDate] [datetime] NULL,
                                [QC_UpdatedDate] [datetime] NULL,
                                [QC_UpdateByUser] [nvarchar](50) NULL,
                                [QC_Done] [bit] NULL,
                                [Survey_Society_Cd] [int] NULL,
                                [Voter_Id] [int] NULL,
                                [List_No] [int] NULL,
                                [MajorIssues] [nvarchar](50) NULL,
                                [Bpl] [nvarchar](12) NULL,
                                [BloodGroup] [nvarchar](50) NULL,
                                [FavParty] [nvarchar](10) NULL,
                                [ShiftedStatus_Cd] [int] NULL,
                                [Sstatus] [nvarchar](10) NULL,
                                [Dead] [int] NULL,
                                [VMark_Cd] [int] NULL,
                                [VMarkName] [nvarchar](10) NULL,
                                [Email] [nvarchar](100) NULL,
                                [Mobile] [nvarchar](12) NULL,
                                [VoterDone] [int] NULL,
                                [SF] [int] NULL,
                                [Srno] [bigint] NULL,
                                [Leader_Cd] [int] NULL,
                                [Leader] [nvarchar](100) NULL,
                                [Party_Cd] [int] NULL,
                                [Party] [nvarchar](50) NULL,
                                [Remark2] [nvarchar](255) NULL,
                                [Remark3] [nvarchar](255) NULL,
                                [Remark4] [nvarchar](255) NULL,
                                [SubLocationNo] [int] NULL,
                                [SocietyNameM] [nvarchar](255) NULL,
                                [NoOfVisits] [nvarchar](50) NULL,
                                [PhoneNo] [nvarchar](11) NULL,
                                [Longitude] [nvarchar](150) NULL,
                                [Latitude] [nvarchar](150) NULL,
                                [Altitude] [nvarchar](150) NULL,
                                [Nmar] [nvarchar](50) NULL,
                                [Nmar_Det] [nvarchar](50) NULL,
                                [AddedDate] [datetime] NULL,
                                [BoothName] [nvarchar](255) NULL,
                                [BoothNameMar] [nvarchar](255) NULL,
                                [BoothRoomNo] [int] NULL,
                                [BootKendraNo] [int] NULL,
                                [Comitee_Cd] [int] NULL,
                                [Comitee] [nvarchar](100) NULL,
                                [City_Cd] [int] NULL,
                                [City] [nvarchar](100) NULL,
                                [NewVoterFlag] [nvarchar](10) NULL,
                                [Recieved_Status] [int] NULL,
                                [Category_Cd] [int] NULL,
                                [QC_Calling_UpdatedDate] [datetime] NULL,
                                [QC_Calling_Status_Cd] [int] NULL,
                                [SMS_UpdatedDate] [datetime] NULL,
                                [SMS_Flag] [int] NULL,
                                [QC_Calling_UpdatedByUser] [varchar](15) NULL,
                                [QC_Birthday_Wish_updatedDate] [datetime] NULL,
                                [QC_Birthday_Wish_Status] [varchar](50) NULL,
                                [QC_Birthday_Wish_UpdatedByUser] [varchar](50) NULL,
                                [SurveyDate_2018] [datetime] NULL,
                                [QC_Calling_Recording] [varchar](255) NULL,
                                [QC_Birthday_Recording] [varchar](255) NULL,
                                [QC_Remark] [nvarchar](255) NULL,
                                [QC_Round1] [int] NULL,
                                [QC_Round2] [int] NULL,
                                [QC_Round3] [int] NULL,
                                [Font_Cd] [int] NULL,
                                [BD_Call_Executive_Cd] [int] NULL,
                                [BD_Call_Temp_Exe_Cd] [int] NULL,
                                [SR_Call_Executive_Cd] [int] NULL,
                                [SR_Call_Temp_Exe_Cd] [int] NULL,
                                [BD_Call_Assign_Date] [datetime] NULL,
                                [SR_Call_Assign_Date] [datetime] NULL,
                                [NewRoomNo] [varchar](50) NULL
                            );";

        $IfNotExistCreateQueryExecute = $db->RunQueryData($ULB,$IfNotExistCreateQuery, $userName, $appName, $developmentMode);
                        
        $sql1 = "INSERT INTO $DBName..NewVoterRegistrationDeleted (Voter_Cd, Name, NameM, Middlename, MiddlenameM
            ,Surname ,SurnameM, Age, Education, Birthdate, Livingyear, Mobileno, Societyname, Subloc_cd, Remark, Roomno, Fullname, FullnameM, Occupation, UpdatedStatus
            ,OwnerName, OwnerAddress, RefVoterID, RefVoterListNo, LPNO, ServerUpdatedFlag, Ward_No, UpdatedDate, UpdateByUser, Sex, OldWard_No, MarNmar_det
            ,ControlChartWard, LockedButSurvey, HouseStatus_Cd, Hstatus, OwnerMobileNo, District, Ac_No, Col1, Col2, Col3, Col4, Col5, MarNmar, Religion, SubCaste
            ,AndroidFormNo, FamilyNo, AnniversaryDate, StayOutside, FamilyCount, VidhanSabha, Site_Cd, SiteName, DSK_UpdatedByUser, DSK_UpdatedDate
            ,QC_UpdatedDate, QC_UpdateByUser, QC_Done, Survey_Society_Cd, Voter_Id, List_No, MajorIssues, Bpl, BloodGroup, FavParty, ShiftedStatus_Cd
            ,Sstatus, Dead, VMark_Cd, VMarkName, Email, Mobile, VoterDone, SF, Srno, Leader_Cd, Leader, Party_Cd, Party, Remark2, Remark3, Remark4
            ,SubLocationNo, SocietyNameM, NoOfVisits, PhoneNo, Longitude, Latitude, Altitude, Nmar, Nmar_Det, AddedDate, BoothName, BoothNameMar
            ,BoothRoomNo, BootKendraNo, Comitee_Cd, Comitee, City_Cd, City, NewVoterFlag, Recieved_Status, Category_Cd, QC_Calling_UpdatedDate
            ,QC_Calling_Status_Cd, SMS_UpdatedDate, SMS_Flag, QC_Calling_UpdatedByUser, QC_Birthday_Wish_updatedDate, QC_Birthday_Wish_Status
            ,QC_Birthday_Wish_UpdatedByUser, SurveyDate_2018, QC_Calling_Recording, QC_Birthday_Recording, QC_Remark, QC_Round1, QC_Round2, QC_Round3, Font_Cd
            ,BD_Call_Executive_Cd, BD_Call_Temp_Exe_Cd, SR_Call_Executive_Cd, SR_Call_Temp_Exe_Cd, BD_Call_Assign_Date, SR_Call_Assign_Date, NewRoomNo)
        SELECT Voter_Cd, Name, NameM, Middlename, MiddlenameM
            ,Surname ,SurnameM, Age, Education, Birthdate, Livingyear, Mobileno, Societyname, Subloc_cd, Remark, Roomno, Fullname, FullnameM, Occupation, UpdatedStatus
            ,OwnerName, OwnerAddress, RefVoterID, RefVoterListNo, LPNO, ServerUpdatedFlag, Ward_No, UpdatedDate, UpdateByUser, Sex, OldWard_No, MarNmar_det
            ,ControlChartWard, LockedButSurvey, HouseStatus_Cd, Hstatus, OwnerMobileNo, District, Ac_No, Col1, Col2, Col3, Col4, Col5, MarNmar, Religion, SubCaste
            ,AndroidFormNo, FamilyNo, AnniversaryDate, StayOutside, FamilyCount, VidhanSabha, Site_Cd, SiteName, DSK_UpdatedByUser, DSK_UpdatedDate
            ,QC_UpdatedDate, QC_UpdateByUser, QC_Done, Survey_Society_Cd, Voter_Id, List_No, MajorIssues, Bpl, BloodGroup, FavParty, ShiftedStatus_Cd
            ,Sstatus, Dead, VMark_Cd, VMarkName, Email, Mobile, VoterDone, SF, Srno, Leader_Cd, Leader, Party_Cd, Party, Remark2, Remark3, Remark4
            ,SubLocationNo, SocietyNameM, NoOfVisits, PhoneNo, Longitude, Latitude, Altitude, Nmar, Nmar_Det, AddedDate, BoothName, BoothNameMar
            ,BoothRoomNo, BootKendraNo, Comitee_Cd, Comitee, City_Cd, City, NewVoterFlag, Recieved_Status, Category_Cd, QC_Calling_UpdatedDate
            ,QC_Calling_Status_Cd, SMS_UpdatedDate, SMS_Flag, QC_Calling_UpdatedByUser, QC_Birthday_Wish_updatedDate, QC_Birthday_Wish_Status
            ,QC_Birthday_Wish_UpdatedByUser, SurveyDate_2018, QC_Calling_Recording, QC_Birthday_Recording, QC_Remark, QC_Round1, QC_Round2, QC_Round3, Font_Cd
            ,BD_Call_Executive_Cd, BD_Call_Temp_Exe_Cd, SR_Call_Executive_Cd, SR_Call_Temp_Exe_Cd, BD_Call_Assign_Date, SR_Call_Assign_Date, NewRoomNo
        FROM $DBName..NewVoterRegistration
        WHERE Voter_Cd = $NonvVoterVoter_Cd;";
                        
        $NewVotRegDeletedAdd = $db->RunQueryData($ULB,$sql1, $userName, $appName, $developmentMode);

        if($NewVotRegDeletedAdd){
            $sql2 = "DELETE FROM $DBName..NewVoterRegistration
            WHERE Voter_Cd = $NonvVoterVoter_Cd;";

            $NewVotDeleteQuery = $db->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);

            if($NewVotDeleteQuery){
                echo json_encode(array('statusCode' => 200, 'msg' => "Deleted Successfully!", 'url' => $redirectURL));
                unset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']);
                unset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']);
                unset($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details']);
                unset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']);
                unset($_SESSION['SurveyUA_pagetype_SurveyQC_Details']);
            }
            else{
                echo json_encode(array('statusCode' => 204, 'msg' => 'Entry added to NewVoterRegistrationDeleted, could not delete from NewVoterRegistration, please try again later'));
            }
        }




    }

    //==================================================Delete Extra NonVoter End========================================================================================== 




    if(
        (isset($_POST['NonvVoterVoter_Cd']) && !empty($_POST['NonvVoterVoter_Cd'])) &&
        (isset($_POST['VoterCds']) && !empty($_POST['VoterCds']))
    ) 
    {
        $NonvVoterVoter_Cd = $_POST['NonvVoterVoter_Cd'];
        $VoterCds = $_POST['VoterCds'];

        if(isset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_VoterCd_SurveyQC_Details'])){
            $MasterVoter_Cd = $_SESSION['SurveyUA_VoterCd_SurveyQC_Details'];
        }else{
            $MasterVoter_Cd = 0;
        }


        if (substr($VoterCds, -1) === ',') { // Check if the last character is a comma
            $VoterCds = substr($VoterCds, 0, -1); // Remove the last character (i.e. the comma)
        }

        if(
            (isset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionName_SurveyQC_Details'])) &&
            (isset($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'])) &&
            (isset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'])) 
        ){
            $electionName = $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'];
            $electionCd = $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'];
            $Society_Cd = $_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'];
            $pagetype = $_SESSION['SurveyUA_pagetype_SurveyQC_Details'];

            if($pagetype == 'Card'){
                $redirectURL = "-Card&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
            }else{
                $redirectURL = "&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
            }

            $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
        }

        if(!empty($DBName)){

            $sql = "SELECT 
                COALESCE(UpdatedStatus, '') AS UpdatedStatus,
                COALESCE(HStatus, '') AS HStatus,
                COALESCE(SStatus, '') AS SStatus,
                COALESCE(Education, '') AS Education,
                COALESCE(Occupation, '') AS Occupation,
                COALESCE(Religion, '') AS Religion,
                COALESCE(SubCaste, '') AS SubCaste,
                COALESCE(SF, 0) AS SF,
                COALESCE(VidhanSabha, 0) AS VidhanSabha,
                COALESCE(MajorIssues, '') AS MajorIssues,
                COALESCE(SocietyName, '') AS SocietyName,
                COALESCE(SocietyNameM, '') AS SocietyNameM,
                COALESCE(Subloc_cd, 0) AS Subloc_cd,
                COALESCE(BirthDate, '') AS BirthDate,
                COALESCE(CONVERT(VARCHAR,AnniversaryDate,23), '') AS AnniversaryDate,
                COALESCE(Remark, '') AS Remark,
                COALESCE(RooMNo, '') AS RooMNo,
                COALESCE(MobileNo, '') AS MobileNo,
                COALESCE(OwnerName, '') AS OwnerName,
                COALESCE(OwnerMobileNo, '') AS OwnerMobileNo,
                COALESCE(District, '') AS District,
                COALESCE(Livingyear, '') AS Livingyear,
                COALESCE(LPNO, '') AS LPNO,
                COALESCE(AndroidFormNo, '') AS AndroidFormNo,
                COALESCE(CONVERT(VARCHAR,UpdatedDate,23), '') AS UpdatedDate,
                COALESCE(UpdateByUser, '') AS UpdateByUser,
                COALESCE(CONVERT(VARCHAR,QC_UpdatedDate,23), '') AS QC_UpdatedDate,
                COALESCE(QC_UpdateByUser, '') AS QC_UpdateByUser,
                COALESCE(QC_Done, '') AS QC_Done,
                COALESCE(SiteName, '') AS SiteName,
                COALESCE(LockedButSurvey, '') AS LockedButSurvey,
                COALESCE(Ward_No, 0) AS Ward_No
            FROM $DBName..NewVoterRegistration Where Voter_Cd = $NonvVoterVoter_Cd";


            $GetNonVoterData = $db->ExecutveQuerySingleRowSALData($ULB,$sql, $userName, $appName, $developmentMode);
    
            if(sizeof($GetNonVoterData)){
                $Hstatus = $GetNonVoterData['HStatus'];
                $Sstatus = $GetNonVoterData['SStatus'];
                $Education = $GetNonVoterData['Education'];
                $Occupation = $GetNonVoterData['Occupation'];
                $Religion = $GetNonVoterData['Religion'];
                $SubCaste = $GetNonVoterData['SubCaste'];
                $VidhanSabha = $GetNonVoterData['VidhanSabha'];
                $MajorIssues = $GetNonVoterData['MajorIssues'];
                $Societyname = $GetNonVoterData['SocietyName'];
                $SocietyNameM = $GetNonVoterData['SocietyNameM'];
                $Subloc_cd = $GetNonVoterData['Subloc_cd'];
                $Birthdate = $GetNonVoterData['BirthDate'];
                $AnniversaryDate = $GetNonVoterData['AnniversaryDate'];
                $Remark = $GetNonVoterData['Remark'];
                $Roomno = $GetNonVoterData['RooMNo'];
                $Mobileno = $GetNonVoterData['MobileNo'];
                $OwnerName = $GetNonVoterData['OwnerName'];
                $OwnerMobileNo = $GetNonVoterData['OwnerMobileNo'];
                $District = $GetNonVoterData['District'];
                $Livingyear = $GetNonVoterData['Livingyear'];
                $LPNO = $GetNonVoterData['LPNO'];
                $AndroidFormNo = $GetNonVoterData['AndroidFormNo'];
                $UpdatedDate = $GetNonVoterData['UpdatedDate'];
                $UpdateByUser = $GetNonVoterData['UpdateByUser'];
                $QC_UpdatedDate = $GetNonVoterData['QC_UpdatedDate'];
                $QC_UpdateByUser = $GetNonVoterData['QC_UpdateByUser'];
                $QC_Done = $GetNonVoterData['QC_Done'];
                $SiteName = $GetNonVoterData['SiteName'];
                $LockedButSurvey = $GetNonVoterData['LockedButSurvey'];
                $Ward_No = $GetNonVoterData['Ward_No'];

                if(!empty($Education)){
                    $EducationCond = "Education='$Education',";
                }else{
                    $EducationCond = "";
                }

                if(!empty($Occupation)){
                    $OccupationCond = "Occupation='$Occupation',";
                }else{
                    $OccupationCond = "";
                }

                if(!empty($Religion)){
                    $ReligionCond = "Religion='$Religion',";
                }else{
                    $ReligionCond = "";
                }

                if(!empty($SubCaste)){
                    $SubCasteCond = "SubCaste='$SubCaste',";
                }else{
                    $SubCasteCond = "";
                }

                if(!empty($VidhanSabha) && $VidhanSabha > 0){
                    $VidhanSabhaCond = "VidhanSabha=$VidhanSabha,";
                }else{
                    $VidhanSabhaCond = "";
                }

                if(!empty($MajorIssues)){
                    $MajorIssuesCond = "MajorIssues='$MajorIssues',";
                }else{
                    $MajorIssuesCond = "";
                }

                if(!empty($Societyname)){
                    $SocietynameCond = "SocietyName='$Societyname',";
                }else{
                    $SocietynameCond = "";
                }

                if(!empty($SocietyNameM)){
                    $SocietyNameMCond = "SocietyNameM= N'$SocietyNameM',";
                }else{
                    $SocietyNameMCond = "";
                }

                if(!empty($Subloc_cd) && $Subloc_cd >0){
                    $Subloc_cdCond = "SubLocation_Cd= $Subloc_cd,";
                }else{
                    $Subloc_cdCond = "";
                }

                if(!empty($AnniversaryDate)){
                    $AnniversaryDateCond = "AnniversaryDate= '$AnniversaryDate',";
                }else{
                    $AnniversaryDateCond = "";
                }

                if(!empty($Remark)){
                    $RemarkCond = "Remark= '$Remark',";
                }else{
                    $RemarkCond = "";
                }

                if(!empty($Roomno)){
                    $RoomnoCond = "RooMNo= '$Roomno',";
                }else{
                    $RoomnoCond = "";
                }

                if(!empty($Mobileno)){
                    $MobilenoCond = "MobileNo= '$Mobileno',";
                }else{
                    $MobilenoCond = "";
                }

                if(!empty($OwnerName)){
                    $OwnerNameCond = "OwnerName= '$OwnerName',";
                }else{
                    $OwnerNameCond = "";
                }

                if(!empty($OwnerMobileNo)){
                    $OwnerMobileNoCond = "OwnerMobileNo= '$OwnerMobileNo',";
                }else{
                    $OwnerMobileNoCond = "";
                }

                if(!empty($District)){
                    $DistrictCond = "District= '$District',";
                }else{
                    $DistrictCond = "";
                }

                if(!empty($Livingyear)){
                    $LivingyearCond = "Livingyear= '$Livingyear',";
                }else{
                    $LivingyearCond = "";
                }

                if(!empty($LPNO) && $LPNO > 0){
                    $LPNOCond = "LPNO= $LPNO,";
                }else{
                    $LPNOCond = "";
                }

                if(!empty($AndroidFormNo) && $AndroidFormNo>0){
                    $AndroidFormNoCond = "AndroidFormNo= $AndroidFormNo,";
                }else{
                    $AndroidFormNoCond = "";
                }

                if(!empty($UpdatedDate)){
                    $UpdatedDateCond = "UpdatedDate= '$UpdatedDate',";
                }else{
                    $UpdatedDateCond = "";
                }

                if(!empty($UpdateByUser)){
                    $UpdateByUserCond = "UpdateByUser= '$UpdateByUser',";
                }else{
                    $UpdateByUserCond = "";
                }

                if(!empty($SiteName)){
                    $SiteNameCond = "SiteName= '$SiteName',";
                }else{
                    $SiteNameCond = "";
                }

                if(!empty($LockedButSurvey)){
                    $LockedButSurveyCond = "LockedButSurvey= '$LockedButSurvey',";
                }else{
                    $LockedButSurveyCond = "";
                }

                if(!empty($Ward_No) && $Ward_No >0){
                    $Ward_NoCond = "Ward_No= $Ward_No ";
                }else{
                    $Ward_NoCond = "";
                }

            }


            $sql = "UPDATE $DBName..DW_VotersInfo SET 
                        UpdatedStatus = 'Y',
                        HStatus = '$Hstatus',
                        SStatus = '$Sstatus',
                        $ReligionCond
                        $SubCasteCond
                        SF='1',
                        $VidhanSabhaCond
                        $MajorIssuesCond
                        $SocietynameCond
                        $SocietyNameMCond
                        $Subloc_cdCond
                        $AnniversaryDateCond
                        $RemarkCond
                        $RoomnoCond
                        $MobilenoCond
                        $OwnerNameCond
                        $OwnerMobileNoCond
                        $DistrictCond
                        $LivingyearCond
                        $LPNOCond
                        $AndroidFormNoCond
                        $UpdatedDateCond
                        $UpdateByUserCond
                        QC_UpdatedDate=GETDATE(),
                        QC_UpdateByUser='$Executive_Cd',
                        QC_Done='1',
                        $SiteNameCond
                        $LockedButSurveyCond
                        $Ward_NoCond
                    WHERE Voter_Cd = $MasterVoter_Cd ; ";

            $DwVotersInfoUpdate = $db->RunQueryData($ULB,$sql, $userName, $appName, $developmentMode);

    // --------------FAMILY UPDATE------------------------------------------------------------------------
            $sql2 = "UPDATE $DBName..DW_VotersInfo SET 
                        UpdatedStatus = 'Y',
                        HStatus = '$Hstatus',
                        SStatus = '$Sstatus',
                        $ReligionCond
                        $SubCasteCond
                        SF='1',
                        $SocietynameCond
                        $SocietyNameMCond
                        $Subloc_cdCond
                        $RoomnoCond
                        $MobilenoCond
                        $OwnerNameCond
                        $OwnerMobileNoCond
                        $DistrictCond
                        QC_UpdatedDate=GETDATE(),
                        QC_UpdateByUser='$Executive_Cd',
                        QC_Done='1',
                        $SiteNameCond
                        $Ward_NoCond
                    WHERE Voter_Cd IN ($VoterCds) AND SF = '0'; ";

            $DwVotersFamilyInfoUpdate = $db->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);
                    
            // $DwVotersInfoUpdate = true;

            if($DwVotersInfoUpdate){
				
                $IfNotExistCreateQuery = "IF NOT EXISTS (SELECT 1 FROM $DBName.sys.tables WHERE name = 'NewVoterRegistrationDeleted') 	
                    CREATE TABLE [$DBName].[dbo].[NewVoterRegistrationDeleted](
                        [Voter_Cd] [int] NOT NULL,
                        [Name] [nvarchar](255) NULL,
                        [NameM] [nvarchar](255) NULL,
                        [Middlename] [nvarchar](255) NULL,
                        [MiddlenameM] [nvarchar](255) NULL,
                        [Surname] [nvarchar](255) NULL,
                        [SurnameM] [nvarchar](255) NULL,
                        [Age] [int] NULL,
                        [Education] [nvarchar](255) NULL,
                        [Birthdate] [datetime] NULL,
                        [Livingyear] [nvarchar](255) NULL,
                        [Mobileno] [nvarchar](255) NULL,
                        [Societyname] [nvarchar](255) NULL,
                        [Subloc_cd] [int] NULL,
                        [Remark] [nvarchar](255) NULL,
                        [Roomno] [nvarchar](255) NULL,
                        [Fullname] [nvarchar](255) NULL,
                        [FullnameM] [nvarchar](255) NULL,
                        [Occupation] [nvarchar](255) NULL,
                        [UpdatedStatus] [nvarchar](255) NULL,
                        [OwnerName] [nvarchar](255) NULL,
                        [OwnerAddress] [nvarchar](255) NULL,
                        [RefVoterID] [nvarchar](50) NULL,
                        [RefVoterListNo] [nvarchar](50) NULL,
                        [LPNO] [int] NULL,
                        [ServerUpdatedFlag] [nvarchar](255) NULL,
                        [Ward_No] [int] NULL,
                        [UpdatedDate] [datetime] NULL,
                        [UpdateByUser] [nvarchar](50) NULL,
                        [Sex] [nvarchar](10) NULL,
                        [OldWard_No] [nvarchar](50) NULL,
                        [MarNmar_det] [nvarchar](50) NULL,
                        [ControlChartWard] [int] NULL,
                        [LockedButSurvey] [nvarchar](50) NULL,
                        [HouseStatus_Cd] [int] NULL,
                        [Hstatus] [nvarchar](10) NULL,
                        [OwnerMobileNo] [nvarchar](50) NULL,
                        [District] [nvarchar](50) NULL,
                        [Ac_No] [int] NULL,
                        [Col1] [nvarchar](50) NULL,
                        [Col2] [nvarchar](50) NULL,
                        [Col3] [nvarchar](50) NULL,
                        [Col4] [nvarchar](50) NULL,
                        [Col5] [nvarchar](50) NULL,
                        [MarNmar] [nvarchar](50) NULL,
                        [Religion] [nvarchar](50) NULL,
                        [SubCaste] [nvarchar](50) NULL,
                        [AndroidFormNo] [bigint] NULL,
                        [FamilyNo] [bigint] NULL,
                        [AnniversaryDate] [date] NULL,
                        [StayOutside] [bit] NULL,
                        [FamilyCount] [int] NULL,
                        [VidhanSabha] [int] NULL,
                        [Site_Cd] [int] NULL,
                        [SiteName] [nvarchar](50) NULL,
                        [DSK_UpdatedByUser] [nvarchar](50) NULL,
                        [DSK_UpdatedDate] [datetime] NULL,
                        [QC_UpdatedDate] [datetime] NULL,
                        [QC_UpdateByUser] [nvarchar](50) NULL,
                        [QC_Done] [bit] NULL,
                        [Survey_Society_Cd] [int] NULL,
                        [Voter_Id] [int] NULL,
                        [List_No] [int] NULL,
                        [MajorIssues] [nvarchar](50) NULL,
                        [Bpl] [nvarchar](12) NULL,
                        [BloodGroup] [nvarchar](50) NULL,
                        [FavParty] [nvarchar](10) NULL,
                        [ShiftedStatus_Cd] [int] NULL,
                        [Sstatus] [nvarchar](10) NULL,
                        [Dead] [int] NULL,
                        [VMark_Cd] [int] NULL,
                        [VMarkName] [nvarchar](10) NULL,
                        [Email] [nvarchar](100) NULL,
                        [Mobile] [nvarchar](12) NULL,
                        [VoterDone] [int] NULL,
                        [SF] [int] NULL,
                        [Srno] [bigint] NULL,
                        [Leader_Cd] [int] NULL,
                        [Leader] [nvarchar](100) NULL,
                        [Party_Cd] [int] NULL,
                        [Party] [nvarchar](50) NULL,
                        [Remark2] [nvarchar](255) NULL,
                        [Remark3] [nvarchar](255) NULL,
                        [Remark4] [nvarchar](255) NULL,
                        [SubLocationNo] [int] NULL,
                        [SocietyNameM] [nvarchar](255) NULL,
                        [NoOfVisits] [nvarchar](50) NULL,
                        [PhoneNo] [nvarchar](11) NULL,
                        [Longitude] [nvarchar](150) NULL,
                        [Latitude] [nvarchar](150) NULL,
                        [Altitude] [nvarchar](150) NULL,
                        [Nmar] [nvarchar](50) NULL,
                        [Nmar_Det] [nvarchar](50) NULL,
                        [AddedDate] [datetime] NULL,
                        [BoothName] [nvarchar](255) NULL,
                        [BoothNameMar] [nvarchar](255) NULL,
                        [BoothRoomNo] [int] NULL,
                        [BootKendraNo] [int] NULL,
                        [Comitee_Cd] [int] NULL,
                        [Comitee] [nvarchar](100) NULL,
                        [City_Cd] [int] NULL,
                        [City] [nvarchar](100) NULL,
                        [NewVoterFlag] [nvarchar](10) NULL,
                        [Recieved_Status] [int] NULL,
                        [Category_Cd] [int] NULL,
                        [QC_Calling_UpdatedDate] [datetime] NULL,
                        [QC_Calling_Status_Cd] [int] NULL,
                        [SMS_UpdatedDate] [datetime] NULL,
                        [SMS_Flag] [int] NULL,
                        [QC_Calling_UpdatedByUser] [varchar](15) NULL,
                        [QC_Birthday_Wish_updatedDate] [datetime] NULL,
                        [QC_Birthday_Wish_Status] [varchar](50) NULL,
                        [QC_Birthday_Wish_UpdatedByUser] [varchar](50) NULL,
                        [SurveyDate_2018] [datetime] NULL,
                        [QC_Calling_Recording] [varchar](255) NULL,
                        [QC_Birthday_Recording] [varchar](255) NULL,
                        [QC_Remark] [nvarchar](255) NULL,
                        [QC_Round1] [int] NULL,
                        [QC_Round2] [int] NULL,
                        [QC_Round3] [int] NULL,
                        [Font_Cd] [int] NULL,
                        [BD_Call_Executive_Cd] [int] NULL,
                        [BD_Call_Temp_Exe_Cd] [int] NULL,
                        [SR_Call_Executive_Cd] [int] NULL,
                        [SR_Call_Temp_Exe_Cd] [int] NULL,
                        [BD_Call_Assign_Date] [datetime] NULL,
                        [SR_Call_Assign_Date] [datetime] NULL,
                        [NewRoomNo] [varchar](50) NULL
                    );";

                $IfNotExistCreateQueryExecute = $db->RunQueryData($ULB,$IfNotExistCreateQuery, $userName, $appName, $developmentMode);
				
                $sql1 = "INSERT INTO $DBName..NewVoterRegistrationDeleted (Voter_Cd, Name, NameM, Middlename, MiddlenameM
                    ,Surname ,SurnameM, Age, Education, Birthdate, Livingyear, Mobileno, Societyname, Subloc_cd, Remark, Roomno, Fullname, FullnameM, Occupation, UpdatedStatus
                    ,OwnerName, OwnerAddress, RefVoterID, RefVoterListNo, LPNO, ServerUpdatedFlag, Ward_No, UpdatedDate, UpdateByUser, Sex, OldWard_No, MarNmar_det
                    ,ControlChartWard, LockedButSurvey, HouseStatus_Cd, Hstatus, OwnerMobileNo, District, Ac_No, Col1, Col2, Col3, Col4, Col5, MarNmar, Religion, SubCaste
                    ,AndroidFormNo, FamilyNo, AnniversaryDate, StayOutside, FamilyCount, VidhanSabha, Site_Cd, SiteName, DSK_UpdatedByUser, DSK_UpdatedDate
                    ,QC_UpdatedDate, QC_UpdateByUser, QC_Done, Survey_Society_Cd, Voter_Id, List_No, MajorIssues, Bpl, BloodGroup, FavParty, ShiftedStatus_Cd
                    ,Sstatus, Dead, VMark_Cd, VMarkName, Email, Mobile, VoterDone, SF, Srno, Leader_Cd, Leader, Party_Cd, Party, Remark2, Remark3, Remark4
                    ,SubLocationNo, SocietyNameM, NoOfVisits, PhoneNo, Longitude, Latitude, Altitude, Nmar, Nmar_Det, AddedDate, BoothName, BoothNameMar
                    ,BoothRoomNo, BootKendraNo, Comitee_Cd, Comitee, City_Cd, City, NewVoterFlag, Recieved_Status, Category_Cd, QC_Calling_UpdatedDate
                    ,QC_Calling_Status_Cd, SMS_UpdatedDate, SMS_Flag, QC_Calling_UpdatedByUser, QC_Birthday_Wish_updatedDate, QC_Birthday_Wish_Status
                    ,QC_Birthday_Wish_UpdatedByUser, SurveyDate_2018, QC_Calling_Recording, QC_Birthday_Recording, QC_Remark, QC_Round1, QC_Round2, QC_Round3, Font_Cd
                    ,BD_Call_Executive_Cd, BD_Call_Temp_Exe_Cd, SR_Call_Executive_Cd, SR_Call_Temp_Exe_Cd, BD_Call_Assign_Date, SR_Call_Assign_Date, NewRoomNo)
                SELECT Voter_Cd, Name, NameM, Middlename, MiddlenameM
                    ,Surname ,SurnameM, Age, Education, Birthdate, Livingyear, Mobileno, Societyname, Subloc_cd, Remark, Roomno, Fullname, FullnameM, Occupation, UpdatedStatus
                    ,OwnerName, OwnerAddress, RefVoterID, RefVoterListNo, LPNO, ServerUpdatedFlag, Ward_No, UpdatedDate, UpdateByUser, Sex, OldWard_No, MarNmar_det
                    ,ControlChartWard, LockedButSurvey, HouseStatus_Cd, Hstatus, OwnerMobileNo, District, Ac_No, Col1, Col2, Col3, Col4, Col5, MarNmar, Religion, SubCaste
                    ,AndroidFormNo, FamilyNo, AnniversaryDate, StayOutside, FamilyCount, VidhanSabha, Site_Cd, SiteName, DSK_UpdatedByUser, DSK_UpdatedDate
                    ,QC_UpdatedDate, QC_UpdateByUser, QC_Done, Survey_Society_Cd, Voter_Id, List_No, MajorIssues, Bpl, BloodGroup, FavParty, ShiftedStatus_Cd
                    ,Sstatus, Dead, VMark_Cd, VMarkName, Email, Mobile, VoterDone, SF, Srno, Leader_Cd, Leader, Party_Cd, Party, Remark2, Remark3, Remark4
                    ,SubLocationNo, SocietyNameM, NoOfVisits, PhoneNo, Longitude, Latitude, Altitude, Nmar, Nmar_Det, AddedDate, BoothName, BoothNameMar
                    ,BoothRoomNo, BootKendraNo, Comitee_Cd, Comitee, City_Cd, City, NewVoterFlag, Recieved_Status, Category_Cd, QC_Calling_UpdatedDate
                    ,QC_Calling_Status_Cd, SMS_UpdatedDate, SMS_Flag, QC_Calling_UpdatedByUser, QC_Birthday_Wish_updatedDate, QC_Birthday_Wish_Status
                    ,QC_Birthday_Wish_UpdatedByUser, SurveyDate_2018, QC_Calling_Recording, QC_Birthday_Recording, QC_Remark, QC_Round1, QC_Round2, QC_Round3, Font_Cd
                    ,BD_Call_Executive_Cd, BD_Call_Temp_Exe_Cd, SR_Call_Executive_Cd, SR_Call_Temp_Exe_Cd, BD_Call_Assign_Date, SR_Call_Assign_Date, NewRoomNo
                FROM $DBName..NewVoterRegistration
                WHERE Voter_Cd = $NonvVoterVoter_Cd;";
                
                $NewVotRegDeletedAdd = $db->RunQueryData($ULB,$sql1, $userName, $appName, $developmentMode);
                // $NewVotRegDeletedAdd = '';

                if($NewVotRegDeletedAdd){
                    $sql2 = "DELETE FROM $DBName..NewVoterRegistration
                    WHERE Voter_Cd = $NonvVoterVoter_Cd;";

                    $NewVotDeleteQuery = $db->RunQueryData($ULB,$sql2, $userName, $appName, $developmentMode);
                    // $NewVotDeleteQuery = '';

                    if($NewVotDeleteQuery){
                        echo json_encode(array('statusCode' => 200, 'msg' => "Updated Successfully!", 'url' => $redirectURL));
                        unset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']);
                        unset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']);
                        unset($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details']);
                        unset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']);
                        unset($_SESSION['SurveyUA_pagetype_SurveyQC_Details']);
                    }
                    else{
                        echo json_encode(array('statusCode' => 204, 'msg' => 'Entry Updated successfully, could not delete from NewVoterRegistration'));
                    }
                }else{
                    echo json_encode(array('statusCode' => 204, 'msg' => 'Entry Updated successfully, could not update in NewVoterRegistration'));
                }
            }else{
                echo json_encode(array('statusCode' => 204, 'msg' => 'Unable to Update, please try again'));
            }
        }else{
            echo json_encode(array('statusCode' => 204, 'msg' => 'Could not fetch DB Name, Please try again'));
        }
    }
    // else{
    //     echo json_encode(array('statusCode' => 204, 'msg' => 'Required parameters are missing, Please try again'));
    // }
}

?>