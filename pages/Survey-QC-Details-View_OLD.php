<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$peopleCount = '';

if(
    (isset($_GET['Society_Cd']) && !empty($_GET['Society_Cd'])) &&
    (isset($_GET['electionName']) && !empty($_GET['electionName'])) &&
    (isset($_GET['ExecutiveName']) && !empty($_GET['ExecutiveName']))
) {

    $Society_Cd = $_GET['Society_Cd'];
    $electionName = $_GET['electionName'];
    $ExecutiveName = $_GET['ExecutiveName'];
    echo $Date = $_GET['Date'];
    // $electionCd = $_GET['electionCd'];
    // $pagetype = 'List';


    // $electionName = 'BMC_ES_181';
    // $Society_Cd = 1218;
    
    $DBName = $db->GetDBName2($ULB,$electionName, $userName, $appName, $developmentMode);
    // $DBName = $db->GetDBName($electionName, $electionCd, $userName, $appName, $developmentMode);

    $sql = "SELECT * FROM $DBName..SubLocationMaster where Survey_Society_Cd = $Society_Cd ;";

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
        D.Occupation, 
        D.Education, 
        D.OwnerName,
        D.OwnerMobileNo, 
        D.District, 
        D.Religion, 
        D.SubCaste, 
        D.LockedButSurvey AS LBS, 
        D.Remark, 
        D.Sitename, 
        em.ExecutiveName AS UpdateByUserName,
        em.MobileNo AS UpdateByUserMobile,
        Convert(Varchar, D.UpdatedDate, 0) AS UpdatedDate,
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
                Sublocation_Cd AS Society_Cd, SocietyNameM, Hstatus AS HS, Occupation, Education, OwnerName, OwnerMobileNo,
                SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey, QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , 
                Col4, '' AS LR_Cd
                FROM  
                $DBName..Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
                AND SubLocation_Cd = $SubLocation_Cd
            UNION ALL 
            SELECT 
                Sitename, UpdateByUser, Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName,
                UpdatedDate, RoomNo, FullName,'' AS FullNameMar, Age, Sex, FamilyNo, 
                Convert(Varchar, BirthDate, 23) AS BirthDate,
                MobileNo, AndroidFormNo as FormNo, District, Religion, '' AS SF,Convert(Varchar, AnniversaryDate, 23) AS AnniversaryDate , Subloc_Cd AS Society_Cd, '' AS SocietyNameM,
                Hstatus AS HS, Occupation, Education, OwnerName, OwnerMobileNo, SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey,
                QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , Col4 , '' AS LR_Cd
                FROM 
                $DBName..NewVoterRegistration 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
                AND Subloc_cd = $SubLocation_Cd
            UNION ALL 
            SELECT 
                Sitename, UpdateByUser, 0 AS Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName, UpdatedDate, RoomNo,
                'LOCKED' AS FullName, '' AS FullNameMar, '' AS Age, '' AS Sex, '' AS FamilyNo, '' AS BirthDate,
                '' AS MobileNo, '' AS FormNo, '' AS District, '' AS Religion, '' AS SF, '' AS AnniversaryDate , Sublocation_Cd AS Society_Cd,
                SocietyNameM, '' AS HS, '' AS Occupation, '' AS Education, '' AS OwnerName, '' AS OwnerMobileNo, '' AS SubCaste, '' AS SurName,
                '' AS FirstName, '' AS MiddleName, '' AS LockedButSurvey, '' AS QC_UpdateByUser, '' AS QC_UpdatedDate, Remark, 'False' AS QC_Done ,
                 '' AS Col4,  LR_Cd
                FROM 
                $DBName..LockRoom 
                WHERE (Locked = 1) 
                AND Sublocation_Cd = $SubLocation_Cd
        ) AS D
        LEFT JOIN (SELECT UserName, Executive_Cd  FROM Survey_Entry_Data..User_Master GROUP BY UserName, Executive_Cd) um on (D.UpdateByUser = um.UserName)
        LEFT JOIN Survey_Entry_Data..Executive_Master em on (em.Executive_Cd = um.Executive_Cd) 
        ORDER BY  D.RoomNo
        "; 

        $result = $db->ExecutveQueryMultipleRowSALData($ULB,$sql3 , $userName, $appName, $developmentMode);

        usort($result, function($a, $b) {
            return intval($a['RoomNo']) - intval($b['RoomNo']);
        });

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

</style>

<div class="row match-height" style="margin-bottom:-10px">
    <div class="col-md-12">
        <div class="card" style="padding-bottom:-10px">
            <div class="card-header mt-0" style="padding-top:10px">
                <div class="row" style="width:100%">
                    <div class="col-md-9" >
                        <div style="padding-top:10px">
                            <h4 class="card-title"><?php echo $SocietyName;?></h4>
                        </div>
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
                            <table class="table table-hover" style="border:solid 1px black;" id="">
                                <thead>
                                <tr>
                                    <th  style=''>SrNo</th>
                                    <th  style=''>Corp No</th>
                                    <th  style=''>Full Name</th>
                                    <th  style=''>Floor No</th>
                                    <th  style=''>Count</th>
                                    <th  style=''>Room No</th>
                                    <th  style=''>Birthdate</th>
                                    <th  style=''>Age</th>
                                    <th  style=''>Sex</th>
                                    <th  style=''>Mobile No</th>
                                    <th  style=''>Updated Date</th>
                                    <?php //if($ExecutiveName != $result[0]['UpdateByUserName']){ echo "<th>Updated By</th>";}?>
                                    <th>Updated By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    if(sizeof($result) > 0){
                                        $srNo = 1;
                                        // $RoomNo = 1;
                                        $RoomNo = $result[0]['RoomNo'];
                                        $RoomNoCount = 1;

                                        foreach($result AS $Key=>$value){  
                                        ?>
                                        
                                        <tr <?php if($value['List_No'] == '0' && $value['Voter_Id'] == '0' && $value['FullName'] != 'LOCKED'){ $Type = 'NonVoter'; ?> style="background-color: #ffc0cb;" <?php }else if($value['FullName'] == 'LOCKED'){ $Type = 'LockRoom';?> style="color:red" title="<?php $value['Remark'] ?>"  <?php }else{ $Type = 'Voter'; } ?> >
                                            <td><?php echo $srNo++; ?></td>
                                            <td><?php if($value['FullName'] == 'LOCKED' || ($value['List_No'] == '0' && $value['Voter_Id'] == '0')){ echo '';}else{ echo $value['CorpNo']; } ?></td> 
                                            <td ><?php echo $value['FullName'];?></td>
                                            <!-- <td><?php //echo $Type ;?></td>  -->
                                            <td><?php echo $value['FloorNo'];?></td> 
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
                                            <td><?php echo "<b>" . $value['RoomNo'] . "</b>";?></td> 
                                            <td><?php echo $value['BirthDate'];?></td> 
                                            <td><?php if($value['FullName'] == 'LOCKED'){ echo "";}else{ echo $value['Age']; } ?></td> 
                                            <td><?php echo $value['Sex'];?></td> 
                                            <td><?php echo $value['MobileNo'];?></td> 
                                            <td><?php echo "<b>" . $value['UpdatedDate'] . "</b>";?></td> 
                                            <?php echo "<td>" . $value['UpdateByUserName'] .  ' - ' . $value['UpdateByUserMobile'];?> 
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

