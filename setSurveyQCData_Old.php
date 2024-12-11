<section id="dashboard-analytics">
    
<?php

$db=new DbOperation();
$userName=$_SESSION['SurveyUA_UserName'];
$appName=$_SESSION['SurveyUA_AppName'];
$electionCd=$_SESSION['SurveyUA_Election_Cd'];
$electionName=$_SESSION['SurveyUA_ElectionName'];
$developmentMode=$_SESSION['SurveyUA_DevelopmentMode'];

$Designation = $_SESSION['SurveyUA_Designation'];

if($Designation == 'CEO/Director' || $Designation == 'Manager' || $Designation == 'Senior Manager' || $Designation == 'Software Developer' || $Designation == 'Admin and Other' || $Designation == 'SP' || $Designation == 'Survey Supervisor'){
    $DesignationCond = '';
}else{
    $DesignationCond = 'Disabled';
}

$fromDate = date('Y-m-d');
$toDate = date('Y-m-d');
$assignDate = date('Y-m-d');

$QCAssignedTo = $_SESSION['SurveyUA_Executive_Cd_Login'];

$Site_Cd = "";
$Pocket_Cd = "";
$ExecutiveCd = "";
// $QCAssignedTo = "";
$QCStatus = 3; 
$SurveyQCList = array(); 


if(isset($_SESSION['SurveyUA_Election_Cd']) && isset($_SESSION['SurveyUA_ElectionName']))
{
    $electionCd=$_SESSION['SurveyUA_Election_Cd'];
    $electionName=$_SESSION['SurveyUA_ElectionName'];
}

if(isset($_SESSION['SurveyUA_SiteCd_SurveyQC']) && isset($_SESSION['SurveyUA_SiteName_SurveyQC'])){
    $Site_Cd = $_SESSION['SurveyUA_SiteCd_SurveyQC'];
    $SiteName = $_SESSION['SurveyUA_SiteName_SurveyQC'];
}else{
    $Site_Cd = "ALL";
    $SiteName = '';
}


if(isset($_SESSION['SurveyUA_AssignedTo_SurveyQC']) && isset($_SESSION['SurveyUA_AssignedTo_SurveyQC'])){
    $QCAssignedTo = $_SESSION['SurveyUA_AssignedTo_SurveyQC'];
}



if(
    (isset($_SESSION['SurveyQC_tbl_pocketCd']) && !empty($_SESSION['SurveyQC_tbl_pocketCd'])) &&
    (isset($_SESSION['SurveyQC_tbl_fromDate']) && !empty($_SESSION['SurveyQC_tbl_fromDate'])) &&
    (isset($_SESSION['SurveyQC_tbl_toDate']) && !empty($_SESSION['SurveyQC_tbl_toDate'])) &&
    (isset($_SESSION['SurveyQC_tbl_executiveCd']) && !empty($_SESSION['SurveyQC_tbl_executiveCd'])) &&
    (isset($_SESSION['SurveyQC_tbl_QCAssignedTo']) && !empty($_SESSION['SurveyQC_tbl_QCAssignedTo'])) &&
    (isset($_SESSION['SurveyQC_tbl_QCStatus']) && !empty($_SESSION['SurveyQC_tbl_QCStatus']))
)
{
    // $electionCd = $_SESSION['SurveyQC_tbl_election_Cd'];
    // $electionName = $_SESSION['SurveyQC_tbl_electionName'];
    // $Site_Cd = $_SESSION['SurveyQC_tbl_SiteCd'];
    // $SiteName = $_SESSION['SurveyQC_tbl_SiteName'];

    $Pocket_Cd = $_SESSION['SurveyQC_tbl_pocketCd'];
    $fromDate = $_SESSION['SurveyQC_tbl_fromDate'];
    $toDate = $_SESSION['SurveyQC_tbl_toDate'];
    $ExecutiveCd = $_SESSION['SurveyQC_tbl_executiveCd'];
    $QCAssignedTo = $_SESSION['SurveyQC_tbl_QCAssignedTo'];
    $QCStatus = $_SESSION['SurveyQC_tbl_QCStatus'];

    

    // unset($_SESSION['SurveyQC_tbl_electionName']);
}

if($Site_Cd == "ALL" || $Site_Cd == ""){
    $siteCondition = "";
}else{
    $siteCondition = " AND Site_Cd = '$Site_Cd' ";
}

if($Pocket_Cd == "ALL" || $Pocket_Cd == ""){
    $PocketCondition = "";
}else{
    $PocketCondition = " AND Pocket_Cd = '$Pocket_Cd' ";
}


if($ExecutiveCd == "ALL" || $ExecutiveCd == ""){
    $ExecutiveCdCondition = "";
}else{
    $ExecutiveCdCondition = " AND Executive_Cd = $ExecutiveCd ";
}

if($QCAssignedTo == "ALL" || $QCAssignedTo == ""){
    $QCAssignedToCondition = "";
}else{
    $QCAssignedToCondition = " AND QC_Assign_To = $QCAssignedTo ";
}

if($fromDate == '' && $toDate == ''){
    $dateCondition = "";
}else{
    $dateCondition = "AND (CONVERT(VARCHAR, AssignedDate, 23) BETWEEN '$fromDate' AND '$toDate')";
}

//todo add coalesce to all select item
$query1 = "  SELECT 
    COALESCE(Society_Cd,0) AS Society_Cd
    ,COALESCE(Site_Cd, 0) AS Site_Cd
    ,COALESCE(SiteName, '') AS SiteName
    ,COALESCE(SocietyName, '') AS SocietyName
    ,COALESCE(ElectionName, '') AS ElectionName
    ,COALESCE(SocietyNameMar, '') AS SocietyNameMar
    ,COALESCE(Area, '') AS Area
    ,COALESCE(AreaMar, '') AS AreaMar
    ,COALESCE(Floor, '') AS Floor
    ,COALESCE(Rooms, 0) AS Rooms
    ,COALESCE(PocketName, '') AS PocketName
    ,COALESCE(Pocket_Cd, 0) AS Pocket_Cd
    ,COALESCE(Executive_Cd, 0) AS Executive_Cd
    ,COALESCE(SequenceCode, 0) AS SequenceCode
    ,COALESCE(Building_Image, '') AS Building_Image
    ,COALESCE(Building_Plate_Image, '') AS Building_Plate_Image
    ,COALESCE(Latitude, '') AS Latitude
    ,COALESCE(Longitude, '') AS Longitude
    ,COALESCE(Sector, '') AS Sector
    ,COALESCE(PlotNo, '') AS PlotNo
    FROM Society_Master 
    WHERE ElectionName = '$electionName' $siteCondition $PocketCondition $QCAssignedToCondition $ExecutiveCdCondition $dateCondition AND QC_Done_Flag = $QCStatus";
$db1=new DbOperation();
// echo $query1;
$SurveyQCList = $db->ExecutveQueryMultipleRowSALData($query1, $userName, $appName, $developmentMode);

// print_r("<pre>");
// print_r($SurveyQCList);
// print_r("</pre>");


?>


<style type="text/css">
    

/* 
    img.center_1 {
    /* vertical-align: middle; */
    /* margin-left: 178px;
    border-style: none; }*/
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
            <div class="content-body">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-electionname-Survey-QC.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-site-Survey-QC.php'; ?>
                            </div>
                            <div class="col-xs-12 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-building-listing-pocket.php'; ?>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-executive-Survey-QC.php'; ?>
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
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <?php include 'dropdown-QC-Assigned-To.php'; ?>
                            </div>
                            <div class="col-xs-6 col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <label>QC Status</label>
                                    <div class="controls">
                                        <select class="select2 form-control"  name="QCStatus" value="<?php echo $QCStatus; ?>">
                                            <option value="3" <?php if($QCStatus == "3"){ ?> selected <?php } ?>>Assigned</option>
                                            <option value="1" <?php if($QCStatus == "1"){ ?> selected <?php } ?>>Done</option>                                               -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6 col-xl-2 col-md-2 col-12">
                                <div class="controls text-center" style="margin-top:25px">
                                    <button type="button" class="btn btn-primary float-right" onclick="getSurveyQCTableFilterData()">
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

<div class="row match-height">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Survey QC List - <?php echo "(" . sizeof($SurveyQCList) . ")"?> </h4>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation table-striped table-hover" id="BuildingSurveyWithNoOrdering">
                                                <thead>
                                                    <tr>
                                                        <th style="background-color:#36abb9;color: white;">Sr No</th>
                                                        <th style="background-color:#36abb9;color: white;">Action</th>
                                                        <th style="background-color:#36abb9;color: white;">Site</th>
                                                        <th style="background-color:#36abb9;color: white;">Society (ENG)</th>
                                                        <th style="background-color:#36abb9;color: white;">Society (MAR)</th>
                                                        <th style="background-color:#36abb9;color: white;">Area (ENG)</th>
                                                        <th style="background-color:#36abb9;color: white;">Area (MAR)</th>
                                                        <th style="background-color:#36abb9;color: white;">Floor</th>
                                                        <th style="background-color:#36abb9;color: white;">Room</th>
                                                        <th style="background-color:#36abb9;color: white;">Society</th>
                                                        <th style="background-color:#36abb9;color: white;">Name Board</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if(sizeof($SurveyQCList) > 0){
                                                            $srNo = 1;
                                                            foreach($SurveyQCList AS $Key=>$value){  
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $srNo++; ?></td>
                                                                <td>
                                                                    <a href="index.php?p=Survey-QC-Details&Society_Cd=<?php echo $value['Society_Cd']?>&electionName=<?php echo $electionName ?>&electionCd=<?php echo $electionCd ?>" onclick="" >
                                                                        <i class="feather icon-eye" style="font-size: 1.5rem;color:#70ccd4;"></i>
                                                                    </a>
                                                                </td>
                                                                <td><?php echo $value['SiteName']?></td>
                                                                <td><?php echo $value['SocietyName']?></td>
                                                                <td><?php echo $value['SocietyNameMar']?></td>
                                                                <td><?php echo $value['Area']?></td>
                                                                <td><?php echo $value['AreaMar']?></td>
                                                                <td><?php echo $value['Floor']?></td>
                                                                <td><?php echo $value['Rooms']?></td>
                                                                <td>
                                                                    <img src="<?php echo $value['Building_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;"/>
                                                                </td>
                                                                <td>
                                                                    <img src="<?php echo $value['Building_Plate_Image']?>" class="docimg" height="110" width="90" style="border:1px solid #007D88;border-radius:12px;"/>
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
