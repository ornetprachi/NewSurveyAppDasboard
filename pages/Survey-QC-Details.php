<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
// $electionCd=$_SESSION['SurveyUA_Election_Cd'];
// $electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$peopleCount = '';

if(
    (isset($_GET['Society_Cd']) && !empty($_GET['Society_Cd'])) &&
    (isset($_GET['electionName']) && !empty($_GET['electionName'])) &&
    (isset($_GET['electionCd']) && !empty($_GET['electionCd'])) 
) {

    $Society_Cd = $_GET['Society_Cd'];
    $electionName = $_GET['electionName'];
    $electionCd = $_GET['electionCd'];
    $pagetype = 'List';


    $SurveyQCDetailsCardURL = "&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
    $_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'] = $Society_Cd;
    $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'] = $electionName;
    $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'] = $electionCd;
    $_SESSION['SurveyUA_pagetype_SurveyQC_Details'] = $pagetype;
    
    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);

    $sql = "SELECT * FROM SubLocationMaster where Survey_Society_Cd = $Society_Cd ;";

    $getSublocationCd = $db->ExecutveQuerySingleRowSALData($ULB,$sql, $userName, $appName, $developmentMode);

    if(sizeof($getSublocationCd)>0){

        $SubLocation_Cd = $getSublocationCd['SubLocation_Cd'];
        $SocietyNameM = $getSublocationCd['SocietyNameM'];
        $SocietyName = $getSublocationCd['SocietyName'];
        $Sector = $getSublocationCd['Sector'];
        $Ac_No = $getSublocationCd['Ac_No'];
        $Ward_No = $getSublocationCd['Ward_No'];
        $PlotNo = $getSublocationCd['PlotNo'];
        $Floor = $getSublocationCd['Floor'];
        $Rooms = $getSublocationCd['Rooms'];
        $SiteName = $getSublocationCd['SiteName'];
        $Pocket_Cd = $getSublocationCd['Pocket_Cd'];


        if($electionName != 'NMMC' && $electionName != 'AK_218'){
            $col = "Ward_no";
        }else{
            $col = "PrarubWard_no";
        }

        $column2 = 'dwv.FullName=e.FullName AND dwv.List_No = e.PART_NO';
        if($electionName != 'NMMC'){
            $column2 = 'dwv.Voter_Id=e.SLNOINPART AND dwv.List_No = e.PART_NO AND dwv.Ac_No = e.ac_no';
        }


        $sql3 = "SELECT 
        D.QC_Done,
        ROW_NUMBER() OVER(ORDER BY D.UpdatedDate Desc) AS SrNo,
        D.FormNo,
        D.Voter_Cd AS Voter_Cd,
        D.Ac_No,
        D.List_No, 
        D.Voter_Id, 
        D.SF, 
        D.Ward_No AS WardNo,
        CONVERT(nvarchar(50),D.Ac_No) + ' / ' + CONVERT(nvarchar(50),D.List_No) + ' / ' + CONVERT(nvarchar(50),D.Voter_Id) AS CorpNo,
        D.FullName,
        D.FullNameMar,
        D.Society_Cd,
        D.SocietyName,
        D.SocietyNameM, 
        D.Age AS Age, 
        D.Sex AS Sex,
        D.FamilyNo, 
        D.RoomNo,
        D.MobileNo AS MobileNo, 
        D.BirthDate AS BirthDate, 
        D.AnniversaryDate, 
        D.HS, 
        D.VidhanSabha,
        D.Occupation, 
        D.Education, 
        D.OwnerName,
        D.OwnerMobileNo, 
        D.District, 
        D.Religion, 
        cm.MarNmarDesc,
        D.SubCaste, 
        D.LockedButSurvey AS LBS, 
        D.Remark, 
        D.Sitename, 
        em.ExecutiveName AS UpdateByUserName,
        em.MobileNo AS UpdateByUserMobile,
        Convert(Varchar, D.UpdatedDate, 0) AS UpdatedDate,
        Convert(varchar, D.UpdatedDate,20) AS UpdatedDateOBJ,
        Convert(varchar,D.UpdatedDate,8) AS DateF,
        D.QC_UpdateByUser,
        Convert(Varchar, D.QC_UpdatedDate, 0) AS QC_UpdatedDate, 
        D.SurName AS SurName, 
        D.FirstName AS FirstName, 
        D.MiddleName AS MiddleName,
        D.Col4 AS FloorNo,
	    D.LR_Cd
        FROM 
        (
            SELECT
                Sitename, UpdateByUser, Voter_Cd, Ac_No, List_No, Voter_Id, Ward_No, SocietyName,
                UpdatedDate, RoomNo, FullName, FullNameMar, Age, Sex, FamilyNo,
                Convert(Varchar, BirthDate, 23) AS BirthDate,
                MobileNo, AndroidFormNo as FormNo, District, Religion, SF,Convert(Varchar, AnniversaryDate, 23) AS AnniversaryDate ,
                Sublocation_Cd AS Society_Cd, SocietyNameM, Hstatus AS HS,VidhanSabha, Occupation, Education, OwnerName, OwnerMobileNo,
                SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey, QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , 
                Col4, '' AS LR_Cd
                FROM  
                Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
                AND SubLocation_Cd = $SubLocation_Cd
            UNION ALL 
            SELECT 
                Sitename, UpdateByUser, Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName,
                UpdatedDate, RoomNo, FullName,'' AS FullNameMar, Age, Sex, FamilyNo, 
                Convert(Varchar, BirthDate, 23) AS BirthDate,
                MobileNo, AndroidFormNo as FormNo, District, Religion, '' AS SF,Convert(Varchar, AnniversaryDate, 23) AS AnniversaryDate , Subloc_Cd AS Society_Cd, '' AS SocietyNameM,
                Hstatus AS HS,VidhanSabha, Occupation, Education, OwnerName, OwnerMobileNo, SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey,
                QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , Col4 , '' AS LR_Cd
                FROM 
                NewVoterRegistration 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
                AND Subloc_cd = $SubLocation_Cd
            UNION ALL 
            SELECT 
                Sitename, UpdateByUser, 0 AS Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName, UpdatedDate, RoomNo,
                'LOCKED' AS FullName, '' AS FullNameMar, '' AS Age, '' AS Sex, '' AS FamilyNo, '' AS BirthDate,
                '' AS MobileNo, '' AS FormNo, '' AS District, '' AS Religion, '' AS SF, '' AS AnniversaryDate , Sublocation_Cd AS Society_Cd,
                SocietyNameM, '' AS HS, '' AS VidhanSabha, '' AS Occupation, '' AS Education, '' AS OwnerName, '' AS OwnerMobileNo, '' AS SubCaste, '' AS SurName,
                '' AS FirstName, '' AS MiddleName, '' AS LockedButSurvey, '' AS QC_UpdateByUser, '' AS QC_UpdatedDate, Remark, 'False' AS QC_Done ,
                FloorNo AS Col4,  LR_Cd
                FROM 
                LockRoom 
                WHERE (Locked = 1) 
                AND Sublocation_Cd = $SubLocation_Cd
        ) AS D
        LEFT JOIN Survey_Entry_Data..CommunityMaster as cm on (cm.MarNmar = D.Religion COLLATE Latin1_General_CI_AI)
        LEFT JOIN (SELECT UserName, Executive_Cd  FROM Survey_Entry_Data..User_Master GROUP BY UserName, Executive_Cd) um on (D.UpdateByUser = um.UserName COLLATE Latin1_General_CI_AI)
        LEFT JOIN Survey_Entry_Data..Executive_Master em on (em.Executive_Cd = um.Executive_Cd) 
        ORDER BY  Convert(varchar, D.UpdatedDate,20),D.RoomNo
        "; 

        $result = $db->ExecutveQueryMultipleRowSALData($ULB,$sql3 , $userName, $appName, $developmentMode);

        // usort($result, function($a, $b) {
        //     return intval($a['RoomNo']) - intval($b['RoomNo']);
        // });

        // echo sizeof($result);
        // Initialize counters
        $voterCount = 0;
        $nonvoterCount = 0;
        $lockroomCount = 0;

        // Iterate over the data array
        foreach ($result as $item) {
            $listNo = $item['List_No'];
            $voterId = $item['Voter_Id'];
            $fullName = $item['FullName'];

            // Check conditions to increment the respective counters
            if($listNo > 0 && $voterId > 0){
                $voterCount++;
            }elseif($listNo == 0 && $voterId == 0 && $fullName != 'LOCKED'){
                $nonvoterCount++;
            }elseif($fullName == 'LOCKED'){
                $lockroomCount++;
            }
        }

        $sql4 = "SELECT
        (RD+$lockroomCount) AS RD,Rooms AS TotalRooms,ABS((RD+$lockroomCount)-Rooms) as PEN from
        (
            SELECT 
                (select  count (*)  from
                    (select ddvv.RoomNo as RoomNo from Dw_VotersInfo as ddvv 
                        where ddvv.SF = 1 and ddvv.SubLocation_Cd = subloc.SubLocation_Cd
                        union 
                        select nnvv.RoomNo as RoomNo from NewVoterRegistration as nnvv 
                        where nnvv.Subloc_cd = subloc.SubLocation_Cd
                    ) as tb1
                ) as RD
                ,sm.Rooms
            from Society_Master as sm
            left join SubLocationMaster as subloc on sm.Society_Cd = subloc.Survey_Society_Cd
            WHERE sm.Society_Cd = $Society_Cd
            GROUP BY subloc.SubLocation_Cd,sm.Rooms
        ) AS tb2
        ";

        $result4 = $db->ExecutveQuerySingleRowSALData($ULB,$sql4 , $userName, $appName, $developmentMode);
        
        if(!empty($result4)){
            $RD = $result4['RD'];
            $TR = $result4['TotalRooms'];
            $PEN = $result4['PEN'];
        }else{
            $RD = '';
            $TR = '';
            $PEN = '';
        }

        // print_r("<pre>");
        // print_r($result);
        // print_r("</pre>");
    }
}

?>
<script>document.body.style.zoom="90%"</script>
<style>
    .table-container {
        height: 500px;
        overflow-y: scroll;
        position: relative;
        text-align: center;
        align-items: center;
    }
    .table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 7px;
    }

    .table td,
    .table th {
        padding: 0.75px;
        padding-left:5px;
        margin: 0;
        text-align: left;
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

    .table-hover tbody tr:hover {
      background-color: #dcfbff;
    }

    .dot {
        height: 10px;
        width: 10px;
        background-color: blue;
        display: inline-block;
        margin-left:10px
    }

</style>

<div class="row match-height" style="margin-bottom:-10px">
    <div class="col-md-12">
        <div class="card" style="padding-bottom:-10px">
            <div class="card-header mt-0" style="padding-top:10px">
                <div class="row" style="width:100%">
                    <div class="col-md-8" >
                        <div style="padding-top:10px">
                            <h4 class="card-title"><?php echo $SocietyName?></h4>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-top:10px" >
                        <a href="index.php?p=Survey-QC-Details-Card<?php echo $SurveyQCDetailsCardURL; ?>">
                            <button class="btn btn-primary btn-sm" title="Card View" style=""><i class="feather icon-credit-card" style="font-size:10px"></i></button>
                        </a>
                        <a href="index.php?p=Non-Voters-with-Society&SocietyName=<?php echo $SocietyName;?>&DBName=<?php echo $DBName; ?>">
                            <button class="btn btn-primary btn-sm" title="Card View"><i class="fa fa-plus" style="font-size:10px"></i></button>
                        </a>
                    </div>
                    <div class="col-md-3" >
                        <div class="float-right" >
                            <p style="margin-bottom:0">
                                <b title="Voter / Non Voter / LockRoom">V / NV / LR : <?php echo $voterCount . " / " . $nonvoterCount . " / " . $lockroomCount; ?></php></b>
                                <br style = "border-color:grey;"> 
                                <b title="Room Done / Total Room / Pending">RD / TR / PEN : <?php echo $RD . " / " . $TR . " / " . $PEN; ?></php></b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0" id="">
                
                <div class="row">
                    <div class="col-xs-12 col-xl-12 col-md-12 col-12" >
                        <div class="table-container" >
                            <table class="table table-hover" style="border:solid 1px black;" id="SurveyQCList">
                                <thead>
                                <tr>
                                    <th  style=''>Sr</th>
                                    <th  style=''>Action</th>
                                    <th  style=''>QC</th>
                                    <!-- <th  style=''>Delete</th> -->
                                    <th  style=''>CorpNo</th>
                                    <th  style=''>FullName</th>
                                    <th  style=''>FloorNo</th>
                                    <th  style=''>Count</th>
                                    <th  style=''>Room</th>
                                    <th  style=''>MobileNo</th>
                                    <th  style=''>HS</th>
                                    <th  style=''>Voted</th>
                                    <th  style=''>LBS</th>
                                    <th  style=''>Religion</th>
                                    <th  style=''>Birthdate</th>
                                    <th  style=''>Age</th>
                                    <th  style=''>Sex</th>
                                    <th  style=''>UpdatedDate</th>
                                    <th  style=''>UpdatedBy</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($result) > 0){
                                        $srNo = 1;
                                        // $RoomNo = 1;
                                        $LtName = str_replace("'", "", $value['SurName']);
                                        $RoomNo = $result[0]['RoomNo'];
                                        $RoomNoCount = 1;
                                        $Iteration = 0;

                                        foreach($result AS $Key=>$value){  

                                            $I =0;
                                            if($Iteration != 0){
                                                $I = $Iteration-1;
                                             // print_r($result[1]['DateF'].'='.$value['DateF']);
                                             $startTime = new DateTime($result[$Iteration]['DateF']);
                                             $endTime = new DateTime($result[$I]['DateF']);
                                             $timeDifference = $startTime->diff($endTime);
                                             
                                             $hoursDifference = $timeDifference->h;
                                             $minutesDifference = $timeDifference->i;
                                             $secondsDifference = $timeDifference->s;
                                             // print_r($hoursDifference.':'.$minutesDifference.':'.$secondsDifference);
                                             }   
                                            $Iteration+1;
                                         
                                        ?>
                                        
                                        <tr <?php       if($value['LBS'] == 'DNP' || $value['LBS'] == 'BNP'|| $value['LBS'] == 'NBS'){ ?>style="background-color: #C0EBFF;" <?php }if($value['List_No'] == '0' && $value['Voter_Id'] == '0' && $value['FullName'] != 'LOCKED'){ $Type = 'NonVoter'; 
                                       ?>
                                                style="background-color: #ffc0cb;" <?php } else if($value['FullName'] == 'LOCKED'){ $Type = 'LockRoom';?> style="color:red" <?php }else{ $Type = 'Voter'; } if($value['Remark'] != ""){ ?>  title="<?php echo 'Remark - ' . $value['Remark'] ?>" <?php } ?> >
                                            <td><?php echo $srNo++; ?></td>
                                            <td style="text-align:center;width:20px;">
                                                <?php if($value['List_No'] == '0' && $value['Voter_Id'] == '0' && $value['FullName'] != 'LOCKED'){ ?>
                                                    <a class="" onclick="SurveyQCNonVoterEdit('<?php echo $DBName ?>','<?php echo $value['Voter_Cd'] ?>','<?php echo $SubLocation_Cd ?>','<?php echo $value['RoomNo'] ?>','<?php echo $value['FirstName'] ?>', '<?php echo $value['MiddleName'] ?>', '<?php echo $LtName ?>')">
                                                        <b><i class="fa fa-pencil-square-o"></i></b>
                                                    </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php } ?>
                                            <!-- </td>
                                            <td style="text-align:center;"> -->
                                                <?php //if($value['List_No'] == '0' && $value['Voter_Id'] == '0' && $value['FullName'] != 'LOCKED'){ ?>
                                                <?php 
                                                    if($value['List_No'] == '0' && $value['Voter_Id'] == '0'){ 
                                                        if($value['FullName'] == 'LOCKED'){
                                                ?>
                                                            <a class="" onclick="DeleteExtraLockRoom('<?php echo $value['Society_Cd'] ?>','<?php echo $value['RoomNo'] ?>')">
                                                                <b><i class="fa fa-trash"></i></b>
                                                            </a>

                                                <?php   }else{ ?>
                                                            <a class="" onclick="DeleteExtraVoter('<?php echo $value['Voter_Cd'] ?>')">
                                                                <b><i class="fa fa-trash"></i></b>
                                                            </a>
                                                <?php   }
                                                    } ?>
                                            </td>
                                            <td style="text-align:center;">
                                                <?php if($value['QC_Done'] == 1){
                                                    echo "<b style='color:green;' title='Qc Done'><i class='fa fa-check'></i></b>";
                                                }else{
                                                    echo "";
                                                }
                                                ?>
                                            </td>
                                            <td><?php if($value['FullName'] == 'LOCKED' || ($value['List_No'] == '0' && $value['Voter_Id'] == '0')){ echo '';}else{ echo $value['CorpNo']; } ?></td> 
                                            <td><?php echo $value['FullName']; if($value['Remark'] != ""){ ?><i class="fa fa-asterisk ml-1" style="color:blue"></i><?php } ?></td>
                                            <!-- <td><?php //echo $Type ;?></td>  -->
                                            <td contenteditable="true" data-roomno="<?php echo $value['FloorNo'] . "~" . $DBName . "~ Floor ~" . $Type; ?>~<?php if($Type == 'NonVoter' || $Type == 'Voter'){echo $value['Voter_Cd'];}elseif($Type == 'LockRoom'){echo $value['LR_Cd'];} ?>"><?php echo $value['FloorNo'];?></td> 
                                            <td>
                                                <?php 
                                                    if($RoomNo != $value['RoomNo']){
                                                        $RoomNoCount++;
                                                        echo $RoomNoCount;
                                                    }else{
                                                        echo $RoomNoCount;
                                                    }
                                                ?>
                                            </td> 
                                            <td contenteditable="true" data-roomno="<?php echo $value['RoomNo'] . "~" . $DBName . "~ Roomm ~" . $Type; ?>~<?php if($Type == 'NonVoter' || $Type == 'Voter'){echo $value['Voter_Cd'];}elseif($Type == 'LockRoom'){echo $value['LR_Cd'];} ?>">
                                                <?php echo $value['RoomNo'];?>
                                            </td>
                                            <td style="align:left;" contenteditable="true" data-roomno="<?php echo $value['MobileNo'] . "~" . $DBName . "~ Mobile ~" . $Type; ?>~<?php if($Type == 'NonVoter' || $Type == 'Voter'){echo $value['Voter_Cd'];}elseif($Type == 'LockRoom'){echo $value['LR_Cd'];} ?>"><?php echo $value['MobileNo'];?></td> 
                                            <td title="<?php if($value['HS'] == 'O'){echo 'Owner';}elseif($value['HS'] == 'R'){echo 'Rented';}else{echo $value['HS'];} ?>" contenteditable="true" data-roomno="<?php echo $value['HS'] . "~" . $DBName . "~ HouseStatus ~" . $Type; ?>~<?php if($Type == 'NonVoter' || $Type == 'Voter'){echo $value['Voter_Cd'];}elseif($Type == 'LockRoom'){echo $value['LR_Cd'];} ?>" ><?php echo $value['HS']; ?></td>
                                            <td><?php if($value['VidhanSabha'] == 1){echo "Yes";}elseif($value['VidhanSabha'] == 0){echo "No";}else{ echo $value['VidhanSabha']; }?></td> 
                                            <td><?php echo "<b>" . $value['LBS'] . "</b>";?></td> 
                                            <td><?php echo "<b>" . $value['MarNmarDesc'] . "</b>";?></td> 
                                            <td><?php echo $value['BirthDate'];?></td> 
                                            <td><?php if($value['FullName'] == 'LOCKED'){ echo "";}else{ echo $value['Age']; } ?></td> 
                                            <td><?php echo $value['Sex'];?></td> 
                                            <td><?php echo "<b>" . $value['UpdatedDate'] . "</b>";?></td> 
                                            <td><?php echo $value['UpdateByUserName'];?> - <?php echo $value['UpdateByUserMobile'];?></td> 
                                        </tr>
                                        <?php
                                            $RoomNo = $value['RoomNo'];
                                        }
                                    }else{ ?>
                                        <tr><td colspan="9">No Record Found</td></tr>
                                <?php 
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



<div class="row">
    <div class="col-xs-12 col-xl-12 col-md-12 col-12">
        <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
        <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
    </div>
</div>
<div class="d-flex flex-row-reverse">
    <div class="col-xs-6 col-xl-2 col-md-2 col-12">
        <div class="controls text-center" style="">
            <button type="button" class="btn btn-primary float-right" onclick="saveSurveyQCSocietyData('<?php echo $Society_Cd; ?>','<?php echo $DBName; ?>','<?php echo $SubLocation_Cd; ?>')" id="QCDoneButtonSurveyQC" >
                    QC Done 
            </button>
        </div>
    </div>
</div>

<div class="row mt-3" id="SurveyQCDetailsListNonVoterSearch">

</div>


<script>

function LockRoomBounceAnimation(Key){

    $("#LockRoomBounceAnimation_"+Key).addClass("bounce");
    
    setTimeout(function() {
        $("#LockRoomBounceAnimation_"+Key).removeClass("bounce");
    }, 500);
}

</script>

<script>
    var editedRoomNo = null;
    var originalRoomNo = null;
    var DBName = null;
    var Type = null;
    var Cd = null;
    var InputField = null;

    function updateRoomNo(element) {
        var roomNo = element.innerHTML;
        var originalValues = element.getAttribute('data-roomno').split("~");

        // Extract the values from the original string
        originalRoomNo = originalValues[0];
        // originalRoomNo = originalRoomNo.trim();
        roomNo = roomNo.trim();
        DBName = originalValues[1]
        InputField = originalValues[2];
        Type = originalValues[3];
        Cd = originalValues[4];

        // Check if the room number has been edited
        if (roomNo !== originalRoomNo) {
            // Store the edited room number
            editedRoomNo = roomNo;
        } else {
            editedRoomNo = null; // Reset the edited room number if there are no changes
        }
    }

    // Attach event listener to the editable <td> elements
    var roomNoCells = document.querySelectorAll("td[contenteditable='true']");
    roomNoCells.forEach(function(element) {
        element.addEventListener("input", function() {
            updateRoomNo(element);
        });

        element.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                element.blur(); // Trigger the blur event to handle confirmation prompt
            }
        });

        element.addEventListener("blur", function() {
            if (editedRoomNo !== null) {
                // var confirmed = confirm("Are you sure you want to update the Room No to '" + editedRoomNo + "'?");
                if(editedRoomNo === ''){
                    alert("Please Enter "+InputField+" Number!");
                    element.innerHTML = originalRoomNo;
                }else
                 if(confirm("Are you sure you want to update the"+InputField+" No to '" + editedRoomNo + "'?") == true) {
                    $.ajax({
                        url: 'action/updateSurveyQCRoomNo.php',
                        method: 'POST',
                        data: {
                            roomNo: editedRoomNo,
                            originalRoomNo: originalRoomNo,
                            Inputfield: InputField,
                            DBName: DBName,
                            Type: Type,
                            Cd: Cd
                        },
                        success: function(dataResult) {
                            // alert('in success');
                            // console.log(dataResult);
                            // alert(dataResult);

                            var dataResult = JSON.parse(dataResult);
                            if(dataResult.statusCode == 200){
                                alert(dataResult.msg);
                                location.reload(true);
                            }else{
                                alert(dataResult.msg);
                            }
                        }
                    });
                } else {
                    // Revert the changes by restoring the original room number
                    element.innerHTML = originalRoomNo;
                }

                editedRoomNo = null; // Reset the edited room number
            }
        });
    });
</script>
