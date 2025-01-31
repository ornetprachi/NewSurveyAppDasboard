<?php
$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$peopleCount = '';


// print_r($_GET);
if(
    (isset($_GET['electionName']) && !empty($_GET['electionName'])) &&
    (isset($_GET['SiteName']) && !empty($_GET['SiteName'])) &&
    (isset($_GET['SocietyName']) && !empty($_GET['SocietyName']))
) {

    $electionName = $_GET['electionName'];
    $SiteName = $_GET['SiteName'];
    $SocietyName = $_GET['SocietyName'];

    $DBName = $db->GetDBName2($ULB,$electionName, $userName, $appName, $developmentMode);

    if(!empty($DBName)){

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
                Sitename, AddedBy AS AddedBy, Voter_Cd, Ac_No, List_No, Voter_Id, Ward_No, SocietyName,
                UpdatedDate, RoomNo, FullName, FullNameMar, Age, Sex, FamilyNo,
                Convert(Varchar, BirthDate, 23) AS BirthDate,
                MobileNo, AndroidFormNo as FormNo, District, Religion, SF,Convert(Varchar, AnniversaryDate, 23) AS AnniversaryDate ,
                Sublocation_Cd AS Society_Cd, SocietyNameM, Hstatus AS HS, Occupation, Education, OwnerName, OwnerMobileNo,
                SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey, QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , 
                Col4, '' AS LR_Cd
                FROM  
                Dw_VotersInfo 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
		        AND SiteName = '$SiteName'
                AND SocietyName = '$SocietyName'
            UNION ALL 
            SELECT 
                Sitename, added_by AS AddedBy, Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName,
                UpdatedDate, RoomNo, FullName,'' AS FullNameMar, Age, Sex, FamilyNo, 
                Convert(Varchar, BirthDate, 23) AS BirthDate,
                MobileNo, AndroidFormNo as FormNo, District, Religion, '' AS SF,Convert(Varchar, AnniversaryDate, 23) AS AnniversaryDate , Subloc_Cd AS Society_Cd, '' AS SocietyNameM,
                Hstatus AS HS, Occupation, Education, OwnerName, OwnerMobileNo, SubCaste, SurName, Name AS FirstName, MiddleName,LockedButSurvey,
                QC_UpdateByUser, QC_UpdatedDate, Remark, QC_Done , Col4 , '' AS LR_Cd
                FROM 
                NewVoterRegistration 
                WHERE (UpdatedStatus = 'Y' OR UpdatedStatus = 'N') 
		        AND SiteName = '$SiteName'
                AND SocietyName = '$SocietyName'
            UNION ALL 
            SELECT 
                Sitename, added_by AS AddedBy, 0 AS Voter_Cd, 0 AS Ac_No, 0 AS List_No, 0 AS Voter_Id, Ward_No, SocietyName, UpdatedDate, RoomNo,
                'LOCKED' AS FullName, '' AS FullNameMar, '' AS Age, '' AS Sex, '' AS FamilyNo, '' AS BirthDate,
                '' AS MobileNo, '' AS FormNo, '' AS District, '' AS Religion, '' AS SF, '' AS AnniversaryDate , Sublocation_Cd AS Society_Cd,
                SocietyNameM, '' AS HS, '' AS Occupation, '' AS Education, '' AS OwnerName, '' AS OwnerMobileNo, '' AS SubCaste, '' AS SurName,
                '' AS FirstName, '' AS MiddleName, '' AS LockedButSurvey, '' AS QC_UpdateByUser, '' AS QC_UpdatedDate, Remark, 'False' AS QC_Done ,
                 '' AS Col4,  LR_Cd
                FROM 
                LockRoom 
                WHERE (Locked = 1) 
		        AND SiteName = '$SiteName'
                AND SocietyName = '$SocietyName'
        ) AS D
        LEFT JOIN (SELECT UserName, Executive_Cd  FROM Survey_Entry_Data..User_Master GROUP BY UserName, Executive_Cd) um on (D.AddedBy = um.Executive_Cd)
        LEFT JOIN Survey_Entry_Data..Executive_Master em on (em.Executive_Cd = um.Executive_Cd) 
        ORDER BY  D.SocietyName,D.RoomNo
        "; 

        // print_r($sql3);
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

    }
}
?>
<script>document.body.style.zoom="90%"</script>
<style>
    .table-container {
        height: 100%;
        /* overflow-y: scroll; */
        /* position: relative; */
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
                            <h4 class="card-title"><?php echo  $SocietyName ;?></h4><br>
                            <h4 class="card-title">Total Count - <?php echo sizeof(($result)) ;?></h4>
                        </div>
                    </div>
                    <div class="col-md-3" >
                        <div class="float-right" >
                            <p style="margin-bottom:0">
                                <b title="Voter / Non Voter / LockRoom">V / NV / LR : <?php echo $voterCount . " / " . $nonvoterCount . " / " . $lockroomCount; ?></php></b>
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
                                    <th>Updated Time</th>
                                    <th  style='width:200px;word-wrap: break-word;'>Society Name</th>
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
                                            <td><?php echo $value['FullName'];?></td>
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
                                            <td><?php echo $value['SocietyName'];?></td>
                                        </tr>
                                        <?php
                                            $RoomNo = $value['RoomNo'];
                                        }
                                    }else{ ?>
                                        <tr><td colspan="12">No Record Found</td></tr>
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

