<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php
// echo "<pre>"; print_r($_SESSION);exit;
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

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

$NonVoterFamilyList = array();
$NonVoterSearchList = array();


$BackButton = "<button class='btn btn-danger btn-block' title='Back' onclick='history.back()'>
                <i class='feather icon-arrow-left'></i>
            </button>";

$GetAc_No = 188;
// $DBName = '[PT188_MemberList]';
$dataElectionName = $db->getSurveyUtilityCorporationElectionData($ULB,$userName, $appName, $developmentMode);
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

    // $FirstName = substr($FirstName, 0, 3);
    // $MiddleName = substr($MiddleName, 0, 3);
    // $LastName = substr($LastName, 0, 3);

    

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
                INNER JOIN Survey_Entry_Data..User_Master AS um ON (t1.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI)
                INNER JOIN Survey_Entry_Data..Executive_Master AS em ON (um.Executive_Cd = em.Executive_Cd)
                GROUP BY t1.Voter_Cd,t1.Ac_No, t1.List_No, t1.Voter_Id,t1.FullName,t1.Name,t1.MiddleName,t1.Surname,
                        t1.SocietyName, t1.Floor, t1.IdCard_No, t1.Remark, t1.Hstatus, t1.VidhanSabha, t1.BirthDate,
                        t1.Age, t1.Sex, t1.MobileNo, t1.UpdatedDate, t1.UpdateByUser, t1.RoomNo, em.ExecutiveName, um.Mobile ;";

    $result4 = $db->ExecutveQueryMultipleRowSALData($ULB,$sql4 , $userName, $appName, $developmentMode);
    if(sizeof($result4) > 0){
        $GetAc_No = $result4[0]['Ac_No'];
    }
}


if(
    (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName'])) 
){
    // print_r("Gauriiii");
    $electionName = $_SESSION['SurveyUA_ElectionName'];
    $electionCd = $_SESSION['SurveyUA_Election_Cd'];

    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);
}else{
    $DBName = "";
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

    $NonVoterSearchList = $db->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);

   

}

// print_r($DBName);
?>
<div class="row match-height mt-0 mb-0" style="margin-top:-35px">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-1" id="">
                <form action="#" id = "searchVoters">
                    <div class="row p-0 m-0">
                        <div class="col-sm-2">
                            <?php //include 'dropdown-electionname.php'; ?>
                            <div class="form-group">
                                <label>Corporation</label>
                                <div class="controls">
                                    <select class="select2 form-control" name="electionName" onChange="setElectionNameInSession(this.value)" >
                                        <?php
                                        if (sizeof($dataElectionName)>0) 
                                        {
                                            foreach ($dataElectionName as $key => $value) 
                                            {
                                                if($_SESSION['SurveyUA_Election_Cd'] == $value["Election_Cd"])
                                                {
                                        ?>
                                                    <option selected="true" value="<?php echo $value['Election_Cd']; ?>"><?php echo $value["ElectionName"]; ?></option>
                                        <?php
                                                }
                                                else
                                                {
                                        ?>
                                                    <option value="<?php echo $value["Election_Cd"];?>"><?php echo $value["ElectionName"];?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-2">
                            <div class="form-group">
                                <input type="hidden" name="DBName" id="DBName" value="<?= $DBName ?>">
                                <label for="societyName">Society</label>
                                <select class = "select2 form-control societyName" style="width:100%" name="societyName"  id = "societyName">
                                    <option value=""> Select Society </option>
                                </select> 
                            </div>
                        </div> -->
                        <div class="col-sm-2">
                            <div class="form-group in-border">
                            <input type="hidden" name="DBName" id="DBName" value="<?= $DBName ?>">
                                <label for="FirstName">First Name</label>
                                <input type="text" class="form-control" name="FirstName" id="FirstName" value="" placeholder="Firstname" onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group in-border">
                                <label for="MidddleName">Middle Name </label>
                                <input type="text" class="form-control" name="MiddleName" id="MiddleName" value="" placeholder="Middlename" onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group in-border">
                                <label for="LastName">Last Name</label>
                                <input type="text" class="form-control" name="LastName" id="LastName" value="" placeholder="Last Name" onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"  oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group in-border">
                                <label for="IdCard_No">ID Card No</label>
                                <input type="text" class="form-control" name="IdCard_No" id="IdCard_No" value="" placeholder="Id Card No" onkeyup = "this.value = this.value.toUpperCase()" oninput="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group in-border">
                                <label for="mobile_no">Mobile No</label>
                                <input type="text" class="form-control" name="mobile_no" id="mobile_no"  placeholder="Mobile NO" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>
                        <div class="col-sm-12">
                        <label for="mobile_no" class="float-right"></label>
                            <button type="button" id= "searchbtn" class="btn btn-primary float-right">Search</button>
                            <button type="button" id= "clearbtn" class="btn btn-dark float-right mr-2">Clear</button>
                        </div>    
                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="col-md-12" style="align-items:center">
                        <center>
                            <div id='SearchingLoad' style='display:none'>
                                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
                            </div>
                        </center>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header" style ="display:block;">
                                <button type="button" id="Add" class="btn btn-primary float-right">+</button>
                                 <button type="button" id="remove" class="btn btn-primary float-right" style="display:none">-</button>
                                <button type="button" id="locked_room" class="btn btn-danger float-right mr-2"><i class='fa fa-lock'></i></button>
                               
                            </div>
                            <div class="card-body">
                                <div class="row votersTable">
                                    <div class="table-responsive">
                                        <table class="table border-top-0 table-striped table-bordered text-nowrap border-bottom"  id="VotersDataTable">
                                            <thead>
                                                <tr role="row">
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Action</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>IdCard No</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Voter ID</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Full Name</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Ward No</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Age</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Sex</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Birthdate</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Mobile No</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Room No</th>
                                                    <th style='background-color: rgb(54, 171, 185); color: white;'>Society Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="FamilyDiv">
                    <?php 
                       
                    ?>
                    <?php 
                        include "setNonVoterFamilyInSession.php";
                    ?>
                </div>
                
                <div class="row UpdateVoters">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="title">Update Non Voters</h4>
                            </div>
                            <div class="card-body">
                                <form action="#" id = "UpdateVoters">
                                    <div class="row p-0 m-0">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <input type="hidden" name="DBName" id="DBName" value="<?= $DBName ?>">
                                                <input type="hidden" name="VoterCd" id= "VoterCd">
                                                <input type="hidden" name="VoterCds" id="VoterCds"> 
                                                <input type="hidden" name="VNType" id= "VNType">
                                                <input type="hidden" name="Ac_No" id= "Ac_No" value="<?= $GetAc_No ?>">
                                                <label for="societyName">Society</label><span class="required" style="color : red">*</span>
                                                <select class ="select2 form-control societyName" style="width:100%" name="societyName" id="addSociety">
                                                    <option value=""> Select Society </option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-sm-2" id="NewSocietyDiv" style="display:none;">
                                            <div class="form-group in-border">
                                                <label for="NewSociety">New Society</label><span class="required" style = "color:red">*</span>
                                                <input type="text" class="form-control" name="NewSociety" id="NewSociety" value="" placeholder="NewSociety"  onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="FirstName">First Name</label><span class="required" style = "color:red">*</span>
                                                <input type="text" class="form-control" name="FirstName" id="FirstName" value="" placeholder="Firstname"  onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="MidddleName">Middle Name </label><span class="required" style = "color:red">*</span>
                                                <input type="text" class="form-control" name="MiddleName" id="MiddleName" value="" placeholder="Middlename"  onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="LastName">Last Name</label><span class="required" style = "color:red">*</span>
                                                <input type="text" class="form-control" name="LastName" id="LastName" value="" placeholder="Last Name"  onkeyup = "this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="IdCard_No">ID Card No</label>
                                                <input type="text" class="form-control" name="IdCard_No" id="IdCard_No" value="" placeholder="Id Card No" onkeyup = "this.value = this.value.toUpperCase()" oninput="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="MobileNo">Mobile No</label>
                                                <input type="text" class="form-control" name="MobileNo" id="MobileNo"  placeholder="Mobile NO" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    <!-- </div>

                                    <div class="row p-0 m-0"> -->
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="Ward_no">Ward No</label>
                                                <input type="text" class="form-control" name="Ward_no" id="Ward_no"  placeholder="Ward No" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="sector">Sector</label>
                                                <input type="text" class="form-control" name="sector" id="sector"  placeholder="Sector">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="room_no">Room No</label>
                                                <input type="text" class="form-control" name="room_no" id="room_no"  placeholder="Room No">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="age">Age</label>
                                                <input type="text" class="form-control" name="age" id="age"  placeholder="Age" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="list_no">List No</label>
                                                <input type="text" class="form-control" name="list_no" id="list_no"  placeholder="List No" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" id= "updatebtn" class="btn btn-primary float-right">Update</button>
                                        </div>    
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row lockedRoom" id="LockDiv">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="title">Update Non Voters - Locked Room </h4>
                            </div>
                            <div class="card-body">
                                <form action="#" id = "lockedRoom">
                                    <div class="row p-0 m-0">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <input type="hidden" name="DBName" id="DBName" value="<?= $DBName ?>">
                                                <input type="hidden" name="VoterCd" id= "VoterCd">
                                                <input type="hidden" name="Ac_No" id= "Ac_No" value="<?= $GetAc_No ?>">
                                                <label for="societyName">Society</label><span class="required" style="color : red">*</span>
                                                <select class = "select2 form-control societyName" style="width:100%" name="societyName"  id = "lockSociety">
                                                    <option value=""> Select Society </option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-sm-2" id="NewSocietyDiv" style="display:none;">
                                            <div class="form-group in-border">
                                                <label for="NewSociety">Society No</label>
                                                <input type="text" class="form-control" name="NewSociety" id="NewSociety"  placeholder="New Society">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="Ward_no">Ward No</label>
                                                <input type="text" class="form-control" name="Ward_no" id="Ward_no"  placeholder="Ward No" oninput="this.value=this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="room_no">Room No</label><span class="required" style="color : red">*</span>
                                                <input type="text" class="form-control" name="room_no" id="room_no"  placeholder="Room No">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="floor">floor</label>
                                                <input type="text" class="form-control" name="floor" id="floor"  placeholder="Floor">
                                            </div>
                                        </div>
                                         <div class="col-sm-2">
                                            <div class="form-group in-border">
                                                <label for="Remark">Remark</label>
                                                <input type="text" class="form-control" name="Remark" id="Remark" value="" placeholder="Remark"  oninput="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')">
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" id= "updateroom" class="btn btn-primary float-right">Update</button>
                                        </div>    
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function() {
    
    $("#addSociety").on("change", function () {
    // alert("Gaurii!!");
    var society_cd = $(this).val();
    console.log(society_cd);

    if (society_cd === '00') {
        $("#NewSocietyDiv").show();
    } else {
        $("#NewSocietyDiv").hide();
    }
});

$("#lockSociety").on("change", function () {
    // print_r("Gaurii!!");
    var society_cd = $(this).val();
    console.log(society_cd);
    if (society_cd === '00') {
        $("#NewSocietyDiv").show();
    } else {
        $("#NewSocietyDiv").hide();
    }
});
    SocietyDropDown('societyName'); 
    $('#VotersDataTable tbody').hide();
    $('#VotersDataTable_wrapper .dataTables_info').hide(); 
    $('#VotersDataTable_wrapper .dataTables_length').hide(); 
    $('#VotersDataTable_wrapper .dataTables_filter').hide(); 
    $('#VotersDataTable_wrapper .dataTables_paginate').hide();
    $('.UpdateVoters').hide();
    $('.lockedRoom').hide();

   
});


$('#searchbtn').click(function (){
    var SocietyName = $('#societyName').val();
    var first_name = $('#FirstName').val();
    var MiddleName = $('#MiddleName').val();
    var LastName = $('#LastName').val();
    var MobileNo = $('#mobile_no').val();
    var IdCard = $('#IdCard_No').val();
    var DbName = $('#DBName').val();

    if(SocietyName == '' && first_name == '' && MiddleName == '' && LastName == '' && MobileNo == '' && IdCard == '' ){
        alert('Please Enter Atleast Name, Mobile, IdCard');
    }else if (first_name !== '' && (MiddleName === '' && LastName === '')) {
        alert('Please enter atleast Middle Name or Last Name.');
    }else if (MiddleName !== '' && (first_name === '' && LastName === '')) {
        alert('Please enter atleast First Name or  Last Name.');
    }else if (LastName !== '' && (first_name === '' && MiddleName === '')) {
        alert('Please enter Atleast First Name Or Middle Name.');
    }else{
        $('#VotersDataTable tbody').show();
        VotersDataTablePagination();
    }
});


function SocietyDropDown(id){
    var DbName = $('#DBName').val();
    var EleName = <?php echo "'".$electionName."'"; ?>;
    $('#'+id).select2({
    placeholder: "Select a Society",
    ajax: {
            type: 'POST',
            url: 'getsocietylist.php', 
            dataType: 'json',
            delay: 250, 
            data: function (params) {
                return {
                    task: 'fetchSocietyList', 
                    search: params.term,
                    DBName: DbName,
                    EleName: EleName
                };
            },
            processResults: function (data) {
                   
                $('#'+id).empty();
  
                var staticOption = {
                    id: "00", // Unique identifier for the static option
                    text: "New Society" // Display text for the static option
                };

                // Map dynamic options
                var dynamicOptions = $.map(data, function (item) {
                    return {
                        id: item.Society_Cd,
                        text: item.SocietyName
                    };
                });

                // Combine static and dynamic options
                var combinedOptions = [staticOption, ...dynamicOptions]; // Static option added at the top

                return {
                    results: combinedOptions
                };
            },
            cache: true
    },
        minimumInputLength: 3 
    });
}

function VotersDataTablePagination() {
    const LastName = $('#LastName').val();
    const FirstName = $('#FirstName').val();
    const societyName = $('#societyName').val();
    const MiddleName = $('#MiddleName').val();
    const IdCard_No = $('#IdCard_No').val();
    const MobileNo = $('#mobile_no').val();
    const DbName = $('#DBName').val();
    if (!DbName) {
        alert("Please select a database name.");
        return;
    }
    var table = $('#VotersDataTable').DataTable();
   
    table.clear();

    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'getsocietylist.php',
        data: {
            DBName: DbName,
            task: 'votersList',
            LastName: LastName,
            FirstName: FirstName,
            societyName: societyName,
            MiddleName: MiddleName,
            IdCard_No: IdCard_No,
            MobileNo:MobileNo,
        },
        error: function(xhr, error, thrown) {
            console.error('Error fetching data:', error);
            alert('An error occurred while fetching data. Please try again. ' + error);
            $('#searchbtn').prop('disabled', false); 
        },
        beforeSend: function () {
            $('#UpdateButton').attr("disabled", true);
            $('html').addClass("ajaxLoading");
            document.getElementById("SearchingLoad").style.display = "block";
        },
        success: function(response) {
            $('#VotersDataTable tbody').show();
            $('#VotersDataTable_wrapper .dataTables_info').show(); 
            $('#VotersDataTable_wrapper .dataTables_length').show(); 
            $('#VotersDataTable_wrapper .dataTables_filter').show(); 
            $('#VotersDataTable_wrapper .dataTables_paginate').show();
            console.log(response);

            if (!response || !response.data || response.data == "") {
                $('#updatebtn').show();
                table.clear().draw();
                return; 
            }
            $('#VotersDataTable').DataTable().destroy(); 
          
            $('#VotersDataTable').DataTable({
                processing: true,
                serverSide: false, 
                searching: true,
                data: response.data, 
                columns: [
                    { 
                        data: null,
                        render: function(data, type, row) {
                            if(row.SF != 1){
                                return `<a onclick='EditVoters(${row.Voter_Cd},"${row.type}",${row.FamilyNo},${row.Ac_No})'>
                                        <i class='fa fa-pencil-square-o'></i>
                                    </a>`;
                            }else {
                                return `<a onclick='ViewVoters(${row.Voter_Cd},"${row.type}",${row.FamilyNo},${row.Ac_No})'>
                                        <i class='fa fa-eye'></i>
                                    </a>`;
                            }
                            
                        },
                        orderable: false
                    },
                    { data: 'IdCard_No', orderable: false },
                    { data: 'Voter_Id', orderable: false },
                    { data: 'FullName', orderable: false },
                    { data: 'Ward_no', orderable: false },
                    { data: 'Age', orderable: false },
                    { data: 'Sex', orderable: false },
                    { data: 'BirthDate', orderable: false },
                    { data: 'MobileNo', orderable: false },
                    { data: 'RoomNo', orderable: false },
                    { data: 'SocietyName', orderable: false }
                ],
                order: [[0, 'desc']] 
            });
        },
        complete: function () {
            $('html').removeClass("ajaxLoading");
            document.getElementById("SearchingLoad").style.display = "none";
        }

    });
}

function surveyQCVoterSearch(){
    VotersDataTablePagination();
}

$('#updatebtn').click(function (){
    var Sublocation_Cd = $('#UpdateVoters #addSociety').val();
    if(Sublocation_Cd === '00'){
        var SocietyNew = $('#UpdateVoters #NewSociety').val();
    }else{
        var SocietyNew = '';
    }
    var first_name = $('#UpdateVoters #FirstName').val();
    var MiddleName = $('#UpdateVoters #MiddleName').val();
    var LastName = $('#UpdateVoters #LastName').val();
    var DbName = $('#UpdateVoters #DBName').val();
    var VoterCds = $('#UpdateVoters #VoterCds').val();
    var VNType = $('#UpdateVoters #VNType').val();
    // alert(VoterCds);
    if(Sublocation_Cd == ''){
        alert('Please selelct Society Name');
    }else if(first_name == ''){
        alert('Please Enter First Name');
    }else if(LastName == ''){
        alert('Please Enter Last Name');
    } else{
       
       var IdCard_No = $('#UpdateVoters #IdCard_No').val();
       var  mobile_no= $('#UpdateVoters #MobileNo').val() || '';
       var Ward_no = $('#UpdateVoters #Ward_no').val() || 0;
       var sector =$('#UpdateVoters #sector').val() || '';
       var room_no = $('#UpdateVoters #room_no').val() || 0;
       var age = $('#UpdateVoters #age').val() || 0;
       var list_no = $('#UpdateVoters #list_no').val() || 0;
       var VoterCd = $('#UpdateVoters #VoterCd').val();
       var Ac_No = $('#UpdateVoters #Ac_No').val() || 0;
        

       $.ajax({
            type: 'POST',
            url: 'getsocietylist.php', 
            dataType: 'json',
            data:{
                DBName : DbName,
                task : 'addUpdateVoter',
                Sublocation_Cd: Sublocation_Cd,
                SocietyNew: SocietyNew,
                first_name : first_name,
                LastName : LastName,
                MiddleName : MiddleName,
                IdCard_No: IdCard_No,
                mobile_no: mobile_no,
                Ward_no:Ward_no,
                sector: sector,
                room_no: room_no,
                age: age,
                list_no:list_no,
                VoterCd:VoterCd,
                Ac_No:Ac_No,
                type:VNType,
                VoterCds:VoterCds
            },
            success: function(response) {
               console.log(response);
               if(response.message == "Success" || response == true){
                    alert("Voters Updated Successfully");
                    location.reload();
               }else{
                    alert("Something Went Wrong");
               }
            },
       });

    }
});

function getAllNewVoters(value){
    $(".NewVotersCard").show();
    var Sublocation_Cd = $('#societyNameDrop').val();

}

function EditVoters(voters_id,vtype,familyno,ac_no) {
   $('#remove').show();
   //
    $('#Add').hide();
     $('.UpdateVoters').show();
    const DbName = $('#DBName').val();
    $.ajax({
        type: 'POST',
        url: 'getsocietylist.php',
        dataType: 'json',
        data: {
            DBName: DbName,
            task: 'singleVoter',
            VoterCd: voters_id,
            type: vtype,
        },
        error: function(xhr, error, thrown) {
            console.error('Error fetching voter data:', error);
            alert('An error occurred while fetching voter data. Please try again.');
        },
        success: function(response) {
            var data = response; 
             SocietyDropDown('addSociety');
            $("#UpdateVoters")[0].reset();
            $('#UpdateVoters #addSociety').append("<option value='00' selected>New Society</option>");
            $('#UpdateVoters #addSociety').append("<option value='"+data.Survey_Society_Cd+"' selected>"+data.SocietyName+"</option>");
            $('#UpdateVoters #FirstName').val(data.Name || '');
            $('#UpdateVoters #MiddleName').val(data.MiddleName || '');
            $('#UpdateVoters #LastName').val(data.Surname || '');
            $('#UpdateVoters #IdCard_No').val(data.IdCard_No || '');
            $('#UpdateVoters #MobileNo').val(data.MobileNo || '');
            $('#UpdateVoters #Ward_no').val(data.Ward_no || '');
            $('#UpdateVoters #sector').val(data.Sector || '');
            $('#UpdateVoters #room_no').val(data.RoomNo || '');
            $('#UpdateVoters #age').val(data.Age || '');
            $('#UpdateVoters #list_no').val(data.List_No || '');
            $('#UpdateVoters #VoterCd').val(data.Voter_Cd || '');
            $('#UpdateVoters #VNType').val(data.type || '');
            $('#UpdateVoters #updatebtn').show();
            $('#UpdateVoters #searchbtn').hide();
                getNonVoterFamilyInSession(familyno,ac_no,voters_id,DbName);
        },
    });
}

$('#Add').click(function(){
     $('#addSociety').empty();
    SocietyDropDown('addSociety'); 
    $('#UpdateVoters')[0].reset();
    $('#Add').hide();
    $('.lockedRoom').hide();
    $('#remove').show();
    $('.UpdateVoters').show();

    var FirstName = $('#searchVoters #FirstName').val();
    var MiddleName = $('#searchVoters #MiddleName').val();
    var LastName = $('#searchVoters #LastName').val();        
    var MobileNo = $('#searchVoters #MobileNo').val();
    var IdCard_no = $('#searchVoters #IdCard_No').val();

    var SocietyName = $('#searchVoters #societyName').text();
    var SubLocationCd = $('#searchVoters #societyName').val();

     $('#UpdateVoters #addSociety').append("<option value='"+SubLocationCd+"' selected>"+SocietyName+"</option>");
    
    $('#UpdateVoters #FirstName').val(FirstName);
    $('#UpdateVoters #MiddleName').val(MiddleName);
    $('#UpdateVoters #LastName').val(LastName);
    $('#UpdateVoters #MobileNo').val(MobileNo);
     $('#UpdateVoters #IdCard_No').val(IdCard_no);
});

$('#remove').click(function(){
     
    $('#addSociety').empty();
    $('#UpdateVoters')[0].reset();
    $('#remove').hide();
    $('#Add').show();
    $('.UpdateVoters').hide();
    $('.lockedRoom').hide();
});

$('#locked_room').click(function(){
    $('.UpdateVoters').hide();
    $('#remove').hide();
    $('#Add').show();

    SocietyDropDown('lockSociety'); 
     $('#lockedRoom')[0].reset();
    $('.lockedRoom').toggle();
    
   
});

$('#updateroom').click(function(){

    var DbName = $('#UpdateVoters #DBName').val();
    var SublocationCd = $('#lockedRoom #lockSociety').val();
    var Room = $('#lockedRoom #room_no').val();
    var Ward = $('#lockedRoom #Ward_no').val();
    var floor = $('#lockedRoom #floor').val();
    var remark = $('#lockedRoom #Remark').val();
    var Ac_No = $('#lockedRoom #Ac_No').val();
   if(SublocationCd === '00'){
        var SocietyNew = $('#lockedRoom #NewSociety').val();
    }else{
        var SocietyNew = '';
    }
    if(SublocationCd == ''){
        alert("Please Select Society Name");
    }else if(Room == ''){
        alert("Please Enter Room");
    }else{
        $.ajax({
            type: 'POST',
            url: 'getsocietylist.php', 
            dataType: 'json',
            data:{
                DBName : DbName,
                task : 'loockedRoom',
                Sublocation_Cd: SublocationCd,
                SocietyNew: SocietyNew,
                Ward_no:Ward,
                room_no: Room,
                remark:remark,
                floor:floor,
                Ac_No:Ac_No,
            },
            success: function(response) {
               console.log(response);
               if(response.message == "Success"){
                    alert("Voters Updated Successfully");
                    location.reload();
               }else{
                    alert("Something Went Wrong");
               }
            },
       });
    }
});

$('#clearbtn').click(function(){
    $('#societyName').empty();
    $('#searchVoters')[0].reset();
    $('#addSociety').empty();
    $('#UpdateVoters')[0].reset();
    var table = $('#VotersDataTable').DataTable();
    $('#VotersDataTable_wrapper .dataTables_info').hide(); 
    $('#VotersDataTable_wrapper .dataTables_length').hide(); 
    $('#VotersDataTable_wrapper .dataTables_filter').hide(); 
    $('#VotersDataTable_wrapper .dataTables_paginate').hide();
    table.clear().draw();
   
});

 $(document).keydown(function(event) {
    if (event.altKey && event.key === 's') {
        event.preventDefault(); 
        $('#searchbtn').click(); 
    }
});


function ViewVoters(voters_id,vtype,familyno,ac_no){
const DbName = $('#DBName').val();
    $.ajax({
        type: 'POST',
        url: 'getsocietylist.php',
        dataType: 'json',
        data: {
            DBName: DbName,
            task: 'singleVoter',
            VoterCd: voters_id,
            type: vtype,
        },
        error: function(xhr, error, thrown) {
            console.error('Error fetching voter data:', error);
            alert('An error occurred while fetching voter data. Please try again.');
        },
        success: function(response) {
            var data = response; 
            // alert(data.MobileNo);
            SocietyDropDown('addSociety');
             $('.UpdateVoters').show();
            $("#UpdateVoters")[0].reset();
            $('#UpdateVoters #addSociety').append("<option value='00' selected>New Society</option>");
            $('#UpdateVoters #addSociety').append("<option value='"+data.Survey_Society_Cd+"' selected>"+data.SocietyName+"</option>");
            $('#UpdateVoters #FirstName').val(data.Name || '');
            $('#UpdateVoters #MiddleName').val(data.MiddleName || '');
            $('#UpdateVoters #LastName').val(data.Surname || '');
            $('#UpdateVoters #IdCard_No').val(data.IdCard_No || '');
            $('#UpdateVoters #MobileNo').val(data.MobileNo || '');
            $('#UpdateVoters #Ward_no').val(data.Ward_no || '');
            $('#UpdateVoters #sector').val(data.Sector || '');
            $('#UpdateVoters #room_no').val(data.RoomNo || '');
            $('#UpdateVoters #age').val(data.Age || '');
            $('#UpdateVoters #list_no').val(data.List_No || '');
            $('#UpdateVoters #VoterCd').val(data.Voter_Cd || '');
            $('#UpdateVoters #VNType').val(data.type || '');
            $('#UpdateVoters #updatebtn').show();
            $('#UpdateVoters #searchbtn').hide();
                getNonVoterFamilyInSession(familyno,ac_no,voters_id,DbName);
        },
    });
}
function getNonVoterFamilyInSession(FamilyNo, Ac_No, Voter_Cd,DbName) {
    
    var ajaxRequest; // The variable that makes Ajax possible!

    try {
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    ajaxRequest.onreadystatechange = function () {
        if (ajaxRequest.readyState == 4) {
            var ajaxDisplay = document.getElementById('FamilyDiv');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
            // surveyQCAdvanceSearchNew()
            $('#spinnerLoader3').hide();
            
        }
    }


    $('#spinnerLoader3').show();
    var queryString = "?FamilyNo=" + FamilyNo + "&Ac_No=" + Ac_No + "&Voter_Cd=" + Voter_Cd+ "&DbName=" + DbName;
    // alert(queryString);
    ajaxRequest.open("POST", "setNonVoterFamilyInSession.php" + queryString, true);
    ajaxRequest.send(null);
}

</script>