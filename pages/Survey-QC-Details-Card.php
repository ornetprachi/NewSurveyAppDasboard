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
    $pagetype = 'Card';

    $SurveyQCDetailsListURL = "&Society_Cd=" . $Society_Cd . "&electionName=" . $electionName . "&electionCd=" . $electionCd;
    $_SESSION['SurveyUA_Society_Cd_SurveyQC_Details'] = $Society_Cd;
    $_SESSION['SurveyUA_ElectionName_SurveyQC_Details'] = $electionName;
    $_SESSION['SurveyUA_ElectionCd_SurveyQC_Details'] = $electionCd;
    $_SESSION['SurveyUA_pagetype_SurveyQC_Details'] = $pagetype;


    // echo $Society_Cd . "<br>" . $electionName . "<br>" . $electionCd;

    $DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);

    $sql = "SELECT * FROM $DBName..SubLocationMaster where Survey_Society_Cd = $Society_Cd ;";

        // echo $sql;
        // die();

    $getSublocationCd = $db->ExecutveQuerySingleRowSALData($ULB,$sql, $userName, $appName, $developmentMode);

    // print_r("<pre>");
    // print_r($getSublocationCd);
    // print_r("</pre>");

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

        $sql2 = "SELECT 
                    COALESCE(dwv.FullName, '') AS FullName,  
                    COALESCE(dwv.Name, '') AS Name, 
					COALESCE(dwv.MiddleName, '') AS MiddleName, 
					COALESCE(dwv.Surname, '') AS Surname,
                    COALESCE(dwv.MobileNo, '') AS MobileNo, 
                    COALESCE(Convert(Varchar, dwv.BirthDate, 23), '') AS BirthDate,
                    COALESCE(dwv.Age, 0) AS Age, 
                    COALESCE(dwv.Sex, '') AS Sex, 
                    COALESCE(dwv.Col4, '') AS FloorNo, 
                    COALESCE(dwv.RoomNo, '') AS RoomNo, 
                    COALESCE(dwv.Ac_No,  0) AS Ac_No, 
                    COALESCE(dwv.List_No, 0) AS List_No, 
                    COALESCE(dwv.Voter_Id, 0) AS Voter_Id, 
					COALESCE(dwv.Voter_Cd , 0) AS Voter_Cd,
                    COALESCE(dwv.FamilyNo, 0) AS FamilyNo, 
                    COALESCE(dwv.Remark, '') AS Remark, 
                    COALESCE(dwv.Hstatus, '') AS Hstatus,  
                    COALESCE(dwv.VidhanSabha, '') AS VidhanSabha, 
                    COALESCE(ColumnToSwFinalResult, '') AS Photo
                    FROM $DBName..Dw_VotersInfo AS dwv
                LEFT JOIN ".$electionName."_VoterImages..EROLLING AS e 
                ON ($column2)
                CROSS APPLY (SELECT [PHOTO] '*' FOR XML PATH('')) T (ColumnToSwFinalResult)
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')
                AND dwv.SubLocation_Cd = $SubLocation_Cd
                UNION ALL
                SELECT
                    COALESCE(nvr.Fullname, '') AS Fullname,  
                    COALESCE(nvr.Name, '') AS Name, 
					COALESCE(nvr.MiddleName, '') AS MiddleName, 
					COALESCE(nvr.Surname, '') AS Surname, 
                    COALESCE(nvr.Mobileno, '') AS Mobileno, 
                    COALESCE(Convert(Varchar, nvr.Birthdate, 23), '')as BirthDate ,
                    COALESCE(nvr.Age, 0) AS Age, 
                    COALESCE(nvr.Sex, '') AS Sex, 
                    COALESCE(nvr.Col4, '') AS FloorNo,
                    COALESCE(nvr.Roomno, '') AS Roomno, 
                    COALESCE(nvr.Ac_No, 0) AS Ac_No, 
                    COALESCE(nvr.List_No, 0) AS List_No, 
                    COALESCE(nvr.Voter_Id, 0) AS Voter_Id,  
					COALESCE(nvr.Voter_Cd, 0) AS Voter_Cd, 
                    COALESCE(nvr.FamilyNo, 0) AS FamilyNo,  
                    COALESCE(nvr.Remark, '') AS Remark, 
                    COALESCE(nvr.Hstatus, '') AS Hstatus,   
                    COALESCE(nvr.VidhanSabha, '') AS VidhanSabha, 
                    '' AS Photo
                FROM $DBName..NewVoterRegistration AS nvr 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N')
                AND nvr.Subloc_cd = $SubLocation_Cd
                order by RoomNo; ";


        // $sql3 = "SELECT COALESCE(LR_Cd, 0) AS LR_Cd, 
        // COALESCE(RoomNo, '') AS RoomNo, 
        // COALESCE(Remark, '') AS Remark FROM $DBName..LockRoom 
        // WHERE (Locked = 1)
        // AND Sublocation_Cd = $SubLocation_Cd
        // ORDER BY RoomNo";

        $sql3 = "SELECT 
                    RoomNo,
                    COALESCE(LR_Cd, 0) AS LR_Cd, 
                    COALESCE(Remark, '') AS Remark
                FROM $DBName..LockRoom WHERE (Locked = 1) AND SubLocation_Cd = $SubLocation_Cd 
                EXCEPT 
                (
                    SELECT 
                    Dw_VotersInfo.RoomNo AS RoomNo,
                    LockRoom.LR_Cd AS LR_Cd,
                    LockRoom.Remark AS Remark
                    FROM $DBName..Dw_VotersInfo
                    LEFT JOIN $DBName..LockRoom ON (Dw_VotersInfo.SubLocation_Cd = LockRoom.Sublocation_Cd)
                    WHERE (Dw_VotersInfo.UpdatedStatus = 'Y' OR Dw_VotersInfo.UpdatedStatus = 'N')
                    AND Dw_VotersInfo.SubLocation_Cd = $SubLocation_Cd AND SF = 1	
                    UNION  
                    SELECT 
                    NewVoterRegistration.RoomNo AS RoomNo,
                    LockRoom.LR_Cd AS LR_Cd,
                    LockRoom.Remark AS Remark
                    FROM $DBName..NewVoterRegistration
                    LEFT JOIN $DBName..LockRoom ON (NewVoterRegistration.Subloc_cd = LockRoom.Sublocation_Cd)
                    WHERE (NewVoterRegistration.UpdatedStatus = 'Y' OR NewVoterRegistration.UpdatedStatus = 'N')
                    AND NewVoterRegistration.Subloc_cd = $SubLocation_Cd
                    )";

        $result = $db->ExecutveQueryMultipleRowSALData($ULB,$sql2 , $userName, $appName, $developmentMode);

        $voterCount = 0;
        $nonVoterCount = 0;

        foreach ($result as $person) {
            if ($person['Voter_Id'] > 0 && $person['List_No']) {
                $voterCount++;
            } else {
                $nonVoterCount++;
            }
        }

        $LockRoomRes = $db->ExecutveQueryMultipleRowSALData($ULB,$sql3 , $userName, $appName, $developmentMode);

        // echo (sizeof($result) + sizeof($LockRoomRes));
        $LockRoomCount = count($LockRoomRes);
        $resultCount = count($result);

        $finalArray = array();

        if(sizeof($result)>0){
            foreach ($result as $row) {
                $roomNo = $row['RoomNo'];
                
                if (!isset($finalArray[$roomNo])) {
                    $finalArray[$roomNo] = array();
                }
                usort($finalArray[$roomNo], function($a, $b) {
                    return $b['Age'] - $a['Age'];
                });
                $finalArray[$roomNo][] = $row;
            }
        }

        $LRfinalArray = array();
        foreach ($LockRoomRes as $element) {
            $roomNo = $element['RoomNo'];
            $LRfinalArray[$roomNo] = $element;
        }

        // Extract room numbers from both arrays
        $roomNumbers1 = array_keys($finalArray);
        $roomNumbers2 = array_keys($LRfinalArray);

        // Combine and sort the room numbers
        $combinedRoomNumbers = array_merge($roomNumbers1, $roomNumbers2);
        sort($combinedRoomNumbers);

        // Initialize the merged array
        $mergedArray = array();

        // Iterate over the sorted room numbers
        foreach ($combinedRoomNumbers as $roomNumber) {
            // Retrieve data from the first array if it exists
            $data1 = isset($finalArray[$roomNumber]) ? $finalArray[$roomNumber] : array();

            // Retrieve data from the second array if it exists
            $data2 = isset($LRfinalArray[$roomNumber]) ? $LRfinalArray[$roomNumber] : array();

            // Merge the data from both arrays
            $mergedData = array_merge($data1, $data2);

            // Add the merged data to the merged array
            $mergedArray[$roomNumber] = $mergedData;
        }

        // $mergedArray now contains the merged data with room numbers in ascending order

        $sql4 = "SELECT
        (RD+$LockRoomCount) AS RD,Rooms AS TotalRoom,ABS((RD+$LockRoomCount)-Rooms) as PEN from
        (
            SELECT
                (select  count (*)  from
                    (select ddvv.RoomNo as RoomNo from $DBName..Dw_VotersInfo as ddvv 
                        where ddvv.SF = 1 and ddvv.SubLocation_Cd = subloc.SubLocation_Cd
                        union 
                        select nnvv.RoomNo as RoomNo from $DBName..NewVoterRegistration as nnvv 
                        where nnvv.Subloc_cd = subloc.SubLocation_Cd
                    ) as tb1
                ) as RD
                ,sm.Rooms
            from [Survey_Entry_Data]..Society_Master as sm
            left join $DBName..SubLocationMaster as subloc on sm.Society_Cd = subloc.Survey_Society_Cd
            WHERE sm.Society_Cd = $Society_Cd
            GROUP BY subloc.SubLocation_Cd,sm.Rooms
        ) AS tb2
        ";

        $result4 = $db->ExecutveQuerySingleRowSALData($ULB,$sql4 , $userName, $appName, $developmentMode);

        if(!empty($result4)){
            $RD = $result4['RD'];
            $TR = $result4['TotalRoom'];
            $PEN = $result4['PEN'];
        }else{
            $RD = '';
            $TR = '';
            $PEN = '';
        }

        

        // print_r("<pre>");
        // print_r($mergedArray);
        // print_r("</pre>");


    }
}

?>
<script>document.body.style.zoom="90%"</script>
<style>

.Voter-Card:hover {
    transform: scale(1.1);
    position: relative;
}

.bounce {
  outline: 0;
  border-color: red;
  animation-name: bounce;
  animation-duration: 0.5s;
  animation-delay: 0.25s;
}

@keyframes bounce {
  0% {
    transform: translateX(0px);
    timing-function: ease-in;
  }
  20% {
    transform: translateX(20px);
    timing-function: ease-out;
  }
  40% {
    transform: translateX(-20px);
    timing-function: ease-in;
  }
  60% {
    transform: translateX(16px);
    timing-function: ease-out;
  }
  80% {
    transform: translateX(-16px);
    timing-function: ease-in;
  }
  100% {
    transform: translateX(0px);
    timing-function: ease-in;
  }
}



</style>

<div class="row match-height mb-0">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header mb-2" style="margin-top:-10">
                <div class="row" style="width:100%">
                    <div class="col-md-8" >
                        <div style="padding-top:10px">
                            <h4 class="card-title"><?php echo $SocietyName?></h4>
                        </div>
                    </div>
                    <div class="col-md-1" style="padding-top:10px;margin-left:0;" >
                        <a href="index.php?p=Survey-QC-Details<?php echo $SurveyQCDetailsListURL; ?>">
                            <button class="btn btn-primary btn-sm" title="List View" style=""><i class="feather icon-list" style="font-size:10px"></i></button>
                        </a>
                    </div>
                    <div class="col-md-3" > 
                        <div class="float-right" >
                            <p style="margin-bottom:0">
                             <b title="Voter / Non Voter / LockRoom">V / NV / LR : <?php echo $voterCount . " / " . $nonVoterCount . " / " . $LockRoomCount; ?></php></b>
                             <br style = "border-color:grey;"> 
                             <b title="Room Done / Total Room / Pending">RD / TR / PEN : <?php echo $RD . " / " . $TR . " / " . $PEN; ?></php></b>
                            </p>
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    if(sizeof($mergedArray)>0){
        $SrNo = 1;
        foreach($mergedArray AS $Key => $Value){
?>
            <div class="row match-height " style = "margin-top:-15px">
                <div class="col-md-12">
                    <div class="card" style="border:solid 1px #36abb9;">
                        <div class="card-header" style="margin-top:-8px;display: flex;align-items: center; text-align: center;display: inline;">
                            <?php //if(array_key_exists('LR_Cd', $Value) == true){  ?>
                                <!-- <div class="row">
                                    <div class="col-md-11">
                                        <h3 class="card-title" style="margin-left:140px"><b><?php //if(array_key_exists('LR_Cd', $Value) == false){ echo "Floor No- ". $Value[0]['FloorNo'] . " | ";};echo $SrNo . ") " ; $SrNo++;?>Room No - <?php //echo $Key; if(array_key_exists('LR_Cd', $Value) == false){ echo " (" . count($Value) .")"; }?></b></h3>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="" onclick="DeleteExtraLockRoom('<?php //echo $SubLocation_Cd ;?>','<?php //echo $Value['RoomNo'] ;?>')">
                                            <b><i class="fa fa-trash"></i></b>
                                        </a>
                                    </div>
                                </div> -->
                            <?php //}else{ ?>
                                <h3 class="card-title" style=""><b><?php if(array_key_exists('LR_Cd', $Value) == false){ echo "Floor No- ". $Value[0]['FloorNo'] . " | ";};echo $SrNo . ") " ; $SrNo++;?>Room No - <?php echo $Key; if(array_key_exists('LR_Cd', $Value) == false){ echo " (" . count($Value) .")"; }?></b></h3>
                            <?php //} ?>
                            <hr style="border-color:#36abb9;">
                        </div>
                        <?php if(array_key_exists('LR_Cd', $Value)){ ?>
                            <div class="content-body">
                                <div class="card-content">
                                    <div class="card-body" style="padding:3px;height:120px">
                                        <div class="avatar-content" style="margin-top:-10px;cursor:pointer;" title="<?php echo "Remark - " . $Value['Remark'] ?>" name="LockRoomBounceAnimation_<?php echo $Key;?>" id="LockRoomBounceAnimation_<?php echo $Key;?>" onclick="LockRoomBounceAnimation('<?php echo $Key;?>')">
                                            <img src="app-assets/images/lock.svg"  class=""  alt="" width="100%" height="120">
                                        </div>
                                        <!-- <img scr="app-assets/images/LockLandscape.png"> -->
                                    </div>
                                </div>
                            </div>
                        <?php }else{ $peopleCount = count($Value); ?>
                            <div class="content-body">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row gx-5" Style="margin-top:-25px;align-items: center;">
                                            <?php    
                                                foreach($Value AS $Key1 => $Value1){
                                            ?>
                                                <div class="col-xs-12 col-xl-4 col-md-4 col-12 m-0 p-0" >
                                                    <div class="Voter-Card" style="transition: transform 0.2s ease;margin: 9px 9px 9px 9px;padding:15px;border:solid 1px #36abb9;border-radius: 10px; background-color:white">
                                                        <div class="row">
                                                            <div class="col-xl-9 col-md-9 col-12">
                                                                <h5 style="margin-bottom: -10px;"><b><?php echo $Value1['FullName']; ?></b></h5>
                                                                <br>
                                                                    <?php 
                                                                        if($Value1['List_No']>0 && $Value1['Voter_Id']>0){
                                                                            echo "Voter Id - <b title='Assembly No / List No / Voter ID'>" . $Value1['Ac_No'] . '/' . $Value1['List_No'] . '/' . $Value1['Voter_Id'] . "</b>";
                                                                        }
                                                                        else{ ?>
                                                                        <a onclick="SurveyQCNonVoterEdit('<?php echo $DBName ?>','<?php echo $Value1['Voter_Cd'] ?>','<?php echo $SubLocation_Cd ?>','<?php echo $Key ?>','<?php echo $Value1['Name'] ?>', '<?php echo $Value1['MiddleName'] ?>', '<?php echo $Value1['Surname'] ?>')"><b style='color:red;'>Non Voter</b></a>
                                                                            <!-- // echo "<a href='index.php?p=Survey-QC-NonVoter-Edit&Name=".$Value1['Name']."&MiddleName=".$Value1['MiddleName']."&Surname=".$Value1['Surname']."' ><b style='color:red;'>Non Voter</b></a>";  -->
                                                                <?php   }
                                                                    ?>
                                                                <br>Mobile No - <?php echo $Value1['MobileNo']; ?>
                                                                <br>Birthdate - <?php echo substr($Value1['BirthDate'], 0, 11);?>
                                                                <br>Age - <?php echo $Value1['Age']; ?>
                                                                <br>Sex - <?php echo $Value1['Sex']; ?>
                                                                <br><b style='color:blue;'>Remark - <?php echo $Value1['Remark'];?></b>
                                                                <br>HStatus - <?php if($Value1['Hstatus'] == 'O'){echo "Owner";}elseif($Value1['Hstatus'] == 'R'){echo "Rented";}else{ echo $Value1['Sex'];} ?>
                                                                | Voted - <?php if($Value1['VidhanSabha'] == 1){echo "Yes";}elseif($Value1['VidhanSabha'] == 0){echo "No";}else{ echo $Value1['VidhanSabha']; }?>
                                                            </div>
                                                            <div class="col-xl-3 col-md-3 col-12" style="margin-left:-45px;margin-top:25px;">
                                                                <img src="<?php echo $Value1['Photo']; ?>" height="100"  width="100" style="border:solid 1px grey;border-radius:10px;">
                                                            </div>
                                                            <?php if($Value1['List_No'] == '0' && $Value1['Voter_Id'] == '0'){ ?>
                                                            <a class="" onclick="DeleteExtraVoter('<?php echo $Value1['Voter_Cd'] ?>')">
                                                                <b><i class="fa fa-trash"></i></b>
                                                            </a>
                                                            <?php }?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        <?php }  ?>
                    </div>
                </div>
            </div>
<?php 
        }
    }
?>

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


<script>
    // document.addEventListener('DOMContentLoaded', function() {
  function LockRoomBounceAnimation(Key){
    
    //   var image = document.querySelector('.bounce');
    // var image = document.getElementsByName('LockRoomBounceAnimation_'+Key);
    // image.classList.add('bounce');
    $("#LockRoomBounceAnimation_"+Key).addClass("bounce");
    
    setTimeout(function() {
    //   image.classList.remove('bounce');
    $("#LockRoomBounceAnimation_"+Key).removeClass("bounce");
    }, 500);
}


    //   image.addEventListener('click', function() {
    //     image.classList.add('bounce');
    
    //     // Remove the bounce class after the animation completes
    //     setTimeout(function() {
    //       image.classList.remove('bounce');
    //     }, 500);
    //   });


// });

</script>

