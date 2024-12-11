
<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];
$ULB=$_SESSION['SurveyUtility_ULB'];

// print_r($electionName);
// die();

$from_Date = '';
$to_Date = '';
$QCStatus = 0;
$SurveyStatus = 1;
$ExecutiveCd = "";
$Site_Cd = "";
$Pocket_Cd = "";
$AssignCon = "";
$QcAssigned = "ALL";


$QCAssignList = array(); 

$fromDate = '2023-05-25';
$toDate = date('Y-m-d');
$assignDate = date('Y-m-d');



// if(isset($_SESSION['SurveyUA_SiteCd_QC_Assign']) && isset($_SESSION['SurveyUA_SiteName_QC_Assign'])){
//     $Site_Cd = $_SESSION['SurveyUA_SiteCd_QC_Assign'];
//     $SiteName = $_SESSION['SurveyUA_SiteName_QC_Assign'];
// }else{
//     $query = "SELECT 
//                 COALESCE(sm.Site_Cd,0) AS Site_Cd, 
//                 COALESCE(sm.ClientName,'') AS ClientName,
//                 COALESCE(sm.SiteName,'') AS SiteName,
//                 COALESCE(sm.Area, '') AS Area,
//                 COALESCE(sm.Ward_No,0) AS Ward_No,
//                 COALESCE(sm.Address,'') AS Address,
//                 COALESCE(sm.ElectionName,'') AS ElectionName,
//                 COALESCE(em.Election_Cd,0) AS Election_Cd
//             FROM Site_Master sm
//             INNER JOIN Election_Master em ON (sm.ElectionName = em.ElectionName)";

//     $dataSite = $db->ExecutveQueryMultipleRowSALData($query, $userName, $appName, $developmentMode);

//     if(sizeof($dataSite) > 0){

//         $Site_Cd = $dataSite[0]['Site_Cd'];
//         $SiteName = $dataSite[0]['SiteName'];
//         $electionCd = $dataSite[0]['Election_Cd'];
//         $electionName = $dataSite[0]['ElectionName'];

//         $_SESSION['SurveyUA_SiteCd_QC_Assign'] = $Site_Cd;
//         $_SESSION['SurveyUA_SiteName_QC_Assign'] = $SiteName;

//         $_SESSION['SurveyUA_Election_Cd'] = $electionCd;
//         $_SESSION['SurveyUA_ElectionName'] = $electionName;

//     }
    
// }

if
(
    (isset($_SESSION['SurveyUA_SiteCd_QC_Assign']) && !empty($_SESSION['SurveyUA_SiteCd_QC_Assign'])) && 
    (isset($_SESSION['SurveyUA_SiteName_QC_Assign']) && !empty($_SESSION['SurveyUA_SiteName_QC_Assign'])) &&
    (isset($_SESSION['SurveyUA_Election_Cd']) && !empty($_SESSION['SurveyUA_Election_Cd'])) && 
    (isset($_SESSION['SurveyUA_ElectionName']) && !empty($_SESSION['SurveyUA_ElectionName']))
)
{
    
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_QC_Assign'];
    $SiteName = $_SESSION['SurveyUA_SiteName_QC_Assign'];

    $electionCd = $_SESSION['SurveyUA_Election_Cd'];
    $electionName = $_SESSION['SurveyUA_ElectionName'];

}else{
    
    if($ULB == 'PANVEL'){
        $ElectionCond = " AND sm.ElectionName = 'PT188' ";
    }else{
        $ElectionCond = " ";
    }
    $DbNm = $db->GetDBNameULB($ULB,$userName, $appName, $developmentMode);
   
    $query = "SELECT 
                COALESCE(sm.Site_Cd,0) AS Site_Cd, 
                COALESCE(sm.ClientName,'') AS ClientName,
                COALESCE(sm.SiteName,'') AS SiteName,
                COALESCE(sm.Area, '') AS Area,
                COALESCE(sm.Ward_No,0) AS Ward_No,
                COALESCE(sm.Address,'') AS Address,
                COALESCE(sm.ElectionName,'') AS ElectionName,
                COALESCE(em.Election_Cd,0) AS Election_Cd
            FROM $DbNm..Site_Master sm
            INNER JOIN Survey_Entry_Data..Election_Master em ON (sm.ElectionName = em.ElectionName)
            WHERE em.ULB = '$ULB' 
            $ElectionCond
            ";
    // print_r($query);
    // die();
    $dataSite = $db->ExecutveQueryMultipleRowSALData($ULB,$query, $userName, $appName, $developmentMode);
    if(sizeof($dataSite) > 0){

        $Site_Cd = $dataSite[0]['Site_Cd'];
        $SiteName = $dataSite[0]['SiteName'];
        $electionCd = $dataSite[0]['Election_Cd'];
        $electionName = $dataSite[0]['ElectionName'];

        $_SESSION['SurveyUA_SiteCd_SurveyQC'] = $Site_Cd;
        $_SESSION['SurveyUA_SiteName_SurveyQC'] = $SiteName;

        $_SESSION['SurveyUA_Election_Cd'] = $electionCd;
        $_SESSION['SurveyUA_ElectionName'] = $electionName;

    }
    
}


if(
    isset($_SESSION['QC_Assign_tbl_pocketCd'])  &&
    isset($_SESSION['QC_Assign_tbl_fromDate']) &&
    isset($_SESSION['QC_Assign_tbl_toDate']) && 
    isset($_SESSION['QC_Assign_tbl_QCStatus']) &&
    isset($_SESSION['QC_Assign_tbl_QCAssigned']) &&
    isset($_SESSION['QC_Assign_tbl_SurveyStatus'])
)
{
    $Pocket_Cd = $_SESSION['QC_Assign_tbl_pocketCd'];
    $fromDate = $_SESSION['QC_Assign_tbl_fromDate'];
    $toDate = $_SESSION['QC_Assign_tbl_toDate'];
    $QCStatus = $_SESSION['QC_Assign_tbl_QCStatus'];
    $QcAssigned = $_SESSION['QC_Assign_tbl_QCAssigned'];  
    $SurveyStatus = $_SESSION['QC_Assign_tbl_SurveyStatus']; 
}


if($QCStatus == "2"){
    $QCStatusCondition = "";
}else{
    $QCStatusCondition = " AND sm.QC_Done_Flag = '$QCStatus' ";
}

if($fromDate == '' && $toDate == ''){
    $dateCondition = "";
}else{
    $dateCondition = " AND (CONVERT(VARCHAR, sm.Survey_UpdatedDate, 23) BETWEEN '$fromDate' AND '$toDate') ";
}

if($Pocket_Cd == "ALL" || $Pocket_Cd == ""){
    $PocketCondition = "";
}else{
    $PocketCondition = " AND sm.Pocket_Cd = '$Pocket_Cd' ";
}

if($SurveyStatus == "0"){
    $SurveyStatusCond = "AND sm.Executive_Cd IS NOT NULL
    $QCStatusCondition ";
}
elseif ($SurveyStatus == "1") {
    $SurveyStatusCond = " AND sm.Executive_Cd IS NOT NULL AND sm.Servey = 1 $dateCondition 
    $QCStatusCondition ";
}
elseif ($SurveyStatus == "2") {
    $SurveyStatusCond = " AND sm.Executive_Cd IS NOT NULL AND sm.Servey = 0 $QCStatusCondition ";
}
if($QcAssigned != "ALL"){
    $AssignCon = "AND sm.QC_Assign_To = $QcAssigned";
}else{
    $AssignCon = "";
}






$DBName = $db->GetDBName($ULB,$electionName, $electionCd, $userName, $appName, $developmentMode);

$query1 = "SELECT
                DISTINCT(Society_Cd) AS Society_Cd,tb2.ElectionName,tb2.SiteName,PocketName,tb2.Area,AreaMar,SocietyName,SocietyNameMar,Floor,Rooms,em.ExecutiveName AS ExecutiveName,em.MobileNo AS ExecutiveMobile,
                Building_Image,Building_Plate_Image,Sector,PlotNo,SurveyExecutive_Cd,ServeyFlag,SurveyStartDate,QC_Done_Flag,SocietyCategory,SubLocation_Cd,exm.ExecutiveName AS QC_Assigned_To,exm.MobileNo AS QC_Assigned_To_Mobile,vcount,nvcount,lockroom,(RD+lockroom) AS RD,NewRooms,ABS((RD+lockroom)-Rooms) as PEN from
                (
                    SELECT sm.ElectionName,sm.SiteName,sm.PocketName,sm.Area,sm.AreaMar, sm.SocietyName,sm.SocietyNameMar ,sm.Floor,sm.Rooms,sm.BList_UpdatedByUser,sm.QC_Assign_To,sm.Building_Image,sm.Building_Plate_Image,sm.Sector,sm.PlotNo,sm.Executive_Cd AS SurveyExecutive_Cd ,sm.Servey AS ServeyFlag,sm.QC_Done_Flag,sm.Col5 AS SocietyCategory,subloc.SubLocation_Cd, sm.Society_Cd,
                        (SELECT COUNT(*) FROM $DBName..Dw_VotersInfo WHERE SF = 1 and SubLocation_Cd = subloc.SubLocation_Cd) AS vcount,
                        (SELECT COUNT(*) FROM $DBName..NewVoterRegistration WHERE Subloc_cd = subloc.SubLocation_Cd) AS nvcount,
                        (SELECT COUNT(DISTINCT(RoomNo)) FROM $DBName..LockRoom WHERE SubLocation_Cd = subloc.SubLocation_Cd) AS lockroom,

                        (
                            SELECT MIN(tb1.Date) AS SurveyDate FROM
                            (
                                select MIN(CONVERT(VARCHAR,UpdatedDate,34)) AS Date from $DBName..Dw_VotersInfo WHERE SF = 1 and SubLocation_Cd = subloc.SubLocation_Cd
                                UNION ALL
                                select MIN(CONVERT(VARCHAR,UpdatedDate,34)) AS Date from $DBName..NewVoterRegistration WHERE Subloc_cd  = subloc.SubLocation_Cd
                            ) AS tb1
                        ) AS SurveyStartDate,

                        (select  count (*)  from
                            (select ddvv.RoomNo as RoomNo from $DBName..Dw_VotersInfo as ddvv 
                                where ddvv.SF = 1 and ddvv.SubLocation_Cd = subloc.SubLocation_Cd
                                union 
                                select nnvv.RoomNo as RoomNo from $DBName..NewVoterRegistration as nnvv 
                                where nnvv.Subloc_cd = subloc.SubLocation_Cd
                            ) as tb1) as RD
                        ,sm.NewRooms
                    from [Survey_Entry_Data]..Society_Master as sm
                    left join [Survey_Entry_Data]..Site_Master as sitemas
                    on sm.SiteName = sitemas.SiteName
                    left join $DBName..SubLocationMaster as subloc
                    on sm.Society_Cd = subloc.Survey_Society_Cd
                    left join $DBName..Dw_VotersInfo as vot
                    on subloc.SubLocation_Cd = vot.SubLocation_Cd
                    left join $DBName..NewVoterRegistration as nvot
                    on subloc.SubLocation_Cd = nvot.Subloc_cd 
                    left join $DBName..LockRoom as lroom
                    on subloc.SubLocation_Cd = lroom.Sublocation_Cd 
                    WHERE sm.Site_Cd = '$Site_Cd'
                    $PocketCondition 
                    $SurveyStatusCond
                    $AssignCon
                    GROUP BY sm.ElectionName,sm.SiteName,sm.PocketName,sm.Area,sm.AreaMar, sm.SocietyName,sm.SocietyNameMar,sm.Floor,sm.Rooms,sm.BList_UpdatedByUser,sm.QC_Assign_To,sm.Building_Image,sm.Building_Plate_Image,sm.Sector,sm.PlotNo,sm.Executive_Cd,sm.Servey,sm.QC_Done_Flag,sm.Col5,sm.NewRooms,subloc.SubLocation_Cd, sm.Society_Cd
            ) AS tb2
            LEFT JOIN Survey_Entry_Data..User_Master AS um ON (um.UserName = tb2.BList_UpdatedByUser) 
            LEFT JOIN Survey_Entry_Data..Executive_Master AS em ON (um.UserName = em.UserName)
            LEFT JOIN Survey_Entry_Data..Executive_Master AS exm ON (exm.Executive_Cd = tb2.QC_Assign_To)
            ORDER BY SurveyStartDate;;";



$db1=new DbOperation();
// echo $query1;
$QCAssignList = $db->ExecutveQueryMultipleRowSALData($ULB,$query1, $userName, $appName, $developmentMode);


// print_r("<pre>");
// print_r($QCAssignList);
// print_r("</pre>");


?>


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

    .dot {
        height: 15px;
        width: 15px;
        background-color: red;
        border-radius: 50%;
        display: inline-block;
    }
</style>
   

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php //include 'dropdown-electionname-QC-Assign.php'; ?>
                            </div> -->
                                  
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-site-QC-Assign.php'; ?>
                            </div> 
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-QC-Assign-pocket.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>Survey Status</label>
                                    <div class="controls">
                                        <select class="select2 form-control" id="SurveyStatus"  name="SurveyStatus" value="<?php echo $SurveyStatus; ?>" onchange="SurveyStatusChange(this.value)">
                                            <option value="0" <?php if($SurveyStatus == "0"){ ?> selected <?php } ?>>ALL</option>
                                            <option value="1" <?php if($SurveyStatus == "1"){ ?> selected <?php } ?>>Survey Done</option>
                                            <option value="2" <?php if($SurveyStatus == "2"){ ?> selected <?php } ?> style="color:red">Survey Ongoing</option>                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>QC Status</label>
                                    <div class="controls">
                                        <select class="select2 form-control" id="QCStatus" name="QCStatus" value="<?php echo $QCStatus; ?>"  onchange="ShowAssignedName(this.value)">
                                            <option value="2" <?php if($QCStatus == "2"){ ?> selected <?php } ?>>ALL</option>
                                            <option value="0" <?php if($QCStatus == "0"){ ?> selected <?php } ?>>Pending</option>
                                            <option value="1" <?php if($QCStatus == "1"){ ?> selected <?php } ?>>Done</option>                                               
                                            <option value="3" <?php if($QCStatus == "3"){ ?> selected <?php } ?>>Assigned But Pending</option>                                                
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label>From Date</label>
                                <div class="controls"> 
                                    <input type="date" name="fromDate" id="fromDate" value="<?php echo $fromDate; ?>"  class="form-control" placeholder="From Date" max="<?= date('Y-m-d'); ?>" <?php if ($SurveyStatus == "2" || $SurveyStatus == "0") {echo "disabled";} ?>>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <label>To Date</label>
                                <div class="controls"> 
                                    <input type="date" name="toDate" id="toDate" value="<?php echo $toDate; ?>"  class="form-control" placeholder="To Date" max="<?= date('Y-m-d'); ?>"  <?php if ($SurveyStatus == "2" || $SurveyStatus == "0") {echo "disabled";} ?>>
                                </div>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12" id="AssignedName" style="display:<?php if($QCStatus == 3 || $QCStatus == 1){ ?> block <?php }else{?> none <?php }?>;">
                                <?php include 'dropdown-QcDoneBy-name.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12" style="display:none;">
                                <input type="hidden" name="electionName" id="electionName" value="<?php echo $electionName; ?>"  class="form-control">
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-63col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="getQCAssignTableFilterData()">
                                        Refresh 
                                    </button>
                                </div> 
                            </div>
                        </div>
                        <hr class="">
                        <div class="row mt-2">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-executive-QC-Assign.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label for="society_cds"><b>Society Count</b></label>
                                <input type=text  class="form-control" style="font-weight: bold;" id="society_cnt" name="society_cnt" placeholder="">     
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label title="Voter / Non Voter / LockRoom" for="VNVLR"><b>(V / NV / LR) Count</b></label>
                                <input type=text  class="form-control" style="font-weight: bold;" id="VNVLR" name="VNVLR" placeholder="" title="Voter / Non Voter / LockRoom">     
                            </div>
                            <div class="col-xs-12 col-xl-2 col-md-2 col-12">
                                <label title="Room Done / Total Room / Pending" for="RDTRPEN"><b>(RD / TR / PEN) Count</b></label>
                                <input type=text  class="form-control" style="font-weight: bold;" id="RDTRPEN" name="RDTRPEN" placeholder=""  title="Room Done / Total Room / Pending">     
                            </div>
                            <input class="form-control form-control-sm basic" type="hidden" name="society_cds">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <div class="controls text-center"  style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="saveQCAssigncheckbox()" >
                                    <?php 
                                    if($QCStatus == 3){ 
                                        echo "Reassign";
                                    }else{
                                        echo "Assign";
                                    }
                                    ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-xl-12 col-md-12 col-12">
                                <div id="msgsuccess" class="controls alert alert-success text-center" role="alert" style="display: none;"></div>
                                <div id="msgfailed" class="controls alert alert-danger text-center" role="alert" style="display: none;"></div>
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

<div class="row match-height" id="QCAssignTblDataHideDiv">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <h4 class="card-title">Select Qc Assign List - ( <?php echo sizeof($QCAssignList);?> )</h4>
                </div>
                <?php if($ExcelExportButton == "show"){ ?>
                    <button id="exportBtn1"  class="btn btn-primary" onclick="ExportToExcel('xlsx','SurveyQCAssignWithNoOrdering')">Excel</button>
                <?php } ?>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">  
                                <div class="card-content">
                                    <div class="card-body card-dashboard pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-hover" id="SurveyQCAssignWithNoOrdering">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <input class="form-check-input checkbox_All" type="checkbox" style=" width: 20px; height: 20px;" id="SelectAllCheckbox" name="SelectAllCheckbox[]" onchange="setQCAssignALLIds(this)" >
                                                        </th>
                                                        <th style="background-color:#36abb9;color: white;">SrNo</th>
                                                        <?php if($QCStatus != '0'){ ?>
                                                            <th style="background-color:#36abb9;color: white;">QC Assigned To</th>
                                                        <?php } ?>
                                                            <th style="background-color:#36abb9;color: white;">QC Status</th>
                                                        <th style="background-color:#36abb9;color: white;">BList By</th>
                                                        <th style="background-color:#36abb9;color: white;">SurveyStartDate</th>
                                                        <!-- <th style="background-color:#36abb9;color: white;">Site</th> -->
                                                        <th style="background-color:#36abb9;color: white;">Pocket</th>
                                                        <th style="background-color:#36abb9;color: white;">Area</th>
                                                        <th style="background-color:#36abb9;color: white;">Society</th>
                                                        <th style="background-color:#36abb9;color: white;">Type</th>
                                                        <!-- <th style="background-color:#36abb9;color: white;">Sector</th> -->
                                                        <!-- <th style="background-color:#36abb9;color: white;">Plot No</th> -->
                                                        <th style="background-color:#36abb9;color: white;">Floors</th>
                                                        <th style="background-color:#36abb9;color: white;" title="Voter / Non Voter / LockRoom">V/NV/LR</th>
                                                        <th style="background-color:#36abb9;color: white;" title="Room Done / Total Room / Pending">RD/TR/PEN</th>
                                                        <th style="background-color:#36abb9;color: white;">Name Board</th>
                                                        <th style="background-color:#36abb9;color: white;">Image</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if(sizeof($QCAssignList) > 0){
                                                            $srNo = 1;
                                                            foreach($QCAssignList AS $Key=>$value){  
                                                            ?>
                                                            <tr style="<?php if($value['SurveyExecutive_Cd'] > 0 && $value['ServeyFlag'] == '0'){echo 'color:red';} ?>">
                                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    <input class="form-check-input checkbox" type="checkbox" style=" width: 20px; height: 20px;" value="'<?php echo $value['SocietyName']?>'~<?php echo $value['Society_Cd']?>~<?php echo $value['vcount']?>~<?php echo $value['nvcount']?>~<?php echo $value['lockroom']?>~<?php echo $value['RD']?>~<?php echo $value['Rooms']?>~<?php echo $value['PEN']?>" id="AssignCheckbox" onclick="setQCAssignIds()" >
                                                                    <!-- <input class="form-check-input checkbox" type="checkbox" style=" width: 20px; height: 20px;" value="<?php //echo $value['Society_Cd']?>,<?php //echo $value['SocietyName']?>,<?php //echo $value['vcount']?>,<?php //echo $value['nvcount']?>,<?php //echo $value['lockroom']?>,<?php //echo $value['RD']?>,<?php //echo $value['Rooms']?>,<?php //echo $value['PEN']?>" id="AssignCheckbox" onclick="setQCAssignIds()" > -->
                                                                </td>
                                                                <td><?php echo $srNo++; ?></td>
                                                                <?php if($QCStatus != '0'){ ?>
                                                                    <td><b><?php echo $value['QC_Assigned_To']?><br><?php echo $value['QC_Assigned_To_Mobile']?></b></td>
                                                                <?php } ?>
                                                                <td>
                                                                    <?php //echo $value['QC_Done_Flag']?>
                                                                    <div id="check_QC" name="check_QC" class="badge badge-<?php  if ($value['QC_Done_Flag'] == 1) { ?>success<?php } elseif($value['QC_Done_Flag'] == 0) { ?>danger<?php }elseif($value['QC_Done_Flag'] == 3){ ?>warning<?php } ?>">
                                                                        
                                                                        <?php if($value['QC_Done_Flag'] == 1){echo "Done";}elseif($value['QC_Done_Flag'] == 0){echo "Pending";}elseif($value['QC_Done_Flag'] == 3){echo "Assigned<br>But<br>Pending";} ?>
                                                                    </div>
                                                                </td>
                                                                <td><b><?php echo $value['ExecutiveName']?><br><?php echo $value['ExecutiveMobile']?></b></td>
                                                                <!-- <td><?php //echo $value['SiteName']?></td> -->
                                                                <td><?php echo $value['SurveyStartDate']?></td>
                                                                <td><?php echo $value['PocketName']?></td>
                                                                <td><?php echo $value['Area']?></td>
                                                                <td style="color:blue;"><?php echo "<b>" . $value['SocietyName'] . "</b>" ;?></td>
                                                                <td style="cursor:pointer" title="<?php if($value['SocietyCategory'] == '1'){echo 'Elite'; }elseif($value['SocietyCategory'] == '2'){echo 'Medium';}elseif($value['SocietyCategory'] == '3'){echo 'Low';} ?>" >
                                                                    <b><?php if($value['SocietyCategory'] == '1'){echo "A"; }elseif($value['SocietyCategory'] == '2'){echo "B";}elseif($value['SocietyCategory'] == '3'){echo "C";} ?></b>
                                                                </td>
                                                                <!-- <td><?php //echo $value['Sector']?></td> -->
                                                                <!-- <td><?php //echo $value['PlotNo']?></td> -->
                                                                <td><?php echo $value['Floor']?></td>
                                                                <td title="Voter / Non Voter / LockRoom"><b> <?php echo $value['vcount'] . "/" . $value['nvcount'] . "/" . $value['lockroom'] ?> </b></td>
                                                                <td title="Room Done / Total Room / Pending"><b> <?php echo $value['RD'] . "/" . $value['Rooms'] . "/" . $value['PEN'] ?> </b></td>
                                                                <td>
                                                                    <img src="<?php echo $value['Building_Plate_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;" <?php if($value['Building_Plate_Image'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                                                </td>
                                                                <td>
                                                                    <img src="<?php echo $value['Building_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;" <?php if($value['Building_Image'] != ''){ ?>onclick="window.open(this.src,'_blank','width=auto,height=auto')" <?php } ?>/>
                                                                </td>
                                                                
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

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
    function ExportToExcel(type,TableID) {
        var fn = "";
        var dl = "";
        var elt = document.getElementById(TableID);
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
            XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
            XLSX.writeFile(wb, fn || (TableID+'.'+ (type || 'xlsx')));
    }

    function ShowAssignedName(QCS){
    var dd = document.getElementById('AssignedName');

        if(QCS == 1 ||  QCS == 3 ){
            dd.style.display = 'block';
        }else{
            dd.style.display = 'none';
        }

    }
</script>
