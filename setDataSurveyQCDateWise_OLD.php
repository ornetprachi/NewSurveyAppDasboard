<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

$Designation = $_SESSION['SurveyUA_Designation'];



$fromDate = date('Y-m-d');
$toDate = date('Y-m-d');

$Site_Cd = "";
$Pocket_Cd = "";
$ExecutiveCd = "";
// $QCAssignedTo = "";
$QCStatus = 3; 
$SurveyStatus = 0;
$SurveyQCList = array(); 

if($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other' || $Designation == 'SP' || $Designation == 'Survey Supervisor'){
    $DesignationCond = '';
    $QCAssignedTo = "";
}else{
    $DesignationCond = 'Disabled';
    $QCAssignedTo = $_SESSION['SurveyUA_Executive_Cd_Login'];
}


if
(
    (isset($_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise']) && !empty($_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'])) && 
    (isset($_SESSION['SurveyUA_SiteName_SurveyQC_DateWise']) && !empty($_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'])) &&
    (isset($_SESSION['SurveyUA_Election_Cd']) && !empty($_SESSION['SurveyUA_Election_Cd'])) && 
    (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']))
)
{
    
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'];
    $SiteName = $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'];

    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];

}else{
    $query = "SELECT 
                COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                COALESCE(sm.ClientName,'') AS ClientName,
                COALESCE(sm.SiteName,'') AS SiteName,
                COALESCE(sm.Area, '') AS Area,
                COALESCE(sm.Ward_No,0) AS Ward_No,
                COALESCE(sm.Address,'') AS Address,
                COALESCE(sm.ElectionName,'') AS ElectionName,
                COALESCE(em.Election_Cd,0) AS Election_Cd
            FROM Site_Master sm
            INNER JOIN Election_Master em ON (sm.ElectionName = em.ElectionName)
            WHERE em.ULB = '$ULB';
            ";

    $dataSite = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);

    if(sizeof($dataSite) > 0){

        $Site_Cd = $dataSite[0]['Site_Cd'];
        $SiteName = $dataSite[0]['SiteName'];
        $electionCd = $dataSite[0]['Election_Cd'];
        $electionName = $dataSite[0]['ElectionName'];

        $_SESSION['SurveyUA_SiteCd_SurveyQC_DateWise'] = $Site_Cd;
        $_SESSION['SurveyUA_SiteName_SurveyQC_DateWise'] = $SiteName;

        $_SESSION['SurveyUA_Election_Cd'] = $electionCd;
        $_SESSION['SurveyUA_ElectionName'] = $electionName;

    }  
}



if(
    isset($_SESSION['SurveyQCDateWise_tbl_fromDate']) && 
    isset($_SESSION['SurveyQCDateWise_tbl_toDate']) 
)
{
    $fromDate = $_SESSION['SurveyQCDateWise_tbl_fromDate'];
    $toDate = $_SESSION['SurveyQCDateWise_tbl_toDate'];
}




$DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);


// $query1 = "SELECT 
//             DISTINCT(tb1.SurveyExecutive) AS SurveyExecutive,
//             em.ExecutiveName AS ExecutiveName,
//             em.MobileNo AS MobileNo,
//             (SELECT COUNT(DISTINCT(tb3.SocietyCount)) AS SocietyCount
//                 FROM
//                 (
//                     select DISTINCT(SocietyName) AS SocietyCount from $DBName..Dw_VotersInfo 
//                     where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
//                     UNION ALL
//                     select DISTINCT(SocietyName) AS SocietyCount from $DBName..NewVoterRegistration 
//                     where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
//                     UNION ALL
//                     select DISTINCT(SocietyName) AS SocietyCount from $DBName..LockRoom 
//                     where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
//                 ) as tb3
//             ) AS SocietyCount,
//             (select count(*) AS Vcount from $DBName..Dw_VotersInfo where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS Vcount,
//             (select count(*) AS NVcount from $DBName..NewVoterRegistration where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS NVcount,
//             (select count(*) AS LRoom from $DBName..LockRoom where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS LRoom
//                 from
//                 (
//                     select 
//                     DISTINCT(UpdateByUser) AS SurveyExecutive
//                     from $DBName..Dw_VotersInfo where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'

//                     UNION ALL

//                     select 
//                     DISTINCT(UpdateByUser) AS SurveyExecutive
//                     from $DBName..NewVoterRegistration where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'

//                     UNION ALL
//                     select DISTINCT(UpdateByUser) AS SurveyExecutive
//                     from $DBName..LockRoom where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'
//                 ) AS tb1
//             INNER JOIN Survey_Entry_Data..User_Master um ON (tb1.SurveyExecutive = um.UserName)
//             INNER JOIN Survey_Entry_Data..Executive_Master em ON (um.Executive_Cd = em.Executive_Cd)
//             ";

$query1 = "SELECT 
            DISTINCT(tb1.SurveyExecutive) AS SurveyExecutive,
            em.ExecutiveName AS ExecutiveName,
            em.MobileNo AS MobileNo,
            (SELECT COUNT(DISTINCT(tb3.SocietyCount)) AS SocietyCount
                FROM
                (
                    select DISTINCT(SocietyName) AS SocietyCount from $DBName..Dw_VotersInfo 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    UNION ALL
                    select DISTINCT(SocietyName) AS SocietyCount from $DBName..NewVoterRegistration 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    UNION ALL
                    select DISTINCT(SocietyName) AS SocietyCount from $DBName..LockRoom 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                ) as tb3
            ) AS SocietyCount,
            (
                select SUM(tb5.BirthdayCount) from
                (
                    select count(BirthDate) AS BirthdayCount from $DBName..Dw_VotersInfo 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    UNION ALL
                    select count(BirthDate) AS BirthdayCount from $DBName..NewVoterRegistration 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                ) AS tb5
            ) AS BirthdayCount,
            (
                select SUM(tb6.MobileCount) from
                (
                    select count(MobileNo) AS MobileCount from $DBName..Dw_VotersInfo 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    AND MobileNo IS NOT NULL AND MobileNo <> ''
                    UNION
                    select count(MobileNo) AS MobileCount from $DBName..NewVoterRegistration 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    AND MobileNo IS NOT NULL AND MobileNo <> ''
                ) AS tb6
            ) AS MobileCount,
            (
                select SUM(tb7.LBS) from
                (
                    select count(LockedButSurvey) AS LBS from $DBName..Dw_VotersInfo 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    AND LockedButSurvey IN ('DNP','BNP','NBS')
                    UNION
                    select count(LockedButSurvey) AS LBS from $DBName..NewVoterRegistration 
                    where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive
                    AND LockedButSurvey IN ('DNP','BNP','NBS')
                ) AS tb7
            ) AS LBS,
            (select count(*) AS Vcount from $DBName..Dw_VotersInfo where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS Vcount,
            (select count(*) AS NVcount from $DBName..NewVoterRegistration where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS NVcount,
            (select count(*) AS LRoom from $DBName..LockRoom where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate' AND UpdateByUser = SurveyExecutive) AS LRoom
                from
                (
                    select 
                    DISTINCT(UpdateByUser) AS SurveyExecutive
                    from $DBName..Dw_VotersInfo where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'

                    UNION ALL

                    select 
                    DISTINCT(UpdateByUser) AS SurveyExecutive
                    from $DBName..NewVoterRegistration where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'

                    UNION ALL
                    select DISTINCT(UpdateByUser) AS SurveyExecutive
                    from $DBName..LockRoom where CONVERT(VARCHAR,UpdatedDate,23) BETWEEN '$fromDate' AND '$toDate'
                ) AS tb1
            INNER JOIN Survey_Entry_Data..User_Master um ON (tb1.SurveyExecutive = um.UserName)
            INNER JOIN Survey_Entry_Data..Executive_Master em ON (um.Executive_Cd = em.Executive_Cd)";



 $SurveyQCList = $db->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);


// print_r("<pre>");
// print_r($SurveyQCList);
// print_r("</pre>");

?>


<script>document.body.style.zoom="90%"</script>
<style type="text/css">
    
    img.docimg{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg:hover{
        z-index: 9999999990909090990909;
        transform: scale(5.2); 
        position: relative;
    }
    img.docimg1{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg1:hover{
        z-index: 9999999990909090990909;
        transform: scale(10.2); 
    }

    img.docimg2{
        transition: 0.4s ease;
        transform-origin: 10% 30%;
    }
    img.docimg2:hover{
        z-index: 9999999990909090990909;
        transform: scale(3.2); 
    }

    .collapse-simple > .card > .card-header > *::before {
        content: '\f2f9';
        font: normal normal normal 14px/1 'Material-Design-Iconic-Font';
        font-size: 1.25rem;
        text-rendering: auto;
        position: absolute;
        top: 8px;
        right: 0;
        color: black;
    }

    th{
        background: lightgrey;
    }
</style>

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body ">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-site-Survey-QC-DateWise.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>From Date</label>
                                <div class="controls"> 
                                    <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label>To Date</label>
                                <div class="controls"> 
                                    <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <input type="hidden" name="electionName" id="electionName" value="<?php echo $electionName; ?>"  class="form-control">
                                    <button type="button" class="btn btn-primary float-right" onclick="getSurveyQCDateWiseTableFilterData()">
                                            Refresh 
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="align-items:center">
        <center>
            <div id='spinnerLoader2' style='display:none'>
                <img src='app-assets/images/loader/loading.gif' width="50" height="50"/>
            </div>
        </center>
    </div>
</div>

<div class="row match-height" id="SurveyQCTblDataHideDiv">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Survey QC List - <?php echo " ( ". sizeof($SurveyQCList) . " )"; ?></h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                
                                <div class="card-content">
                                    <div class="card-body card-dashboard pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-striped table-hover" id="BuildingSurveyWithNoOrdering">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;">Action</th>
                                                        <th style="background-color:#36abb9;color: white;">surveyor</th>
                                                        <th style="background-color:#36abb9;color: white;">Society count</th>
                                                        <th style="background-color:#36abb9;color: white;">Voter count</th>
                                                        <th style="background-color:#36abb9;color: white;">Non Voter Count</th>
                                                        <th style="background-color:#36abb9;color: white;">LockRoom Count</th>
                                                        <th style="background-color:#36abb9;color: white;">Birthday Count</th>
                                                        <th style="background-color:#36abb9;color: white;">Mobile Count</th>
                                                        <th style="background-color:#36abb9;color: white;">LBS</th>

                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if(sizeof($SurveyQCList) > 0){
                                                            $srNo = 1;
                                                            foreach($SurveyQCList AS $Key=>$value){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td style="color: #36abb9;align-items:center;text-center;">
                                                                    <a href="index.php?p=Survey-QC-Details_DateWise-View&electionName=<?php echo $electionName ?>&ExecutiveName=<?php echo $value['ExecutiveName'] ?>&fromDate=<?php echo $fromDate; ?>&toDate=<?php echo $toDate; ?>&UserName=<?php echo $value['SurveyExecutive'] ?>" target="_blank" class="">
                                                                        <i class="fa fa-eye ml-1" style="color: #36abb9;"></i>
                                                                    </a>
                                                                </td>
                                                                <td><?php echo "<b>" . $value['ExecutiveName'] . "</b><br>" . $value['MobileNo']?></td>
                                                                <td><?php echo $value['SocietyCount']?></td>
                                                                <td><?php echo $value['Vcount']?></td>
                                                                <td><?php echo $value['NVcount']?></td>
                                                                <td><?php echo $value['LRoom']?></td>
                                                                <td><?php echo $value['BirthdayCount']?></td>
                                                                <td><?php echo $value['MobileCount']?></td>
                                                                <td><?php echo $value['LBS']?></td>

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
                </section>
            </div>
        </div>
    </div>
</div>
