<?php
// session_start();
// include 'api/includes/DbOperation.php'; 


$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];



$FirstName = '';
$MiddleName = '';
$LastName = '';
$IdCard_No= '';
$List_No= '';
$AdvanceSearch= '';
$DBName = '';
$FullName = '';
$FamilyNo = 0;
$Ac_No = 0;
$Voter_Id = 0;
$FamilyTblConditionVariable = 0;

$NonVoterFamilyList = array();
$NonVoterSearchList = array();


$BackButton = "<button class='btn btn-danger btn-block' title='Back' onclick='history.back()'>
                <i class='feather icon-arrow-left'></i>
            </button>";

// if(
//     (isset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionName_SurveyQC_Details'])) &&
//     (isset($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'])) &&
//     (isset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'])) 
// ){
//     $electionName = $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'];
//     $electionCd = $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'];
//     $Society_Cd = $_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'];
//     $pagetype = $_SESSION['SurveyUA_pagetype_SurveyQC_Details'];

//     if($pagetype == 'Card'){
//         $redirectURL = "-Card&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
//     }else{
//         $redirectURL = "&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
//     }

//     $backurl = "index.php?p=Survey-QC-Details".$redirectURL;

//     $BackButton = "<a href='".$backurl."' style='text-decoration: none;'>
//                         <button class='btn btn-danger btn-block' title='Back'>
//                             <i class='feather icon-arrow-left'></i>
//                         </button>
//                     </a>";
// }

$GetAc_No = 0;

if(
    isset($_SESSION['SurveyUA_FirstName_SurveyQC_Edit']) && 
    isset($_SESSION['SurveyUA_MiddleName_SurveyQC_Edit']) && 
    isset($_SESSION['SurveyUA_LastName_SurveyQC_Edit']) &&
    (isset($_SESSION['SurveyUA_VoterCd_SurveyQC_Edit']) && !empty($_SESSION['SurveyUA_VoterCd_SurveyQC_Edit'])) &&
    (isset($_SESSION['SurveyUA_SubLocationCd_SurveyQC_Edit']) && !empty($_SESSION['SurveyUA_SubLocationCd_SurveyQC_Edit'])) &&
    (isset($_SESSION['SurveyUA_DBName_SurveyQC_Edit']) && !empty($_SESSION['SurveyUA_DBName_SurveyQC_Edit'])) &&
    (isset($_SESSION['SurveyUA_RoomNo_SurveyQC_Edit'])) 
    
) 
{

    $Voter_CdNonVoter = $_SESSION['SurveyUA_VoterCd_SurveyQC_Edit'];
    $SubLocation_Cd = $_SESSION['SurveyUA_SubLocationCd_SurveyQC_Edit'];
    $RoomNo = $_SESSION['SurveyUA_RoomNo_SurveyQC_Edit'];

    $DBName = $_SESSION['SurveyUA_DBName_SurveyQC_Edit'];

    $FirstName = $_SESSION['SurveyUA_FirstName_SurveyQC_Edit'];
    $MiddleName = $_SESSION['SurveyUA_MiddleName_SurveyQC_Edit'];
    $LastName = $_SESSION['SurveyUA_LastName_SurveyQC_Edit'];


    $FullName = $LastName . " " . $FirstName . " " . $MiddleName;

    $FirstName = substr($FirstName, 0, 3);
    $MiddleName = substr($MiddleName, 0, 3);
    $LastName = substr($LastName, 0, 3);

    $sql4 = "SELECT 
                t1.*, 
                em.ExecutiveName, 
                um.Mobile 
                FROM 
                (
                    SELECT 
                        dw.Voter_Cd,dw.Ac_No, dw.List_No, dw.Voter_Id,dw.FullName,dw.Name,dw.MiddleName,dw.Surname,dw.SocietyName, dw.Col4 AS  Floor, dw.IdCard_No AS IdCard_No, dw.Remark,
                        dw.Hstatus, dw.VidhanSabha,
                        --CONVERT(VARCHAR, CONVERT(DATE, BirthDate, 101), 105) AS BirthDate,
                        CONVERT(VARCHAR,BirthDate, 105) AS BirthDate,
                        Age, 
                        Sex, MobileNo, CONVERT(VARCHAR,dw.UpdatedDate,0) AS UpdatedDate, dw.UpdateByUser, RoomNo FROM $DBName..SubLocationMaster AS sb 
                    INNER JOIN $DBName..Dw_VotersInfo AS dw on (dw.Sublocation_Cd = sb.SubLocation_Cd) 
                    WHERE SF = 1 AND dw.RoomNo = '$RoomNo' AND sb.SubLocation_Cd = $SubLocation_Cd
                    UNION  
                    SELECT 
                        dw.Voter_Cd,dw.Ac_No, dw.List_No, dw.Voter_Id,dw.FullName,dw.Name,dw.Middlename,dw.Surname,dw.SocietyName,  dw.Col4 AS Floor, '' AS IdCard_No, dw.Remark,
                        dw.Hstatus, dw.VidhanSabha,
                        --CONVERT(VARCHAR, CONVERT(DATE, BirthDate, 101), 105) AS BirthDate, 
                        CONVERT(VARCHAR,BirthDate, 105) AS BirthDate,
                        Age, 
                        Sex, MobileNo, CONVERT(VARCHAR,dw.UpdatedDate,0) AS UpdatedDate, dw.UpdateByUser, RoomNo from $DBName..SubLocationMaster AS sb 
                    INNER JOIN $DBName..NewVoterRegistration AS dw on (dw.Subloc_cd = sb.SubLocation_Cd) 
                    WHERE dw.RoomNo = '$RoomNo' AND sb.SubLocation_Cd = $SubLocation_Cd
                ) AS t1 
                LEFT JOIN Survey_Entry_Data..User_Master AS um ON (t1.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI)
                LEFT JOIN Survey_Entry_Data..Executive_Master AS em ON (um.Executive_Cd = em.Executive_Cd)
                GROUP BY t1.Voter_Cd,t1.Ac_No, t1.List_No, t1.Voter_Id,t1.FullName,t1.Name,t1.MiddleName,t1.Surname,
                        t1.SocietyName, t1.Floor, t1.IdCard_No, t1.Remark, t1.Hstatus, t1.VidhanSabha, t1.BirthDate,
                        t1.Age, t1.Sex, t1.MobileNo, t1.UpdatedDate, t1.UpdateByUser, t1.RoomNo, em.ExecutiveName, um.Mobile ;";
// die();
    $result4 = $db->ExecutveQueryMultipleRowSALData($ULB,$sql4 , $userName, $appName, $developmentMode);

	
    if(sizeof($result4) > 0){
        $GetAc_No = $result4[0]['Ac_No'];
    }
}

 

if(
    (isset($_SESSION['SurveyUA_FamilyNo_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_FamilyNo_SurveyQC_Details'])) &&
    (isset($_SESSION['SurveyUA_AcNo_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_AcNo_SurveyQC_Details'])) &&
    (isset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']))
){

    // echo "here";
    $FamilyNo = $_SESSION['SurveyUA_FamilyNo_SurveyQC_Details'];
    $Ac_No = $_SESSION['SurveyUA_AcNo_SurveyQC_Details'];
    $Voter_Cd = $_SESSION['SurveyUA_VoterCd_SurveyQC_Details'];

    $FirstName = $_SESSION['SurveyUA_FirstName_SurveyQC_Details'];
    $MiddleName = $_SESSION['SurveyUA_MiddleName_SurveyQC_Details'];
    $LastName = $_SESSION['SurveyUA_LastName_SurveyQC_Details'];

    $AdvanceSearch = $_SESSION['SurveyUA_AdvanceSearch_SurveyQC_Details'];
    $IdCard_No = $_SESSION['SurveyUA_IdCard_No_SurveyQC_Details'];
    $List_No = $_SESSION['SurveyUA_List_No_SurveyQC_Details'];

    $FamilyTblConditionVariable = 1;

    

    unset($_SESSION['SurveyUA_FamilyNo_SurveyQC_Details']);
    unset($_SESSION['SurveyUA_AcNo_SurveyQC_Details']);
    // unset($_SESSION['SurveyUA_VoterCd_SurveyQC_Details']);
}


if(
    (isset($_SESSION['SurveyUA_ElectionName_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionName_SurveyQC_Details'])) &&
    (isset($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details']) && !empty($_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'])) 
){
    $electionName = $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'];
    $electionCd = $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'];

    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
}

if(!empty($DBName)){

    $query1 = "SELECT top 20 
    COALESCE(SF, 0) AS SF,
    COALESCE(Voter_Cd, 0) AS Voter_Cd,
    COALESCE(FamilyNo, 00) AS FamilyNo,
    COALESCE(Ac_No, 0) AS Ac_No,
    COALESCE(List_No, 0) AS List_No,
    COALESCE(Voter_Id, 0) AS Voter_Id,
    COALESCE(NewVoter_Id, 0) AS NewVoter_Id,
    COALESCE(FullName, '') AS FullName,
    COALESCE(FullNameMar, '') AS FullNameMar,
    COALESCE(SocietyName, '') AS SocietyName,
    COALESCE(SocietyNameM, '') AS SocietyNameM,
    COALESCE(Sex, '') AS Sex,
    COALESCE(Age, 0) AS Age,
    COALESCE(RoomNo, '') AS RoomNo,
    COALESCE(Mobileno, '') AS Mobileno,
    COALESCE(BirthDate, '') AS BirthDate,
    COALESCE(Livingyear, '') AS Livingyear,
    COALESCE(HouseStatus_Cd, 0) AS HouseStatus_Cd,
    COALESCE(HStatus, '') AS HStatus,
    COALESCE(ShiftedStatus_Cd, 0) AS ShiftedStatus_Cd,
    COALESCE(SStatus, '') AS SStatus,
    COALESCE(VidhanSabha, 0) AS VidhanSabha,
    COALESCE(Occupation_Cd, 0) AS Occupation_Cd,
    COALESCE(Occupation, '') AS Occupation,
    COALESCE(Education, '') AS Education,
    COALESCE(MajorIssues, '') AS MajorIssues,
    COALESCE(OwnerName, '') AS OwnerName,
    COALESCE(MarNmar_Det, '') AS MarNmar_Det,
    COALESCE(MarNmar, '') AS MarNmar,
    COALESCE(Remark, '') AS Remark,
    COALESCE(Email, '') AS Email,
    COALESCE(Dead, 0) AS Dead
    from $DBName..DW_VotersInfo 
    where QC_Done <> 1 AND Surname like '$LastName%' and  Name like '$FirstName%'  and  MiddleName like '$MiddleName%'";

    // echo $query1;
    $NonVoterSearchList = $db->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);

    if($FamilyTblConditionVariable == 1){
        $query2 = "SELECT 
            COALESCE(Voter_Cd, 0) AS Voter_Cd, 
            COALESCE(Ac_No, 0) AS Ac_No, 
            COALESCE(Ward_No, 0) AS Ward_No, 
            COALESCE(List_No, 0) AS List_No, 
            COALESCE(Voter_Id, 0) AS Voter_Id, 
            COALESCE(FamilyNo, 0) AS FamilyNo, 
            COALESCE(SubLocation_Cd, 0) AS SubLocation_Cd, 
            COALESCE(SocietyName, '') AS SocietyName, 
            COALESCE(FullName, '') AS FullName, 
            COALESCE(RoomNo, '') AS RoomNo, 
            COALESCE(Sex, '') AS Sex, 
            COALESCE(Age, 0) AS Age, 
            COALESCE(MarNmar, '') AS MarNmar, 
            COALESCE(MarNmar_Det, '') AS MarNmar_Det, 
            COALESCE(SF, 0) AS SF, 
            COALESCE(MobileNo, '') AS MobileNo,  
            COALESCE(BirthDate, '') AS BirthDate, 
            COALESCE(Livingyear , '') AS Livingyear, 
            COALESCE(VidhanSabha, 0) AS VidhanSabha, 
            COALESCE(Occupation, '') AS Occupation, 
            COALESCE(Education, '') AS Education, 
            COALESCE(HStatus, '') AS HStatus, 
            COALESCE(SStatus, '') AS SStatus, 
            COALESCE(MajorIssues, '') AS MajorIssues, 
            COALESCE(OwnerName, '') AS OwnerName, 
            COALESCE(Remark, '') AS Remark, 
            COALESCE(LPNO, 0) AS LPNO, 
            COALESCE(Religion, '') AS Religion, 
            COALESCE(SubCaste, '') AS SubCaste, 
            COALESCE(LockedButSurvey, '') AS LockedButSurvey, 
            COALESCE(OwnerMobileNo, '') AS OwnerMobileNo, 
            COALESCE(District, '') AS District, 
            COALESCE(AnniversaryDate, ' ') AS AnniversaryDate 
            From $DBName..Dw_VotersInfo 
            where FamilyNo = $FamilyNo and Ac_No= $Ac_No
            ORDER BY Age DESC";

        // echo $query1;
        $NonVoterFamilyList = $db->ExecutveQueryMultipleRowSALData($ULB,$query2, $userName, $appName, $developmentMode);
    }
 
    // print_r("<pre>");
    // print_r($result4);
    // print_r("</pre>");
}


    


?>


<style>
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 7px;
    }

    .table td,
    .table th {
        padding: 0.75px;
        margin: 0;
    }

    .table th {
        background-color:#36abb9 ;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
        }

    .table tr {
        padding: 0;
        margin: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    td {
        border: 1px solid grey;
        padding: 8px;
    }
</style>

<div class="row match-height mb-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-1" id="">
                <h4 class="card-title">Room Details - <?php echo $result4[0]['SocietyName']?></h4>
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;height: auto;">
                                <thead>
                                <tr>
                                    <th  style=''>SrNo</th>
                                    <!-- <th  style=''>Action</th> -->
                                    <th  style=''>Corp No</th>
                                    <th  style=''>ID Card</th>
                                    <th  style=''>Full Name</th>
                                    <th  style=''>Floor No</th>
                                    <th  style=''>Room No</th>
                                    <th  style=''>Hstatus</th>
                                    <th  style=''>Voted</th>
                                    <th  style=''>Birthdate</th>
                                    <th  style=''>Age</th>
                                    <th  style=''>Sex</th>
                                    <th  style=''>Mobile No</th>
                                    <th  style=''>Remark</th>
                                    <th  style=''>Updated Date</th>
                                    <th  style=''>Updated By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($result4) > 0){
                                        $srNo = 1;
                                        foreach($result4 AS $Key=>$value){  
                                        ?>
                                        <tr <?php if($value['List_No'] == '0' && ($value['Voter_Id'] == '0' || $value['Voter_Id'] == NULL)){ ?> style="background-color: #ffc0cb;" <?php } ?> >
                                            <td><?php echo $srNo++;if($value['Voter_Cd'] == $Voter_CdNonVoter){ ?><i class="fa fa-arrow-circle-right ml-1" Style="color:white;" aria-hidden="true"></i><?php } ?></td>
                                            
                                            <!-- <td style="text-align:center;">
                                                <?php //if($value['List_No'] == '0' && $value['Voter_Id'] == '0' && $value['Voter_Cd'] != $Voter_CdNonVoter){ ?>
                                                    <a class="" onclick="SurveyQCNonVoterEdit('<?php //echo $DBName ?>','<?php //echo $value['Voter_Cd'] ?>','<?php //echo $SubLocation_Cd ?>','<?php //echo $value['RoomNo'] ?>','<?php //echo $value['Name'] ?>', '<?php //echo $value['MiddleName'] ?>', '<?php // echo $value['Surname'] ?>')">
                                                        <b><i class="fa fa-pencil-square-o"></i></b>
                                                    </a>
                                                <?php //} ?>
                                            </td> -->

                                            <td>
                                                <?php if($value['List_No'] > 0 && $value['List_No'] > 0){echo $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id'];} ?>
                                            </td>
                                            <td><?php echo $value['IdCard_No']; ?></td>
                                            <td><?php echo $value['FullName']; ?></td>
                                            <td><?php echo $value['Floor']; ?></td>
                                            <td><?php echo $value['RoomNo']; ?></td>
                                            <td title="<?php if($value['Hstatus'] == 'O'){echo 'Owner';}elseif($value['Hstatus'] == 'R'){echo 'Rented';}else{echo $value['Hstatus'];} ?>" ><?php echo $value['Hstatus']; ?></td>
                                            <td><?php if($value['VidhanSabha'] == 1){echo "Yes";}elseif($value['VidhanSabha'] == 0){echo "No";}else{ echo $value['VidhanSabha']; }?></td>
                                            <td><?php echo $value['BirthDate']; ?></td>
                                            <td><?php echo $value['Age']; ?></td>
                                            <td><?php echo $value['Sex']; ?></td>
                                            <td><?php echo $value['MobileNo']; ?></td>
                                            <td><?php echo $value['Remark']; ?></td>
                                            <td><?php echo $value['UpdatedDate']; ?></td>
                                            <td><?php echo $value['ExecutiveName'] . " - ". $value['Mobile']; ?></td>
                                        </tr>
                                        <?php
                                        }
                                    }
                                ?>
                                </tbody>  
                            </table>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row match-height mt-0 mb-0" style="margin-top:-35px">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-1">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="card-title">Non Voter Search - <?php echo $FullName; ?></h4>
                    </div>
                </div>
            </div>
            <!-- card body starts -->
            <div class="card-body p-1" id="">
                <div class="row p-0 m-0">
                    <div class="col-sm-2">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="LastName" id="LastName" value="<?php echo $LastName ?>" placeholder="Surname"  onkeyup="surveyQCAdvanceSearchNew()" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            <label for="LastName">Surname</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="FirstName" id="FirstName" value="<?php echo $FirstName; ?>" placeholder="Firstname"  onkeyup="surveyQCAdvanceSearchNew()" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            <label for="FirstName">First Name</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="MiddleName" id="MiddleName" value="<?php echo $MiddleName?>" placeholder="Middlename"  onkeyup="surveyQCAdvanceSearchNew()" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            <label for="MidddleName">Middle Name </label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="DBName" id="DBName" value="<?php echo $DBName ?>" >
						<input type="hidden" name="GetAc_No" id="GetAc_No" value="<?php echo $GetAc_No ?>" >
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="AdvanceSearch" id="AdvanceSearch" value="<?php echo $AdvanceSearch; ?>" placeholder="Search Full Name" onkeyup="surveyQCAdvanceSearchNew()" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            <label for="AdvanceSearch">Advance Search</label>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="FullName" id="FullName" value="<?php echo $FullName ?>" >
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="IdCard_No" id="IdCard_No" value="<?php echo $IdCard_No?>" placeholder="Id Card No"  onkeyup="surveyQCAdvanceSearchNew()">
                            <label for="IdCard_No">ID Card No</label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-label-group in-border">
                            <input type="text" class="form-control" name="fuzzThreshold" id="fuzzThreshold" disabled value="80" placeholder="Fuzz Threshold"  onkeyup="surveyQCAdvanceSearchNew()">
                            <label for="fuzzThreshold">Fuzz Threshold</label>
                        </div>
                    </div>
                    <div class="col-sm-1" style="padding:0">
                        <?php echo $BackButton; ?>
                        <!-- <button class="btn btn-danger btn-block" title="Back" onclick="history.back()">
                            <i class="feather icon-arrow-left"></i>
                        </button> -->
                    </div>
                    <!-- <div class="col-sm-1 float-right">
                        <div class="form-label-group in-border">
                            <img src="app-assets/images/lock.svg" id="toggleButton" title="Advance Search" class=""  alt="" width="30x" height="30px">
                        </div>
                    </div> -->
                </div>
                <!-- <div class="row p-0 m-0" id="advanceSearchDiv"> -->
                <!-- </div> -->
                <div class="row">
                    <div class="col-md-12" style="align-items:center">
                        <center>
                            <div id='spinnerLoader2' style='display:none'>
                                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                            </div>
                        </center>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" style="height:250px;overflow-y:scroll; position:relative;text-align:center;align-items:center;">
                            <table class="table table-hover tblCustomHeight" height="250px" style="border:solid 1px black;height: 250px;overflow-y:scroll;">
                                <thead>
                                <tr>
                                    <th  style=''>Action</th>
                                    <th  style=''>Voter ID</th>
                                    <th  style=''>Full Name</th>
                                    <th  style=''>Age</th>
                                    <th  style=''>Sex</th>
                                    <th  style=''>Birthdate</th>
                                    <th  style=''>Mobile No</th>
                                    <th  style=''>Room No</th>
                                    <th  style=''>Hstatus</th>
                                    <!-- <th  style=''>Voted</th>
                                    <th  style=''>Remark</th> -->
                                    <th  style=''>Society Name</th>
                                </tr>
                                </thead>
                                <tbody id="tbodydiv">
                                <?php
                                    if(sizeof($NonVoterSearchList) > 0){
                                        // $srNo = 1;
                                        foreach($NonVoterSearchList AS $Key=>$value){  
                                        ?>
                                        <tr>
                                            <!-- <td><?php //echo $srNo++; ?></td> -->
                                            <td>
                                                <a class="" onclick="getSurveyQCNonVoterFamilyInSession(<?php echo $value['FamilyNo']?>,<?php echo $value['Ac_No']?>,<?php echo $value['Voter_Cd']?>)">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a> 
                                                <a class="" onclick="addFamilyList(<?php echo $value['Voter_Cd']?>)">
                                                    <i class="fa fa-plus-square-o"></i>
                                                </a>
                                            </td>
                                            <td style='width: 120px;'><?php echo $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id'] ?></td>
                                            <td style="width: 400px;word-wrap: break-word;"><?php echo $value['FullName']?></td>
                                            <td><?php echo $value['Age']?></td>
                                            <td><?php echo $value['Sex']?></td>
                                            <td><?php echo substr($value['BirthDate'], 0, 10);?></td>
                                            <td><?php echo $value['Mobileno']?></td>
                                            <td><?php echo $value['RoomNo']?></td>
                                            <td title="<?php if($value['HStatus'] == 'O'){echo 'Owner';}elseif($value['HStatus'] == 'R'){ echo 'Rented'; }else{ echo $value['HStatus']; } ?>" ><?php echo $value['HStatus']; ?></td>
                                            <!-- <td><?php //if($value['VidhanSabha'] == 1){echo "Yes";}elseif($value['VidhanSabha'] == 0){echo "No";}else{ echo $value['VidhanSabha']; }?></td>
                                            <td style="width: 100px;word-wrap: break-word;" ><?php //echo $value['Remark']?></td>-->
                                            <td style="width: 300px;word-wrap: break-word;"><?php echo $value['SocietyName']?></td>
                                        </tr>
                                        <?php
                                        }
                                    }else{ ?>
                                        <tr><td colspan="14">No Record Found</td></tr>
                                <?php 
                                    }
                                ?>
                                </tbody>  
                            </table>
                        </div>
                    </div>  
                </div>
                <?php if($FamilyTblConditionVariable == 1){?>
                <hr>
                <div class="row">
                    <div class="col-md-12" style="align-items:center">
                        <center>
                            <div id='spinnerLoader3' style='display:none'>
                                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                            </div>
                        </center>
                    </div>
                </div>
                <div class="row mt-1 p-1">
                    <div class="col-md-12">
                        <h5>Record with family members:</h5>
                    </div>
                    <div class="col-md-12">
                        <div class="tblCustomHeight" id="tblCustomHeight" style="position: relative;text-align: center;align-items: center;height: 400px;overflow:scroll;">
                            <table class="table table-hover" id="tblCustomCss" style="">
                                <thead>
                                    <tr class="">
                                        <th style=" width:30px;padding:5px 15px 15px 15px">&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input class="form-check-input checkbox_All" type="checkbox" style=" width: 20px; height: 20px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setSurveyQCFamilyALLIds(this)"  >
                                        </th>
                                        <th style="">Sr No</th>
                                        <th style="">SF</th>
                                        <th style="">Voter ID</th>
                                        <th style="">Full Name</th>
                                        <th style="">Age</th>
                                        <th style="">Sex</th>
                                        <th style="">Birthdate</th>
                                        <th style="">Mobile No</th>
                                        <th style="">Room</th>
                                        <th  style=''>Hstatus</th>
                                        <th  style=''>Voted</th>
                                        <th  style=''>Remark</th>
                                        <th style="">SocietyName</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($NonVoterFamilyList) > 0){
                                        $srNo = 1;
                                        foreach($NonVoterFamilyList AS $Key=>$value){  
                                        ?>
                                        <tr <?php if($Voter_Cd == $value['Voter_Cd']){ ?> style="color:#36abb9" <?php } ?> >
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input class="form-check-input checkbox" type="checkbox" style="width:30px;padding:5px 15px 15px 15px" value="<?php echo $value['Voter_Cd']?>,<?php echo $value['FullName']?>" id="AssignCheckbox" onclick="setSurveyQCNonVoterFamilyIds()" <?php if($value['SF'] == '1'){ ?> disabled <?php }?>>
                                            </td>
                                            <td><?php echo $srNo++; ?></td>
                                            <td><?php echo $value['SF'];?></td>
                                            <td><?php echo $value['Ac_No'] . " / " . $value['List_No'] . " / " . $value['Voter_Id']; ?></td>
                                            <td  style="width: 200px;word-wrap: break-word;" ><?php echo $value['FullName'];?></td>
                                            <td><?php echo $value['Age'];?></td>
                                            <td><?php echo $value['Sex']; ?></td>
                                            <td><?php echo substr($value['BirthDate'], 0, 10);?></td>
                                            <td><?php echo $value['MobileNo'];?></td>
                                            <td><?php echo $value['RoomNo'];?></td>
                                            <td title="<?php if($value['HStatus'] == 'O'){echo 'Owner';}elseif($value['HStatus'] == 'R'){echo 'Rented';}else{echo $value['HStatus'];} ?>" ><?php echo $value['HStatus']; ?></td>
                                            <td><?php if($value['VidhanSabha'] == 1){echo "Yes";}elseif($value['VidhanSabha'] == 0){echo "No";}else{ echo $value['VidhanSabha']; }?></td>
                                            <td style="width: 100px;word-wrap: break-word;" ><?php echo $value['Remark']?></td>
                                            <td  style="width:250px;word-wrap:break-word;" ><?php echo $value['SocietyName'];?></td>
                                        </tr>
                                        <?php
                                        }
                                    }
                                ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-1 mb-1">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                        <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                        <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse m-2">
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-block" id="" onclick="saveSurveyQCNonVoterToVoter(<?php echo $Voter_CdNonVoter; ?>)">
                            Update
                        </button>
                        <!-- </a> -->
                    </div>
                    <div class="col-sm-8">
                        <input class="form-control form-control-sm basic" type="" name="VoterCds"> 
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Get the input field
    var FirstNameenterEvent = document.getElementById("FirstName");

    // Execute a function when the user presses a key on the keyboard
    FirstNameenterEvent.addEventListener("keypress", function(event) {
        // If the user presses the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("SocietySurveyQCSearchEngine").click();
        }
    });

    // Get the input field
    var MidddleNameenterEvent = document.getElementById("MiddleName");

    // Execute a function when the user presses a key on the keyboard
    MidddleNameenterEvent.addEventListener("keypress", function(event) {
        // If the user presses the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("SocietySurveyQCSearchEngine").click();
        }
    });

    // Get the input field
    var LastNameenterEvent = document.getElementById("LastName");

    // Execute a function when the user presses a key on the keyboard
    LastNameenterEvent.addEventListener("keypress", function(event) {
        // If the user presses the "Enter" key on the keyboard
        if (event.key === "Enter") {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("SocietySurveyQCSearchEngine").click();
        }
    });

    var toggleButton = document.getElementById('toggleButton');
    var advanceSearchDiv = document.getElementById('advanceSearchDiv');

    toggleButton.addEventListener('click', function() {
        if (advanceSearchDiv.style.display === 'none') {
            advanceSearchDiv.style.display = 'block'; // Show the div
            toggleButton.src = 'app-assets/images/unlock.svg'; // Update the image src to unlock.svg
            toggleButton.alt = 'Unlock Icon';
        } else {
            advanceSearchDiv.style.display = 'none'; // Hide the div
            toggleButton.src = 'app-assets/images/lock.svg'; // Update the image src to lock.svg
            toggleButton.alt = 'Lock Map';
        }
    });



    function addFamilyList(Voter_cd){
        // alert(Voter_cd);
    const inputField = document.querySelector('input[name="VoterCds"]');
    if (inputField.value) {
        inputField.value +=  Voter_cd+","; // Append with a comma separator
    } else {
        inputField.value = Voter_cd+","; // Set the value if the field is empty
    }
}
</script>

<?php
unset($_SESSION['SurveyUA_FirstName_SurveyQC_Details']);
unset($_SESSION['SurveyUA_MiddleName_SurveyQC_Details']);
unset($_SESSION['SurveyUA_LastName_SurveyQC_Details']);


// if(isset($_SESSION['SurveyUA_FamilyNo_SurveyQC_Details']))

?>